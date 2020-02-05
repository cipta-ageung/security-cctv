<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\User;

class ActivationEmailAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('development@aquilaland.id','AquilaLand')
                    ->subject('Login Akun Aquila Land')
                    ->view('emails.registerAdmin')
                    ->with([
                        'namaAdmin' => $this->user->name,
                        'hpAdmin' => $this->user->no_hp,
                        'emailAdmin' => $this->user->email,
                        'passwordAdmin' => 'WelcomeAquila',
                    ]);
    }
}
