<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    use HasFactory;

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

    public function event() {
        return $this->belongsTo('App\Models\Event');
    }

    public function contact() {
        return $this->belongsTo('App\Models\Contact');
    }
}
