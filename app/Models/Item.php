<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cocur\Slugify\Slugify;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'description', 'price', 'min_bid', 'img', 'category_id', 'timer'];

    /**
     * Relationship "item belongs to category".
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship "item has many bids".
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'lot_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = $model->slug ?? static::generateSlug($model->title);
        });

        static::updating(function ($model) {
            if ($model->isDirty('title')) {
                $model->slug = static::generateSlug($model->title);
            }
        });
    }

    private static function generateSlug($text)
    {
        $slugify = new Slugify();
        $baseSlug = $slugify->slugify($text);
        $slug = $baseSlug;

        $counter = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
