<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory; // Подключаем трейт
    
    protected $fillable = ['title', 'description', 'price', 'min_bid', 'img', 'category_id'];
    
    /**
     * Связь "лот принадлежит категории".
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Связь "лот имеет много ставок".
     */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'lot_id');
    }
}
