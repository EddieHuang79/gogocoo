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

        $data = ['edm' => $edm, 'site' => $site];

        return $this->from('gogocoo@gogocoo.com')
                    ->subject( $edm->subject )
                    ->view('edm.send_template', $data);

    }
}
