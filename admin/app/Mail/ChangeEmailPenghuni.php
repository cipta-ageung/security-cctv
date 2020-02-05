<?php

namespace App\Mail;

use App\Models\Occupant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeEmailPenghuni extends Mailable
{
    use Queueable, SerializesModels;

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
                    ->subject('Perubahan Email Akun Aquila Land')
                    ->view('emails.changeEmail')
                    ->with([
                        'namaPenghuni' => $this->occupant->nama_kk,
                    ]);
    }
}
