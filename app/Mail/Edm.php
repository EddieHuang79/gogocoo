<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\logic\Edm_logic;
use URL;

class Edm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $site = URL::to('/');

        $edm = Edm_logic::get_edm_to_send();

        Edm_logic::change_status( array($edm->id), 3 );

        $data = ['edm' => $edm, 'site' => $site];

        $subject = $edm->subject;

        return $this->from('gogocoo@gogocoo.com')
                    ->subject( $subject )
                    ->view('edm.send_template', $data);

    }
}
