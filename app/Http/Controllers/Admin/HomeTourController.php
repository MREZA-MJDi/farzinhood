<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeTourFrame;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

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
            DB::transaction(function () use ($validated, $nextOrder, &$storedPaths): void {
                foreach ($validated['images'] as $index => $image) {
                    $path = $image->store('home-tour/' . $validated['scene'], 'public');

                    if (! $path) {
                        throw new \RuntimeException('ذخیره یکی از تصاویر انجام نشد.');
                    }

                    $storedPaths[] = $path;

                    HomeTourFrame::query()->create([
                        'scene' => $validated['scene'],
                        'image' => $path,
                        'title' => 'زاویه ' . ($nextOrder + $index + 1),
                        'description' => null,
                        'alt' => $validated['scene'] === 'hood'
                            ? 'نمای محصول هود فرزین'
                            : 'نمای محصول سینک فرزین',
                        'sort_order' => $nextOrder + $index,
                        'object_position_x' => 50,
                        'object_position_y' => 50,
                        'scale' => 1.03,
                        'is_active' => true,
                    ]);
                }
            }, 3);
        } catch (Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.home-tour.index')
            ->with('success', 'فریم‌ها اضافه شدند. عنوان، توضیح و تنظیمات هر زاویه را در کارت همان فریم کامل کن.');
    }

    public function update(Request $request, HomeTourFrame $frame): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1200'],
            'alt' => ['nullable', 'string', 'max:180'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:100000'],
            'object_position_x' => ['required', 'integer', 'between:0,100'],
            'object_position_y' => ['required', 'integer', 'between:0,100'],
            'scale' => ['required', 'numeric', 'between:1,1.5'],
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
        }

        try {
            $frame->update([
                'title' => $validated['title'] ?? null,
                'description' => $validated['description'] ?? null,
                'alt' => $validated['alt'] ?? null,
                'sort_order' => $validated['sort_order'],
                'object_position_x' => $validated['object_position_x'],
                'object_position_y' => $validated['object_position_y'],
                'scale' => $validated['scale'],
                'is_active' => (bool) $validated['is_active'],
                ...($newPath ? ['image' => $newPath] : []),
            ]);
        } catch (Throwable $exception) {
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
            ->with('success', 'اطلاعات این زاویه ذخیره شد.');
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
