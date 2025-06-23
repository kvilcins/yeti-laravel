<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'contact_details',
        'avatar',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            if ($user->avatar && !str_starts_with($user->avatar, 'img/')) {
                Storage::delete('public/' . $user->avatar);
            }
        });
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            $isPublic = str_starts_with($this->avatar, 'img/');
            return $isPublic ? asset($this->avatar) : asset('storage/' . $this->avatar);
        }

        return asset('img/default-avatar.jpg');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOwnerOf($lot)
    {
        return $this->id === $lot->user_id;
    }

    public function lots()
    {
        return $this->hasMany(Item::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function wonLots()
    {
        return $this->hasMany(Item::class, 'winner_id');
    }
}
