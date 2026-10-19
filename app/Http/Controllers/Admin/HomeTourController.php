<?php

namespace App\\Http\\Controllers\\Admin;

use App\\Http\\Controllers\\Controller;
use App\\Models\\HomeTourFrame;
use Illuminate\\Http\\RedirectResponse;
use Illuminate\\Http\\Request;
use Illuminate\\Support\\Facades\\Storage;
use Illuminate\\View\\View;

class HomeTourController extends Controller
{
    public function index(): View
    {
        $frames = HomeTourFrame::query()
            ->orderBy('scene')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('scene');

        return view('admin.home-tour.index', compact('frames'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'scene' => ['required', 'in:hood,sink'],
            'images' => ['required', 'array', 'min:1', 'max:30'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
        ]);

        $nextOrder = (int) HomeTourFrame::query()
            ->where('scene', $validated['scene'])
            ->max('sort_order') + 1;

        $storedPaths = [];

        try {
            foreach ($validated['images'] as $index => $image) {
                $path = $image->store('home-tour/' . $validated['scene'], 'public');

                if (! $path) {
                    throw new \\RuntimeException('ذخیره یکی از تصاویر انجام نشد.');
                }

                $storedPaths[] = $path;

                HomeTourFrame::create([
                    'scene' => $validated['scene'],
                    'image' => $path,
                    'alt' => $validated['scene'] === 'hood'
                        ? 'فریم تور ۳۶۰ درجه هود فرزین'
                        : 'فریم تور ۳۶۰ درجه سینک فرزین',
                    'sort_order' => $nextOrder + $index,
                    'is_active' => true,
                ]);
            }
        } catch (\\Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.home-tour.index')
            ->with('success', 'فریم‌های تور با موفقیت اضافه شدند. برای ترتیب نمایش، شماره ترتیب هر فریم را تغییر بده.');
    }

    public function update(Request $request, HomeTourFrame $frame): RedirectResponse
    {
        $validated = $request->validate([
            'alt' => ['nullable', 'string', 'max:180'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:100000'],
            'is_active' => ['required', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:10240'],
        ]);

        $oldImage = $frame->image;
        $newPath = null;

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('home-tour/' . $frame->scene, 'public');

            if (! $newPath) {
                return back()->withErrors(['image' => 'ذخیره تصویر جدید انجام نشد.'])->withInput();
            }

            $validated['image'] = $newPath;
        }

        try {
            $frame->update([
                'alt' => $validated['alt'] ?? null,
                'sort_order' => $validated['sort_order'],
                'is_active' => (bool) $validated['is_active'],
                ...($newPath ? ['image' => $newPath] : []),
            ]);
        } catch (\\Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($newPath && $oldImage !== $newPath) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('admin.home-tour.index')
            ->with('success', 'تنظیمات فریم ذخیره شد.');
    }

    public function destroy(HomeTourFrame $frame): RedirectResponse
    {
        $path = $frame->image;
        $frame->delete();

        Storage::disk('public')->delete($path);

        return redirect()
            ->route('admin.home-tour.index')
            ->with('success', 'فریم از تور حذف شد.');
    }
}
