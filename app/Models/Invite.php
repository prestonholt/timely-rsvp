<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\URL;

class Invite extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $dates = [
    	'expiration'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'accepted' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($invite) {
            // Automatically generate short url for every invite
            $shortUrl = new ShortUrl;
            $shortUrl->linkable()->associate($invite);
            $shortUrl->destination = URL::signedRoute('invite.view', [$invite]);
            $shortUrl->save();
        });
    }

    public function routeNotificationForTwilio()
    {
        return $this->contact->phone;
    }

    public function event() {
        return $this->belongsTo('App\Models\Event');
    }

    public function contact() {
        return $this->belongsTo('App\Models\Contact');
    }

    /**
     * Get the invite's short url.
     */
    public function shortUrl()
    {
        return $this->morphOne('App\Models\ShortUrl', 'linkable');
    }
}
