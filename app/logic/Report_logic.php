<?php

namespace App\logic;

use Illuminate\Support\Facades\Session;
use App\model\Purchase;
use App\model\Order;
use App\logic\Redis_tool;

class Report_logic extends Basetool
{

	public static function this_week_order_cnt()
	{

		$_this = new self();

		$shop_id = Session::get( 'Store' );

		$search_deadline = mktime(date("H")+1,0,0,date("m"),date("d"),date("Y"));

		$Redis_key = $shop_id."_".$search_deadline;

		$cnt = Redis_tool::get_week_order_cnt( $Redis_key );

		if ( is_null($cnt) || !is_numeric($cnt) ) 
		{

			$week_date = $_this->this_week_date();

			$cnt = Order::get_order_cnt( $week_date, $shop_id, $status = array(1,2) );

			$cnt = $cnt->cnt;
	
			Redis_tool::set_week_order_cnt( $Redis_key, $cnt );

		}

		$cnt = number_format($cnt);

		return $cnt;

	}

	public static function week_cancel_order_cnt()
	{

		$_this = new self();

		$shop_id = Session::get( 'Store' );

		$search_deadline = mktime(date("H")+1,10,0,date("m"),date("d"),date("Y"));

		$Redis_key = $shop_id."_".$search_deadline;

		$cnt = Redis_tool::get_week_cancel_order_cnt( $Redis_key );

		if ( is_null($cnt) || !is_numeric($cnt) ) 
		{

			$week_date = $_this->this_week_date();

			$cnt = Order::get_order_cnt( $week_date, $shop_id, $status = array(3) );

			$cnt = $cnt->cnt;
	
			Redis_tool::set_week_cancel_order_cnt( $Redis_key, $cnt );

		}

		$cnt = number_format($cnt);

		return $cnt;

	}

	public static function today_in_ws_cnt()
	{

		$_this = new self();

		$shop_id = Session::get( 'Store' );

		$search_deadline = mktime(date("H")+1,20,0,date("m"),date("d"),date("Y"));

		$Redis_key = $shop_id."_".$search_deadline;

		$cnt = Redis_tool::get_today_in_ws_cnt( $Redis_key );

		if ( is_null($cnt) || !is_numeric($cnt) ) 
		{

			$date = $_this->today_date();

			$cnt = Purchase::get_purchase_sum( $date, $shop_id, $status = array(2) );

			$cnt = $cnt->cnt;
	
			Redis_tool::set_today_in_ws_cnt( $Redis_key, $cnt );

		}

		$cnt = number_format($cnt);

		return $cnt;

	}

	public static function today_out_ws_cnt()
	{

		$_this = new self();

		$shop_id = Session::get( 'Store' );

		$search_deadline = mktime(date("H")+1,30,0,date("m"),date("d"),date("Y"));

		$Redis_key = $shop_id."_".$search_deadline;

		$cnt = Redis_tool::get_today_out_ws_cnt( $Redis_key );

		if ( is_null($cnt) || !is_numeric($cnt) ) 
		{

			$date = $_this->today_date();

			$cnt = Order::get_order_sum( $date, $shop_id, $status = array(1,2) );

			$cnt = $cnt->cnt;
	
			Redis_tool::set_today_out_ws_cnt( $Redis_key, $cnt );

		}

		$cnt = number_format($cnt);

		return $cnt;

	}

	public static function month_order_view()
	{

		$_this = new self();

		$data = array();

		$cnt = array();

		$shop_id = Session::get( 'Store' );

		$search_deadline = mktime(date("H")+1,40,0,date("m"),date("d"),date("Y"));

		$Redis_key = $shop_id."_".$search_deadline;

		$data = Redis_tool::get_month_order_view( $Redis_key );

		if ( is_null($data) || empty($data) ) 
		{

			$date = $_this->month_of_year_date();

			foreach ($date as $index => $row) 
			{

				$tmp = Order::get_order_sum( $row, $shop_id, $status = array(1,2) );
				
				$cnt[] = (int)$tmp->cnt;

			}

			$data = Redis_tool::set_month_order_view( $Redis_key, $cnt );

		}

		return $data;

	}

