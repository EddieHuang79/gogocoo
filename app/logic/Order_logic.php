<?php

namespace App\logic;

use App\model\Order;
use App\logic\Option_logic;
use App\logic\Stock_logic;
use App\logic\Msg_logic;
use Illuminate\Support\Facades\Session;

class Order_logic extends Basetool
{

	protected $status_txt = array(
								1	=>	"未入帳",
								2	=>	"已入帳"
							);

	protected $out_warehouse_category;

	protected $warehouse_txt = array(
									1 => "自家倉庫"
								);


	public function __construct()
	{

		// 文字
		$this->out_warehouse_category = Option_logic::get_out_warehouse_category();

	}


	public static function get_order_extra_column()
	{

		$result = array();

		$data = Order::get_order_extra_column();

		$not_show_on_page = array('pay_status', 'logistics_status', 'pay_time', 'refund_time', 'cancel_time');

		unset($data[0]);
		unset($data[1]);

		foreach ($data as $key => $row) 
		{

			$result[$key]["name"] = $row->Field;

			$result[$key]["show_on_page"] = true;

			// 基本案例

			if ( strpos($row->Type, "varchar") !== false || strpos($row->Type, "date") !== false ) 
			{
				$result[$key]["type"] = "text";
			}

			if ( strpos($row->Type, "int") !== false ) 
			{
				$result[$key]["type"] = "number";
			}

			if ( strpos($row->Type, "text") !== false ) 
			{
				$result[$key]["type"] = "textarea";
			}

			// 特例

			// if ( $row->Field == 'warehouse_id' ) 
			// {
			// 	$result[$key]["type"] = "select";
			// }

			if ( in_array($row->Field, $not_show_on_page) ) 
			{
				$result[$key]["show_on_page"] = false;
			}

		}


		return $result;

	}


	// 新增格式

	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array();

		$shop_id = Session::get( 'Store' );

		$spec_id = isset($data["spec_id"]) ? intval($data["spec_id"]) : 0 ;

		// 商品data

		$product_name = isset($data["product_name"]) ? $_this->strFilter($data["product_name"]) : "" ;

        if ( !empty($product_name) ) 
        {

	        $option = array( array("product_name", "=", $product_name) );

	        $product_data = Product_logic::get_product_data( $option );

	        $product_id = $product_data[0]->product_id;

			// 訂單編號

			$order_number = $_this->get_order_number();

			$number = isset($data["number"]) ? intval($data["number"]) : 0 ;

			$result = array(
			            "shop_id"      			=> isset($shop_id) ? $shop_id : 1,
			            "product_id"			=> $product_id,
			            "spec_id"				=> $spec_id,
			            "order_number"			=> $order_number,
			            "number"       			=> $number,
			            "out_warehouse_date" 	=> isset($data["out_warehouse_date"]) ? date("Y-m-d", strtotime($data["out_warehouse_date"])) : date("Y-m-d"),
			            "category"    			=> isset($data["category"]) ? intval($data["category"]) : 1,
			            "status"    			=> 1,
			            "created_at"    		=> date("Y-m-d H:i:s"),
			            "updated_at"    		=> date("Y-m-d H:i:s")
			         );

        }

