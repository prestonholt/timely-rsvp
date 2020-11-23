<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function update(User $user, Invite $invite)
    {
        return $user->id == $invite->event->user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function delete(User $user, Invite $invite)
    {
        return $user->id == $invite->event->user->id;
    }

    /**
     * Determine whether the user can respond to the invite.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Event  $event
     * @return mixed
     */
    public function respond(User $user, Invite $invite)
    {
        return $user->phone == $invite->contact->phone;
    }

}
