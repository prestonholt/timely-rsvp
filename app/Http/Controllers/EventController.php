<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Invite;
use Illuminate\Support\Facades\Validator;
use App\Models\ShortUrl;
use Illuminate\Support\Carbon;

class EventController extends Controller
{
    public function __construct() {
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Home', [
            'events' => $request->user()->events()->get(['id', 'name', 'start_date']),
            'invites' => $request->user()->invites()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Event/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:g:i A'],
            'end_date' => ['nullable', 'required_if:end_toggle,true','date', 'after_or_equal:start_date'],
            'end_time' => ['nullable', 'required_if:end_toggle,true','date_format:g:i A']
        ])->validateWithBag('createEvent');

        $event = new Event;
        $event->user()->associate($request->user());
        $event->name = $request->name;
        $event->start_date = strtotime($request->start_date . ' ' . $request->start_time);
        if ($request->end_toggle)
            $event->end_date = strtotime($request->end_date . ' ' . $request->end_time);
        $event->save();

        $event->refresh();

        return redirect()->route('event.details', [$event]);
    }

    public function details(Request $request, Event $event) {
        $this->authorize('update', $event);

        return Inertia::render('Event/Details', [
            'event' => $event
        ]);
    }

    public function storeDetails(Request $request, Event $event) {
        $this->authorize('update', $event);

        Validator::make($request->all(), [
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string']
        ])->validateWithBag('eventDetails');

        $event->location = $request->location;
        $event->description = $request->description;
        $event->save();


        return redirect()->route('event.edit', [$event]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Event $event)
    {
        // this is the private link to view an event, so we know they are logged in 
        // update the event view policy to make sure they have been invited

        return Inertia::render('Event/View', [
            'event' => $event->load('user'),
            'invites' => $event->invites()->with('contact:id,name')->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return Inertia::render('Event/Edit', [
            'event' => $event,
            'invites' => $event->invites()->with('contact')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:g:i A'],
            'end_date' => ['nullable', 'required_if:end_toggle,true','date', 'after_or_equal:start_date'],
            'end_time' => ['nullable', 'required_if:end_toggle,true','date_format:g:i A'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string']
        ])->validateWithBag('editEvent');

        $event->name = $request->name;
        $event->location = $request->location;
        $event->description = $request->description;
        $event->start_date = strtotime($request->start_date . ' ' . $request->start_time);
        if ($request->end_toggle)
            $event->end_date = strtotime($request->end_date . ' ' . $request->end_time);
        else
            $event->end_date = null;
        
        $event->save();

        return redirect()->route('event.edit', [$event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Event $event)
    {
        $event->delete();

        return redirect()->route('home');
    }
}
