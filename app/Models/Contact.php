<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

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
    
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function invites() {
        return $this->hasMany('App\Models\Invite');
    }

    public function hasBeenInvited(Event $event) {
    	return $event->invites()->where('contact_id', $this->id)->exists();
    }
}
