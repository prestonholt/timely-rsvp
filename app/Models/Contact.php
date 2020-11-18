<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\User;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_registered'];

    
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function invites() {
        return $this->hasMany('App\Models\Invite');
    }

    public function hasBeenInvited(Event $event) {
    	return $event->invites()->where('contact_id', $this->id)->exists();
    }

    /**
     * Get whether the contact is registered.
     *
     * @return bool
     */
    public function getIsRegisteredAttribute()
    {
        return User::where('phone', $this->phone)->exists();
    }
}
