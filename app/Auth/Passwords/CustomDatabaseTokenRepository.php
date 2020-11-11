<?php

namespace App\Auth\Passwords;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CustomDatabaseTokenRepository extends DatabaseTokenRepository
{    
	/**
     * Create a new token record.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return string
     */
    public function create(CanResetPasswordContract $user)
    {
        $phone = $user->getPhoneForPasswordReset();

        $this->deleteExisting($user);

        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.
        $token = $this->createNewToken();

        $this->getTable()->insert($this->getPayload($phone, $token));

        return $token;
    }

    /**
     * Delete all existing reset tokens from the database.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return int
     */
    protected function deleteExisting(CanResetPasswordContract $user)
    {
        return $this->getTable()->where('phone', $user->getPhoneForPasswordReset())->delete();
    }

    /**
     * Build the record payload for the table.
     *
     * @param  string  $phone
     * @param  string  $token
     * @return array
     */
    protected function getPayload($phone, $token)
    {
        return ['phone' => $phone, 'token' => $this->hasher->make($token), 'created_at' => new Carbon];
    }

    /**
     * Determine if a token record exists and is valid.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $token
     * @return bool
     */
    public function exists(CanResetPasswordContract $user, $token)
    {
        $record = (array) $this->getTable()->where(
            'phone', $user->getPhoneForPasswordReset()
        )->first();

        return $record &&
               ! $this->tokenExpired($record['created_at']) &&
                 $this->hasher->check($token, $record['token']);
    }

    /**
     * Determine if the token has expired.
     *
     * @param  string  $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        return Carbon::parse($createdAt)->addSeconds($this->expires)->isPast();
    }

    /**
     * Determine if the given user recently created a password reset token.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @return bool
     */
    public function recentlyCreatedToken(CanResetPasswordContract $user)
    {
        $record = (array) $this->getTable()->where(
            'phone', $user->getPhoneForPasswordReset()
        )->first();

        return $record && $this->tokenRecentlyCreated($record['created_at']);
    }
}