<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact;
use App\Models\Invite;
use App\Models\Event;
use App\Rules\PhoneNumber;
use App\Rules\ExcludeThisPhone;

class InviteController extends Controller
{
    public function send(Request $request, Event $event) {
    	// Check if user can edit this event
    	$this->authorize('update', $event);

    	Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', new PhoneNumber, new ExcludeThisPhone($request->user()->phone)],
            'expiration_date' => ['required', 'date'],
            'expiration_time' => ['required', 'date_format:g:i A'],
        ])->validateWithBag('sendInvite');

    	// Check if contact exists in user's contacts
    	$contact = $request->user()->contacts()->where('phone', preg_replace('/\D+/', '', $request->phone))->first();

    	if ($contact) {
    		// Check if contact has already been invited
    		if ($contact->hasBeenInvited($event))
    			return back()->withErrors(['duplicate' => 'You have already invited this person'], 'sendInvite');

    		// If they have changed the name of an existing contact, update this name
    		if ($contact->name != $request->name) {
    			$contact->name = $request->name;
    			$contact->save();
    		}
    	} else {
    		// Create contact for this user
    		$contact = new Contact;
    		$contact->user()->associate($request->user());
    		$contact->name = $request->name;
    		$contact->phone = preg_replace('/\D+/', '', $request->phone);
    		$contact->save();
    		$contact->refresh();
    	}

    	// Invite contact
    	$invite = new Invite;
    	$invite->event()->associate($event);
    	$invite->contact()->associate($contact);
    	$invite->expiration = strtotime($request->expiration_date . ' ' . $request->expiration_time);
    	$invite->save();

    	// Notify person of invitation
    	

    	// Redirect to event page so that invites reload
    	return redirect()->route('event.edit', [$event]);
    }

    public function update(Request $request, Event $event, Invite $invite) {
    	$this->authorize('update', $invite);

    	Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'expiration_date' => ['required', 'date'],
            'expiration_time' => ['required', 'date_format:g:i A'],
        ])->validateWithBag('sendInvite');

    	$contact = $invite->contact()->first();
    	$contact->name = $request->name;
    	$contact->save();

        $invite->expiration = strtotime($request->expiration_date . ' ' . $request->expiration_time);
        $invite->save();

    	// Redirect to event page so that invites reload
    	return redirect()->route('event.edit', [$event]);
    }

    public function delete(Request $request, Event $event, Invite $invite) {
    	$this->authorize('delete', $invite);

    	$invite->delete();

    	// Redirect to event page so that invites reload
    	return redirect()->route('event.edit', [$event]);
    }

}
