<?php

namespace App\logic;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class Record_logic extends Basetool
{

	// 寫log

    public static function write_log( $action, $content )
    {

        $result = false;

        if ( !empty($action) && !empty($content) ) 
        {

            $path = "Syslog";

            Storage::makeDirectory($path);

            $filename = $path . "/sys_log_".date("Ymd").".txt";

            $header = "[".date("Y-m-d H:i:s")."][{$action}]$$$";

            $ori_content = Storage::exists( $filename ) ? Storage::get( $filename ) : "" ;

            $new_content = $ori_content . $header . $content . "\n" ;

            Storage::put( $filename, $new_content);

            $result = true;

        }

        return $result;

    }

	// 取log

    public static function get_log()
    {

    	$result = array();

    	$path = "Syslog";

        $filename = $path . "/sys_log_".date("Ymd").".txt";

        $content = Storage::exists( $filename ) ? Storage::get( $filename ) : "" ;

        if (!empty($content)) 
        {

	        $tmp = explode("\n", $content);

	        foreach ($tmp as $row) 
	        {

	        	$data = explode("$$$", $row);

	        	if (!empty($data)) 
	        	{
					$result[] = array(
									"header" 	=> isset($data[0]) ? $data[0] : "",
									"content" 	=> isset($data[1]) ? $data[1] : ""
								);
	        	}

	        }

        }

        return $result;

    }

}
