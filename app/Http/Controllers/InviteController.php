<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use App\Models\Invite;
use App\Models\Event;
use App\Rules\PhoneNumber;
use App\Rules\ExcludeThisPhone;
use Inertia\Inertia;
use App\Notifications\InviteSent;
use App\Notifications\InviteAccepted;
use App\Notifications\InviteDeclined;
use Illuminate\Support\Facades\URL;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as CalendarEvent;

class InviteController extends Controller
{
    public function send(Request $request, Event $event) {
    	// Check if user can edit this event
    	$this->authorize('update', $event);

        $request->merge([
            'phone' => preg_replace('/\D+/', '', $request->phone),
        ]);

    	Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', new PhoneNumber, new ExcludeThisPhone($request->user()->phone)],
            'expiration_date' => ['required', 'date', 'after_or_equal:now'],
            'expiration_time' => ['required', 'date_format:g:i A'],
        ])->validateWithBag('sendInvite');

    	// Check if contact exists in user's contacts
    	$contact = $request->user()->contacts()->where('phone', $request->phone)->first();

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
    		$contact->phone = $request->phone;
    		$contact->save();
    		$contact->refresh();
    	}

    	// Invite contact
    	$invite = new Invite;
    	$invite->event()->associate($event);
    	$invite->contact()->associate($contact);
    	$invite->expiration = strtotime($request->expiration_date . ' ' . $request->expiration_time);
    	$invite->save();
        $invite->refresh();

    	// Notify person of invitation
        if ($request->send_invite)
    	   $invite->notify(new InviteSent);

    	// Redirect to event page so that invites reload
    	return redirect()->route('event.edit', [$event])->with('invite_url', $invite->shortUrl->textableUrl());
    }

    public function update(Request $request, Event $event, Invite $invite) {
    	$this->authorize('update', $invite);

    	Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'expiration_date' => ['required', 'date', 'after_or_equal:now'],
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

    public function show(Request $request, Invite $invite) {
        // this means they are coming from a public link, so if they are logged in, redirect them to the private route 

        // check if they have an account but arent logged in and are using either public or private link
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        if (Auth::check() && $request->user()->hasBeenInvitedTo($invite->event)) {
            return redirect()->route('event.view', [$invite->event]);
        }

        if ($invite->event->has_ended) {
            return Inertia::render('Error', ['status' => 491]);
        }

        if (!$invite->can_view) {
            return Inertia::render('Error', ['status' => 490]);
        }

        // Check if they are logged in. If so, check whether logged in phone number is same as invite contact phone number
        // If phone numbers are same, redirect them to the private route
        // If phone numbers are different, that means they clicked on a link for a different account......
            // could check if they are using someone elses link for correct event
            // if they have not been invited to this event at all, log them out and keep them on public route

        // if they have an account and are logged out, tell them to view all of their events to log in 
        // if they dont have an account, tell them to register to view all events and create their own
        return Inertia::render('Event/View', [
            'event' => $invite->event()->with('user')->first(),
            'invites' => $invite->event->invites()->with('contact:id,name')->get(),
            'invite' => $invite->load('contact'),
            'routes' => [
                'respond' => URL::signedRoute('invite.respond', [$invite]),
                'calendar' => URL::signedRoute('invite.calendar', [$invite])
            ]
        ]);
    }

    public function respond(Request $request, Invite $invite) {
        if (Auth::check()) {
            $this->authorize('respond', $invite);
        } else {
            if (!$request->hasValidSignature())
                abort(401);
        }

        if ($invite->has_expired)
            return back();

        Validator::make($request->all(), [
            'accepted' => ['nullable', 'boolean'],
        ])->validateWithBag('response');

        $invite->accepted = $request->accepted;
        $invite->save();

        if ($invite->accepted)
            $notification = new InviteAccepted($invite);
        else
            $notification = new InviteDeclined($invite);

        $invite->event->user->notify($notification);

        return back();
    }

    public function calendar(Request $request, Invite $invite) {
        if (Auth::check()) {
            $this->authorize('respond', $invite);
        } else {
            if (!$request->hasValidSignature())
                abort(401);
        }

        $event = CalendarEvent::create($invite->event->name)
            ->startsAt($invite->event->start_date);

        if ($invite->event->end_date)
            $event->endsAt($invite->event->end_date);
        else
            $event->endsAt($invite->event->start_date->addHour()->addMinutes(30));

        if ($invite->event->description)
            $event->description($invite->event->description);

        $calendar = Calendar::create('TimelyRSVP')
            ->withTimezone()
            ->event($event);

        return response($calendar->get(), 200, [
           'Content-Type' => 'text/calendar',
           'Content-Disposition' => 'attachment; filename="timelyrsvp.ics"',
           'charset' => 'utf-8',
        ]);
    }

}
