<?php

namespace App\Services;

class SeoService
{
    public function apply(
        object $model,
        array $data
    ): void {
        $model->update([
            'meta_title' => $data['meta_title'] ?? null,

            'meta_description' => $data['meta_description'] ?? null,

            'canonical_url' => $data['canonical_url'] ?? null,

            'noindex' => $data['noindex'] ?? false,
        ]);
    }

    public function data(array $data): array
    {
        return [
            'meta_title' => $data['meta_title'] ?? null,

            'meta_description' => $data['meta_description'] ?? null,

            'canonical_url' => $data['canonical_url'] ?? null,

            'noindex' => $data['noindex'] ?? false,
        ];
    }
}
