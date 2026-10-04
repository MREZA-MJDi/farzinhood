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

                const meta = document.createElement("div");
                meta.className = "media-picker__meta";

                const name = document.createElement("span");
                name.textContent = file.name;
                name.title = file.name;

                const crop = document.createElement("button");
                crop.type = "button";
                crop.className = "media-picker__crop";
                crop.textContent = "برش";

                const remove = document.createElement("button");
                remove.type = "button";
                remove.className = "media-picker__remove";
                remove.textContent = "حذف";

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
                            <span>قسمت موردنظر را داخل قاب قرار بده.</span>
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
            const size = 720;

            canvas.width = size;
            canvas.height = size;

            const draw = () => {
                if (!image.naturalWidth) return;

                const cover = Math.max(size / image.naturalWidth, size / image.naturalHeight);
                const scale = cover * zoom;
                const width = image.naturalWidth * scale;
                const height = image.naturalHeight * scale;

                const maxX = Math.max(0, (width - size) / 2);
                const maxY = Math.max(0, (height - size) / 2);

                offsetX = Math.max(-maxX, Math.min(maxX, offsetX));
                offsetY = Math.max(-maxY, Math.min(maxY, offsetY));

                ctx.clearRect(0, 0, size, size);
                ctx.fillStyle = "#111";
                ctx.fillRect(0, 0, size, size);
                ctx.drawImage(
                    image,
                    (size - width) / 2 + offsetX,
                    (size - height) / 2 + offsetY,
                    width,
                    height
                );
            };

            image.onload = draw;
            image.src = URL.createObjectURL(file);

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
                offsetX += event.clientX - pointerX;
                offsetY += event.clientY - pointerY;
                pointerX = event.clientX;
                pointerY = event.clientY;
                draw();
            });

            canvas.addEventListener("pointerup", () => {
                dragging = false;
            });

            const close = () => {
                URL.revokeObjectURL(image.src);
                modal.remove();
            };

            closeButtons.forEach((button) => button.addEventListener("click", close));
            modal.querySelector(".media-cropper__backdrop").addEventListener("click", close);

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
            files = Array.from(input.files || []);
            render();
        });
    });
});