		return $result;

	}

	// 新增附屬欄位格式

	public static function insert_extra_format( $data, $order_extra_column, $order_id )
	{

		$_this = new self();

		$result = array();

		$result["order_id"] = $order_id;

		foreach ($order_extra_column as $row_data) 
		{

			$default_value = $row_data['type'] == 'number' ? 0 : "" ;

			$result[$row_data['name']] = isset($data[$row_data['name']]) && !empty($data[$row_data['name']]) ? trim($data[$row_data['name']]) : $default_value ;

		}

		return $result;

	}

	// 更新格式

	public static function update_format( $data )
	{

		$_this = new self();

		$spec_id = isset($data["spec_id"]) ? intval($data["spec_id"]) : 0 ;

		// 商品data

		$product_name = isset($data["product_name"]) ? $_this->strFilter($data["product_name"]) : "" ;

        $option = array( array("product_name", "=", $product_name) );

        $product_data = Product_logic::get_product_data( $option );

        $product_id = $product_data[0]->product_id;

		$result = array(
		            "product_id"      		=> $product_id,
		            "spec_id"      			=> $spec_id,
		            "number"       			=> isset($data["number"]) ? intval($data["number"]) : 0,
		            "out_warehouse_date" 	=> isset($data["out_warehouse_date"]) ? date("Y-m-d", strtotime($data["out_warehouse_date"])) : date("Y-m-d"),
		            "category"    			=> isset($data["category"]) ? intval($data["category"]) : 1,
		            "updated_at"    		=> date("Y-m-d H:i:s")
		         );

		return $result;

	}

	// 附屬資料更新格式

	public static function update_extra_format( $data, $purchase_extra_column )
	{

		$_this = new self();

		$result = array();

		foreach ($purchase_extra_column as $row_data) 
		{

			$default_value = $row_data['type'] == 'number' ? 0 : "" ;

			$result[$row_data['name']] = isset($data[$row_data['name']]) && !empty($data[$row_data['name']]) ? trim($data[$row_data['name']]) : $default_value ;

		}

		return $result;

	}

	public static function edit_order( $data, $order_id )
	{

		Order::edit_order( $data, $order_id );

	}

	public static function edit_order_extra_data( $data, $order_id )
	{

		Order::edit_order_extra_data( $data, $order_id );

	}

	public static function get_order_number( $order_id = 0 )
	{

		$data = Order::get_order_number( $order_id );

		$result = !empty($data) ? $data->order_number + 1 : 1 ;

		return $result;

	}

	public static function add_order( $data )
	{

		return Order::add_order( $data );

	}

	public static function add_extra_order( $data )
	{

		Order::add_extra_order( $data );

	}

	public static function get_order_data( $data = array() )
	{

		$shop_id = Session::get( 'Store' );

		return Order::get_order_data( $data, $shop_id );

	}

	public static function get_order_list( $data, $option_data )
	{

		$_this = new self();

		$status_txt = $_this->status_txt;

		$out_warehouse_category_txt = $_this->out_warehouse_category;

		$result = array();

		foreach ($data as $key1 => $row) 
		{
			foreach ($row as $key2 => $data) 
			{

				$result[$key1][$key2] = $data;

			}

			$category = $row->category;

			$result[$key1]['out_warehouse_category_txt'] = $out_warehouse_category_txt->$category;

			$result[$key1]['status_txt'] = $status_txt[$row->status];

			$result[$key1]['order_number_txt'] = $_this->return_order_number_to_user($row);

			foreach ($option_data as $key2 => $data) 
			{

				$result[$key1][$key2.'_txt'] = isset( $data[$row->$key2] ) ? $data[$row->$key2] : "" ;
			
			}

		}

		return $result;

	}

	public static function get_single_order_data( $id )
	{

		return Order::get_single_order_data( $id );

	}

	public static function FIFO_get_stock_id( $data )
	{

		$_this = new self();

		$result = array();

		$product_id = array();
		
		$spec_id = array();

		foreach ($data as $row) 
		{

			$product_id[] = $row->product_id;

			$spec_id[] = $row->spec_id;

		}

		$product_id = array_filter($product_id, "intval");

		$spec_id = array_filter($spec_id, "intval");

		$stock_data = Stock_logic::FIFO_get_stock_id( $product_id, $spec_id );

    	$stock_data = $_this->change_format($stock_data);

    	// 爛招 丟session

    	Session::forget('stock_data');

    	Session::put('stock_data', $stock_data);

		foreach ($data as $row) 
		{

			$stock_key = $_this->made_key(array($row->product_id, $row->spec_id));

			$result[$row->id] = $_this->FIFO_stock( $stock_key, $row->number );

		}

		return $result;

	}

	public static function change_format( $data )
	{

		$_this = new self();

		$result = array();

		foreach ($data as $row) 
		{

			$stock_key =  $_this->made_key( array( $row->product_id, $row->spec_id ) );

			$result[$stock_key][$row->in_warehouse_number] = array(
																	$row->id => $row->stock
																);

		}

		return $result;

	}

	public static function FIFO_stock( $stock_key, $request_number )
	{

		$result = array();

		$stock_result = array();

		$data = Session::get('stock_data');

		if ( !empty($data) ) 
		{

			$stock_data = $data;

			foreach ($data[$stock_key] as &$row) 
			{

				foreach ($row as $stock_id => &$stock) 
				{

					// 判斷結果

					$tmp = $stock - $request_number;
					
					if ( $tmp >= 0 ) 
					{
						$stock_result[] = array(
												$stock_id => $request_number
											);

						$request_number = 0;

						// 更新陣列數

						$stock = $tmp > 0 ? $tmp : 0 ;

						break 2;
					}
					else
					{

						$request_number = abs($tmp) ; 

						$stock_result[] = array(
												$stock_id => $stock
											);

						// 更新陣列數

						$stock = $tmp > 0 ? $tmp : 0 ;

					}

				}

			}

			// 分配成功，更新陣列; 分配失敗，還原陣列

			$request_number > 0 ? Session::put('stock_data', $stock_data) : Session::put('stock_data', $data) ;

		}
		
		$result = array(
						
						"result" 	=> $request_number > 0 ? false : true,

						"data" 		=> $request_number > 0 ? array() : $stock_result
					
					);

		return $result;

	}

	public static function get_order_data_for_verify( $id )
	{

		return Order::get_order_data_for_verify( $id );

	}

	public static function change_status( $id )
	{

		return Order::change_status( $id );

	}

	public static function order_stock_record_format( $data, $order_id )
	{

		$_this = new self();

		$result = array();

		foreach ($data as $row) 
		{

			foreach ($row as $stock_id => $cost_stock) 
			{

				$result[] = array(
									"order_id" 		=> $order_id,
									"stock_id" 		=> $stock_id,
									"cost_number" 	=> $cost_stock,
									"created_at" 	=> date("Y-m-d H:i:s"),
									"updated_at" 	=> date("Y-m-d H:i:s"),
								);
			}

		}

		return $result;

	}

	public static function add_order_stock_record( $data )
	{

		return Order::add_order_stock_record( $data );

	}

	public static function return_order_number_to_user( $order_data )
	{

		$result = date("Ymd", strtotime($order_data->created_at)) . str_pad($order_data->order_number, 8, "0", STR_PAD_LEFT);

		return $result;

	}

	public static function order_verify( $order_id )
	{

		$_this = new self();

        // filter

        $order_id_array = array_filter($order_id, "intval");

        // 取得訂單資料 預期回傳 id,product_id,spec_id,number

        $order_data = $_this->get_order_data_for_verify( $order_id_array ); 

        $FIFO_result = $_this->FIFO_get_stock_id( $order_data ); 

        $Login_user = Session::get("Login_user");

        $error_data = array();

        foreach ($FIFO_result as $order_id => $data) 
        {

        	if ( $data['result'] === true ) 
        	{

    			// 更改狀態

    			$_this->change_status( $order_id );

    			// 紀錄扣庫

    			$order_stock_record = $_this->order_stock_record_format( $data['data'], $order_id );

    			$_this->add_order_stock_record( $order_stock_record );

    			// 扣除庫存

    			Stock_logic::cost_stock( $order_stock_record );

        	}
        	else
        	{

        		$fail_data = $_this->get_single_order_data($order_id);

        		$fail_data = $fail_data[0];

        		$order_number = $_this->return_order_number_to_user($fail_data);

        		$error_data[] = $order_number;

        	}

        }

		$subject = !empty($error_data) ? "庫存不足" : "入帳成功" ;

		$content = !empty($error_data) ? "庫存不足，無法入帳！訂單編號: ". implode(",", $error_data) : $subject ;

        // Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

	}

	public static function order_upload_sample_output( $column_header )
	{

		$_this = new self();

		$result = array();

		$column = array();

		$shop_id = Session::get( 'Store' );	

		$data = Order::order_upload_sample_output( $shop_id );

		foreach ($column_header as $row) 
		{

			$column[] = $row['name'];

		}

		if ( !empty($data) ) 
		{

			foreach ($data as $key => $value) 
			{

				if ( in_array($key, $column) ) 
				{

					$result[$key] = $value;

				}

			}

		}

		return $result;

	}

	public static function get_order_cnt( $week_date, $shop_id, $status )
	{


		return Order::get_order_cnt( $week_date, $shop_id, $status );

	}

	public static function get_order_sum( $week_date, $shop_id, $status )
	{


		return Order::get_order_sum( $week_date, $shop_id, $status );

	}

	public static function get_hotSell_top5( $week_date, $shop_id, $status )
	{


		return Order::get_hotSell_top5( $week_date, $shop_id, $status );

	}

}
