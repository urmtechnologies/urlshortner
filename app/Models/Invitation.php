<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'client_name',
        'client_id',
        'role',
        'email',
        'token_hash',
        'invited_by_id',
        'expires_at',
        'accepted_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by_id');
    }

    public function getStatusAttribute(): string
    {
        if ($this->accepted_at) {
            return 'Accepted';
        }

        return $this->expires_at->isPast() ? 'Expired' : 'Pending';
    }
}
