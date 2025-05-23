<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class MessageSentListener
{
    /**
     * @param MessageSent $event
     * @return void
     */
    public function handle(MessageSent $event): void
    {
        $to = $event->message->getTo()[0]->getAddress();
        if ($to == config('cms.report_logs.email')) {
            return;
        }

        $recipients = [$to];

        if ($cc = $event->message->getCc()) {
            foreach ($cc as $item) {
                $recipients[] = $item->getAddress();
            }
        }
        if ($bcc = $event->message->getBcc()) {
            foreach ($bcc as $item) {
                $recipients[] = $item->getAddress();
            }
        }

        Log::channel('sent')->info($event->message->getSubject().' » '.implode(', ', $recipients));
    }
}
