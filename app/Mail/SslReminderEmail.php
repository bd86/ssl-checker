<?php

namespace App\Mail;

use App\Models\SslList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SslReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $list;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Collection $list)
    {
        $this->list = $list;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.sslReminder');
    }
}
