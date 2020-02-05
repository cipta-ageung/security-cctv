<?php

namespace App\Mail;

use App\Models\Occupant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordPenghuni extends Mailable
{
    use Queueable, SerializesModels;

    public $occupant;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Occupant $occupant)
    {
        $this->occupant = $occupant;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('development@aquilaland.id','AquilaLand')
                    ->subject('Reset Password Akun Aquila Land')
                    ->view('emails.resetPenghuni')
                    ->with([
                        'namaPenghuni' => $this->occupant->nama_kk,
                        'emailPenghuni' => $this->occupant->email,
                        'passwordPenghuni' => 'WelcomeAquila',
                    ]);
    }
}
