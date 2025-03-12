<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bids';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lot_id',
        'user_id',
        'bid_amount',
        'bid_time',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'bid_time' => 'datetime',
    ];
    
    /**
     * Get the lot associated with the bid.
     */
    public function lot()
    {
        return $this->belongsTo(Item::class);
    }
    
    /**
     * Get the user who made the bid.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
