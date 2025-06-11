<?php

namespace App\Mail;

use App\Models\Registration;
use App\Models\Reunion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Registration_User extends Mailable
{
    use Queueable, SerializesModels;

	/**
	* The variable instances
	*
     * @var Registration $registration
     * @var Reunion $reunion
	*/
	public $registration;
	public $reunion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($registration, $reunion)
    {
        $this->registration = $registration;
        $this->reunion = $reunion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->reunion->reunion_year . ' Reunion Registration')->view('emails.new_message');
    }
}
