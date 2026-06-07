<?php

namespace App\Listeners;

use App\Events\SystemUpdateCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewsletterNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SystemUpdateCreated $event): void
    {
        $subscribers = \App\Models\NewsletterSubscriber::all();

        foreach ($subscribers as $subscriber) {
            \Illuminate\Support\Facades\Mail::to($subscriber->email)
                ->send(new \App\Mail\NewsletterUpdateMail($event->title, $event->message));
        }
    }
}
