<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyPhone;
use App\Notifications\ResetPassword;
use App\Models\Invite;
use App\Models\Event;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'verification_code',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'created_at',
        'updated_at',
        'phone_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function events() {
        return $this->hasMany('App\Models\Event');
    }

    public function contacts() {
        return $this->hasMany('App\Models\Contact');
    }

    public function invites() {
        $invites = [];

        // Find where current user phone number has been added as a contact to other users
        $contacts = Contact::where('phone', $this->phone)->pluck('id');
        $invites = Invite::whereIn('contact_id', $contacts)->where(function ($query) {
            $query->where('expiration', '>=', date("Y-m-d H:i:s"))->orWhere('accepted', true);
        })->with(['event', 'event.user'])->get()->sortByDesc('event.start_date')->values();

        return $invites;
    }

    public function hasBeenInvitedTo(Event $event) {
        foreach ($event->invites()->with('contact')->get() as $invite)
            if ($invite->contact->phone == $this->phone)
                return true;

        return false;
    }

    /**
     * Get the user's links.
     */
    public function shortUrls()
    {
        return $this->morphMany('App\Models\ShortUrl', 'linkable');
    }

    /**
     * Determine if the user has verified their phone number.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the phone verification notification.
     *
     * @return void
     */
    public function sendPhoneVerificationNotification()
    {
        $this->generateVerificationCode();
        $this->notify(new VerifyPhone);
    }

    /**
     * Get the phone number that should be used for verification.
     *
     * @return string
     */
    public function getPhoneForVerification()
    {
        return $this->phone;
    }

    /**
     * Get the phone number where password reset links are sent.
     *
     * @return string
     */
    public function getPhoneForPasswordReset()
    {
        return $this->phone;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function routeNotificationForTwilio() 
    {
        return $this->phone;
    }

    public function generateVerificationCode() {
        $this->forceFill([
            'verification_code' => random_int(100000, 999999)
        ])->save();
    }
}