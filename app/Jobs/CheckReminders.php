<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Models\Invite;
use App\Notifications\DayInviteReminder;
use App\Notifications\WeekInviteReminder;
use Illuminate\Support\Facades\Log;

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
        $day_reminders = 0;
        $week_reminders = 0;

        // Send a reminder 1 day before
        $start_day_before = Carbon::now()->minute(0)->second(0)->microsecond(0)->addDay()->toDateTimeString();
        $end_day_before = Carbon::now()->minute(0)->second(59)->microsecond(0)->addDay()->addMinutes(59)->toDateTimeString();
        $invites_day_reminder = Invite::where('accepted', null)->whereBetween('expiration', [$start_day_before, $end_day_before])->get();

        foreach ($invites_day_reminder as $invite) {
            $day_reminders++;
            $invite->notify(new DayInviteReminder);
        }

        // Send a reminder 1 week before
        $start_week_before = Carbon::now()->minute(0)->second(0)->microsecond(0)->addWeek()->toDateTimeString();
        $end_week_before = Carbon::now()->minute(0)->second(59)->microsecond(0)->addWeek()->addMinutes(59)->toDateTimeString();
        $invites_week_reminder = Invite::where('accepted', null)->whereBetween('expiration', [$start_week_before, $end_week_before])->get();

        foreach ($invites_week_reminder as $invite) {
            $week_reminders++;
            $invite->notify(new WeekInviteReminder);
        }

        Log::info('Day Reminders Sent: ' . $day_reminders . ', Week Reminders Sent: ' . $week_reminders);
    }
}
