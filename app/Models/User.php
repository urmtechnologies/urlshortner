<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'invited_by_id',
        'client_id',

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by_id');
    }

    public function invitees()
    {
        return $this->hasMany(User::class, 'invited_by_id');
    }

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
