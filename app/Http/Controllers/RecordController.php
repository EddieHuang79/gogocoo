<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Redis_tool;

class RecordController extends Controller
{
	
	public function index()
	{

		// search bar setting

		$search_tool = array(1);

		Redis_tool::set_search_tool( $search_tool );

		// get variable setting

		$date = isset($_GET["date"]) ? $_GET["date"] : date("Y-m-d");

		$show_error = array();

		$log_file = "../storage/logs/laravel-".$date.".log";

		if ( file_exists($log_file) ) 
		{

			$get_log_data = file_get_contents($log_file);

			$ori_log_array = explode("\n", $get_log_data);

			foreach ($ori_log_array as $row) 
			{

				if (preg_match("/^\[".$date."/", $row)) 
				{
					$show_error[] = $row;
				}
			
			}

		}


		$assign_page = "record/record_list";

        $data = compact('assign_page', 'show_error', 'search_tool');

        return view('webbase/content', $data);		

	}

}