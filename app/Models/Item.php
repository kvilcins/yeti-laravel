<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Убедитесь, что этот трейт подключен
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory; // Подключаем трейт
    
    protected $fillable = ['title', 'description', 'price', 'min_bid', 'img', 'category_id'];
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
