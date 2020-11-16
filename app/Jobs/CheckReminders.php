<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Invite;
use App\Notifications\DayInviteReminder;
use App\Notifications\WeekInviteReminder;

class CheckReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send a reminder 1 day before
        $start_day_before = Carbon::now()->minute(0)->second(0)->microsecond(0)->addDay()->toDateTimeString();
        $end_day_before = Carbon::now()->minute(0)->second(59)->microsecond(0)->addDay()->addMinutes(59)->toDateTimeString();
        $invites_day_reminder = Invite::where('accepted', null)->whereBetween('expiration', [$start_time, $end_time])->get();

        foreach ($invites_day_reminder as $invite) {
            $invite->notify(new DayInviteReminder);
        }

        // Send a reminder 1 week before
        $start_week_before = Carbon::now()->minute(0)->second(0)->microsecond(0)->addWeek()->toDateTimeString();
        $end_week_before = Carbon::now()->minute(0)->second(59)->microsecond(0)->addWeek()->addMinutes(59)->toDateTimeString();
        $invites_week_reminder = Invite::where('accepted', null)->whereBetween('expiration', [$start_time, $end_time])->get();

        foreach ($invites_week_reminder as $invite) {
            $invite->notify(new WeekInviteReminder);
        }

    }
}
