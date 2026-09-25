<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrlHit extends Model
{
    public $timestamps = false;

    protected $fillable = ['short_url_id', 'created_at'];

    public function shortUrl()
    {
        return $this->belongsTo(ShortUrl::class);
    }
}
