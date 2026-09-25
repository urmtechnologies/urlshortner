<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name'];

    public function shortUrls()
    {
        return $this->hasMany(ShortUrl::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
