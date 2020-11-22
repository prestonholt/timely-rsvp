<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['has_ended'];

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
    	'start_date',
    	'end_date'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function invites() {
        return $this->hasMany('App\Models\Invite');
    }

    public function getHasEndedAttribute() {
        return $this->end_date ? $this->end_date->lt(Carbon::now()) : $this->start_date->lt(Carbon::now()->addHour());
    }
}
