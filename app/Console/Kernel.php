<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\logic\Edm_logic;
use App\Mail\Edm;
use Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // EDM寄送

        $edm = Edm_logic::get_edm_to_send();

        if ( !empty($edm) && is_object($edm) && isset($edm->id) ) 
        {

            $edm_list = array('u9735034@gms.ndhu.edu.tw', 'kivwu0106@gmail.com');

            $schedule->call(function () use ($edm_list) {

                            Mail::to( $edm_list )->send(new Edm());
                
                        })
                    ->cron("* * * * *");

        }

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
