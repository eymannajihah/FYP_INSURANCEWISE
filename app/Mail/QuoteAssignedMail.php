<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $phone;
    public $assignedTo;

    public function __construct($name, $phone, $assignedTo)
    {
        $this->name       = $name;
        $this->phone      = $phone;
        $this->assignedTo = $assignedTo;
    }

    public function build()
    {
        return $this->subject('Your Insurance Quote Request Has Been Assigned')
                    ->view('emails.quote_assigned')
                    ->with([
                        'name'       => $this->name,
                        'phone'      => $this->phone,
                        'assignedTo' => $this->assignedTo,
                    ]);
    }
}
