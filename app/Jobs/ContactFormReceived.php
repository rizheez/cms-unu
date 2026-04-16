<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ContactFormReceived implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public ContactMessage $contactMessage)
    {
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipient = setting('site_email');

        if (! is_string($recipient) || $recipient === '') {
            return;
        }

        Mail::to($recipient)->send(new ContactMessageReceived($this->contactMessage));
    }
}