	public static function year_stock_view()
	{

		$_this = new self();

		$data = array();

		$cnt = array();

		$shop_id = Session::get( 'Store' );

		$search_deadline = mktime(date("H")+1,50,0,date("m"),date("d"),date("Y"));

		$Redis_key = $shop_id."_".$search_deadline;

		$data = Redis_tool::get_year_stock_view( $Redis_key );

		if ( is_null($data) || empty($data) ) 
		{

			$date = $_this->month_of_year_date();

			// 入庫

			foreach ($date as $index => $row) 
			{

				$tmp = Purchase::get_purchase_sum( $row, $shop_id, $status = array(2) );
				
				$cnt["in"][] = $tmp->cnt * -1;

			}

			// 出庫

			foreach ($date as $index => $row) 
			{

				$tmp = Order::get_order_sum( $row, $shop_id, $status = array(1,2) );
				
				$cnt["out"][] = $tmp->cnt;

			}

			$data = Redis_tool::set_year_stock_view( $Redis_key, $cnt );

		}

		return $data;

	}

	public static function year_product_top5()
	{

		$_this = new self();

		$data = array();

		$cnt = array();

		$shop_id = Session::get( 'Store' );

		$search_deadline = mktime(date("H")+1,5,0,date("m"),date("d"),date("Y"));

		$Redis_key = $shop_id."_".$search_deadline;

		$data = Redis_tool::get_year_product_top5( $Redis_key );

		// if ( is_null($data) || empty($data) ) 
		// {

			$date = $_this->month_of_year_date();

			// 出庫

			foreach ($date as $index => $row) 
			{

				// 總量

				$tmp = Order::get_order_sum( $row, $shop_id, $status = array(1,2) );
				
				$cnt["total"][$index-1] = new \stdClass;

				$cnt["total"][$index-1]->name = date("M", mktime(0,0,0,$index,1,date("Y")));
				$cnt["total"][$index-1]->y = (int)$tmp->cnt;
				$cnt["total"][$index-1]->drilldown = date("M", mktime(0,0,0,$index,1,date("Y")));	

				$tmp = Order::get_hotSell_top5( $row, $shop_id, $status = array(1,2) );

				$cnt["top5"][$index-1] = new \stdClass;
				$cnt["top5"][$index-1]->name = date("M", mktime(0,0,0,$index,1,date("Y")));
				$cnt["top5"][$index-1]->id = date("M", mktime(0,0,0,$index,1,date("Y")));

				$dataArray = array();

				foreach ($tmp as $row1) 
				{
					$dataArray[] =  array(
											$row1->product_name,
											(int)$row1->cnt,
										);

				}

				$cnt["top5"][$index-1]->data = $dataArray;

			}

			$data = Redis_tool::set_year_product_top5( $Redis_key, $cnt );

		// }

		return $data;

	}

	protected function month_of_year_date()
	{

		$result = array();

		$year = date("Y");

		for ($month = 1; $month < 13; $month++) 
		{ 

			$result[$month] = array(
							"start_date" 	=> date("Y-m-01 00:00:00", mktime(0,0,0,$month,1,$year) ),
							"end_date" 		=> date("Y-m-t 23:59:59", mktime(0,0,0,$month+1,0,$year) )
						);

		}

		return $result;

	}

	protected function this_week_date()
	{

		$today = strtotime("now");

		$week = date("w",$today);

		// 禮拜一為第一天，禮拜日為最後一天

		$first_day = date("Y-m-d 00:00:00", mktime(0,0,0,date("m"),date("d",$today) - ((int)$week - 1) ,date("Y")));

		$last_day = date("Y-m-d 23:59:59", mktime(0,0,0,date("m"),date("d",$today) + ( 7 - (int)$week) ,date("Y"))); 

		$result = array(
					"start_date" 	=> $first_day,
					"end_date" 		=> $last_day,
				);

		return $result;

	}

	protected function today_date()
	{

		$result = array(
					"start_date" 	=> date("Y-m-d 00:00:00", time()),
					"end_date" 		=> date("Y-m-d 23:59:59", time()),
				);

		return $result;

	}

}
