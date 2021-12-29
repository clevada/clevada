<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use DB;
use App\Models\Core;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->config = Core::config();        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        

        return $this
            ->from($this->config->mail_from_address, $this->config->mail_from_name)
            ->subject('This is a test email')
            ->markdown('emails.core.test', [
                'app_name' => config('app.name'),
                'app_url' => config('app.url') ?? '#',
                'signature' => $this->config->mail_signature,
            ]);
    }
}
