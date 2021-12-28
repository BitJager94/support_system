<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Events\AnsweredEvent;

use App\Mail\QuestionAnswered;


class AnsweredListener implements ShouldQueue
{


    public function retryUntil()
    {
        return now()->addMinutes(1);
    }

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(AnsweredEvent $event)
    {
        Mail::send(new QuestionAnswered($event->user->email));
    }
}
