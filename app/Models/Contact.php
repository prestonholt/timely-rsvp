<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Contact extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function hasBeenInvited(Event $event) {
    	return $event->invites()->where('contact_id', $this->id)->exists();
    }
}
