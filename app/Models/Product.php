<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'brand',
        'short_description',
        'description',
        'price',
        'old_price',
        'discount',
        'stock',
        'rating',
        'review_count',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'canonical_url',
        'noindex',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'price' => 'integer',
        'old_price' => 'integer',
        'discount' => 'integer',
        'stock' => 'integer',
        'rating' => 'decimal:1',
        'review_count' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'noindex' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopeWithWishlistedBy(Builder $query, ?User $user): Builder
    {
        if (! $user || ! $user->isCustomer()) {
            return $query;
        }

        return $query->withExists([
            'wishlists as is_wishlisted' => fn (Builder $wishlistQuery) =>
                $wishlistQuery->where('user_id', $user->getKey()),
        ]);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query
            ->where('is_active', true)
            ->where('is_featured', true);
    }

    /**
     * @return HasMany
     */
    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * @return HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
