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

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'min_bid',
        'img',
        'category_id',
        'timer',
        'user_id',
        'status',
        'winner_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'lot_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->img) {
            $isPublic = str_starts_with($this->img, 'img/');
            return $isPublic ? asset($this->img) : asset('storage/' . $this->img);
        }

        return asset('img/noimage.jpg');
    }

    public function getHighestBid()
    {
        return $this->bids()->orderBy('bid_amount', 'desc')->first();
    }

    public function getCurrentPrice()
    {
        $highestBid = $this->getHighestBid();
        return $highestBid ? $highestBid->bid_amount : $this->price;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = $model->slug ?? static::generateSlug($model->title);
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
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
