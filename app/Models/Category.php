<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Cocur\Slugify\Slugify;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'slug', 'class'];
    
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->slug = $model->slug ?? static::generateSlug($model->name);
        });
        
        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = static::generateSlug($model->name);
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
