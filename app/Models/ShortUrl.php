<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    use HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($shortUrl) {
            $shortUrl->short = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 6);
        });
    }

    /**
     * Get the owning imageable model.
     */
    public function linkable()
    {
        return $this->morphTo();
    }

    public function fullUrl() {
    	return url('/') . '/s/' . $this->short;
    }

    public function textableUrl() {
        return $this->fullUrl();
    }
}
