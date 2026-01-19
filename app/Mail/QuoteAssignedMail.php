<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue; // Optional: for queueing emails

class QuoteAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $name;
    public $phone;
    public $assignedTo;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $phone
     * @param string $assignedTo
     */
    public function __construct($name, $phone, $assignedTo)
    {
        $this->name       = $name;
        $this->phone      = $phone;
        $this->assignedTo = $assignedTo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
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
