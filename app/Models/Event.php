<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $dates = [
    	'start_date',
    	'end_date'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function invites() {
        return $this->hasMany('App\Models\Invite');
    }
}
