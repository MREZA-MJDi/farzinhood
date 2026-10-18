/*
|--------------------------------------------------------------------------
| Shared Media Picker — preview + client-side crop
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-media-picker]").forEach((root) => {
        const input = root.querySelector("[data-media-input]");
        const preview = root.querySelector("[data-media-preview]");

        if (!input || !preview) return;

        let files = [];
        const parsedRatio = Number.parseFloat(root.dataset.mediaRatio || "1");
        const cropRatio = Number.isFinite(parsedRatio) && parsedRatio >= 0.5 && parsedRatio <= 2.5
            ? parsedRatio
            : 1;
        const maxFilesValue = Number.parseInt(root.dataset.mediaMaxFiles || (input.multiple ? "10" : "1"), 10);
        const maxFiles = Number.isFinite(maxFilesValue) && maxFilesValue > 0 ? maxFilesValue : 1;
        const maxBytesValue = Number.parseInt(root.dataset.mediaMaxSize || String(5 * 1024 * 1024), 10);
        const maxBytes = Number.isFinite(maxBytesValue) && maxBytesValue > 0 ? maxBytesValue : 5 * 1024 * 1024;
        const allowedTypes = new Set(["image/jpeg", "image/png", "image/webp"]);

        let feedback = root.querySelector("[data-media-feedback]");
        if (!feedback) {
            feedback = document.createElement("p");
            feedback.className = "media-picker__feedback hidden";
            feedback.setAttribute("role", "status");
            feedback.setAttribute("aria-live", "polite");
            root.insertBefore(feedback, preview);
        }

        const showFeedback = (messages = []) => {
            feedback.textContent = messages.join(" ");
            feedback.classList.toggle("hidden", messages.length === 0);
            feedback.classList.toggle("media-picker__feedback--error", messages.length > 0);
        };

        const syncInput = () => {
            const transfer = new DataTransfer();
            files.forEach((file) => transfer.items.add(file));
            input.files = transfer.files;
        };

        const render = () => {
            preview.innerHTML = "";

            files.forEach((file, index) => {
                const card = document.createElement("div");
                card.className = "media-picker__item";

                const image = document.createElement("img");
                image.alt = file.name;
                image.className = "media-picker__preview";
                image.style.aspectRatio = `${cropRatio} / 1`;

                const meta = document.createElement("div");
                meta.className = "media-picker__meta";

                const name = document.createElement("span");
                name.textContent = file.name;
                name.title = file.name;

                const crop = document.createElement("button");
                crop.type = "button";
                crop.className = "media-picker__crop";
                crop.textContent = "برش";
                crop.title = `برش تصویر ${file.name}`;
                crop.setAttribute("aria-label", `برش تصویر ${file.name}`);

                const remove = document.createElement("button");
                remove.type = "button";
                remove.className = "media-picker__remove";
                remove.textContent = "حذف";
                remove.title = `حذف تصویر ${file.name}`;
                remove.setAttribute("aria-label", `حذف تصویر ${file.name}`);

                crop.addEventListener("click", () => openCropper(index));
                remove.addEventListener("click", () => {
                    files.splice(index, 1);
                    syncInput();
                    render();
                });

                meta.append(name, crop, remove);
                card.append(image, meta);
                preview.append(card);

                const reader = new FileReader();
                reader.onload = (event) => {
                    image.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
        };

        const openCropper = (fileIndex) => {
            const file = files[fileIndex];
            if (!file) return;

            const modal = document.createElement("div");
            modal.className = "media-cropper";

            modal.innerHTML = `
                <div class="media-cropper__backdrop"></div>
                <div class="media-cropper__dialog" role="dialog" aria-modal="true" aria-label="برش تصویر">
                    <div class="media-cropper__header">
                        <div>
                            <strong>برش تصویر</strong>
                            <span>قاب خروجی ${Math.round(cropRatio * 100) / 100}:1 است؛ تصویر را جابه‌جا و بزرگ‌نمایی کن.</span>
                        </div>
                        <button type="button" data-crop-close aria-label="بستن">×</button>
                    </div>
                    <div class="media-cropper__stage">
                        <canvas data-crop-canvas></canvas>
                    </div>
                    <div class="media-cropper__controls">
                        <label>
                            <span>بزرگ‌نمایی</span>
                            <input type="range" min="1" max="3" step="0.01" value="1" data-crop-zoom>
                        </label>
                    </div>
                    <div class="media-cropper__actions">
                        <button type="button" data-crop-cancel>انصراف</button>
                        <button type="button" data-crop-save>اعمال برش</button>
                    </div>
                </div>
            `;

            document.body.append(modal);

            const canvas = modal.querySelector("[data-crop-canvas]");
            const ctx = canvas.getContext("2d");
            const zoomInput = modal.querySelector("[data-crop-zoom]");
            const closeButtons = modal.querySelectorAll("[data-crop-close], [data-crop-cancel]");
            const image = new Image();

            let zoom = 1;
            let offsetX = 0;
            let offsetY = 0;
            let dragging = false;
            let pointerX = 0;
            let pointerY = 0;
            const cropWidth = 720;
            const cropHeight = Math.round(cropWidth / cropRatio);

            canvas.width = cropWidth;
            canvas.height = cropHeight;
            canvas.style.aspectRatio = `${cropRatio} / 1`;

            const draw = () => {
                if (!image.naturalWidth) return;

                const cover = Math.max(canvas.width / image.naturalWidth, canvas.height / image.naturalHeight);
                const scale = cover * zoom;
                const width = image.naturalWidth * scale;
                const height = image.naturalHeight * scale;

                const maxX = Math.max(0, (width - canvas.width) / 2);
                const maxY = Math.max(0, (height - canvas.height) / 2);

                offsetX = Math.max(-maxX, Math.min(maxX, offsetX));
                offsetY = Math.max(-maxY, Math.min(maxY, offsetY));

                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = "#111";
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(
                    image,
                    (canvas.width - width) / 2 + offsetX,
                    (canvas.height - height) / 2 + offsetY,
                    width,
                    height
                );
            };

            image.onload = draw;

            zoomInput.addEventListener("input", () => {
                zoom = Number(zoomInput.value);
                draw();
            });

            canvas.addEventListener("pointerdown", (event) => {
                dragging = true;
                pointerX = event.clientX;
                pointerY = event.clientY;
                canvas.setPointerCapture(event.pointerId);
            });

            canvas.addEventListener("pointermove", (event) => {
                if (!dragging) return;
                const rect = canvas.getBoundingClientRect();
                const scaleX = rect.width > 0 ? canvas.width / rect.width : 1;
                const scaleY = rect.height > 0 ? canvas.height / rect.height : 1;

                offsetX += (event.clientX - pointerX) * scaleX;
                offsetY += (event.clientY - pointerY) * scaleY;
                pointerX = event.clientX;
                pointerY = event.clientY;
                draw();
            });

            canvas.addEventListener("pointerup", () => {
                dragging = false;
            });

            const imageUrl = URL.createObjectURL(file);
            image.src = imageUrl;

            const close = () => {
                if (!modal.isConnected) return;
                URL.revokeObjectURL(imageUrl);
                modal.remove();
                input.focus();
            };

            closeButtons.forEach((button) => button.addEventListener("click", close));
            modal.querySelector(".media-cropper__backdrop").addEventListener("click", close);
            modal.addEventListener("keydown", (event) => {
                if (event.key === "Escape") {
                    event.preventDefault();
                    close();
                    return;
                }

                if (event.key !== "Tab") return;

                const focusable = [...modal.querySelectorAll("button, input")].filter((element) => !element.disabled);
                if (!focusable.length) return;

                const first = focusable[0];
                const last = focusable[focusable.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            });

            modal.querySelector("[data-crop-close]")?.focus();

            modal.querySelector("[data-crop-save]").addEventListener("click", () => {
                canvas.toBlob((blob) => {
                    if (!blob) return;

                    const cropped = new File(
                        [blob],
                        file.name.replace(/\.[^.]+$/, "") + "-cropped.webp",
                        { type: "image/webp", lastModified: Date.now() }
                    );

                    files[fileIndex] = cropped;
                    syncInput();
                    render();
                    close();
                }, "image/webp", 0.88);
            });
        };

        input.addEventListener("change", () => {
            const selected = Array.from(input.files || []);
            const valid = [];
            const messages = [];

            selected.forEach((file) => {
                const hasAllowedType = allowedTypes.has(file.type)
                    || /\\.(jpe?g|png|webp)$/i.test(file.name);

                if (!hasAllowedType) {
                    messages.push(`«${file.name}» تصویر پشتیبانی‌شده نیست؛ JPG، PNG یا WebP انتخاب کن.`);
                    return;
                }

                if (file.size > maxBytes) {
                    messages.push(`حجم «${file.name}» بیشتر از ${Math.round(maxBytes / (1024 * 1024))} مگابایت است.`);
                    return;
                }

                valid.push(file);
            });

            if (valid.length > maxFiles) {
                messages.push(`حداکثر ${maxFiles} تصویر در این فرم قابل انتخاب است؛ فقط ${maxFiles} تصویر اول نگه داشته می‌شود.`);
            }

            files = valid.slice(0, maxFiles);
            syncInput();
            showFeedback(messages);
            render();
        });
    });
});
