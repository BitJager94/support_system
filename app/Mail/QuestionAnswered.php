<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

class QuestionAnswered extends Mailable
{
    use Queueable, SerializesModels;

    private $email;

    public function __construct($target)
    {
        $this->email = $target;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)
                    ->markdown('emails.answered');
    }
}
