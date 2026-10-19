<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HomeTourFrame extends Model
{
    use HasFactory;

    protected $fillable = [
        'scene',
        'image',
        'title',
        'description',
        'alt',
        'sort_order',
        'object_position_x',
        'object_position_y',
        'scale',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'object_position_x' => 'integer',
            'object_position_y' => 'integer',
            'scale' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
