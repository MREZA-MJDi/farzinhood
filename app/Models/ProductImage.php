<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
        'alt',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'sort_order' => 'integer',
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute(): string
    {
        $image = trim((string) $this->image);

        if ($image === '') {
            return '';
        }

        if (Str::startsWith($image, [
            'http://',
            'https://',
            '//',
            'data:',
        ])) {
            return $image;
        }

        return asset('storage/' . ltrim($image, '/'));
    }
}
