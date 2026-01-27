<?php

namespace App\Mail;

use App\Models\Registration;
use App\Models\Reunion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Registration_Account extends Mailable
{
    use Queueable, SerializesModels;

	/**
	* The variable instances
	*
	* @var Registration $registration
	* @var Reunion $reunion
	*/
	public $user;
	public $member;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->member = $user->member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Jackson-Green Reunion Account Registration')->view('emails.new_account');
    }
}
