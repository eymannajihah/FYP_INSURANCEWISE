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
    public $email; // ✅ declare the property

    public function __construct($name, $phone, $assignedTo, $email)
    {
        $this->name       = $name;
        $this->phone      = $phone;
        $this->assignedTo = $assignedTo;
        $this->email      = $email; // assign it
    }

    public function build()
    {
        return $this->to($this->email) // ✅ send to this email
                    ->subject('Your Insurance Quote Request Has Been Assigned')
                    ->view('emails.quote_assigned');
    }
}
