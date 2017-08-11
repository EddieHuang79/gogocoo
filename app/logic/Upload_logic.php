<?php

namespace App\logic;

use App\logic\Web_cht;
use Maatwebsite\Excel\Facades\Excel;
use App\logic\Msg_logic;
use App\logic\Product_logic;
use App\logic\Purchase_logic;
use App\logic\Order_logic;
use Illuminate\Support\Facades\Session;

class Upload_logic extends Basetool
{

	protected $txt;

	public function __construct()
	{

		// 文字
		$this->txt = Web_cht::get_txt();

	}

	// 欄位轉英文

	public static function column_name_to_english( $data )
	{

		$_this = new self();

		$txt = $_this->txt;

		$result = array();

		foreach ($data as $key => $row) 
		{

			$new_key = !empty( $key ) ? array_keys($txt, $key) : array() ;

			$new_key = !empty($new_key) ? $new_key[0] : "" ;

			if (!empty($new_key)) 
			{

				$result[$new_key] = $row;

			}

		}

		return $result;

	}

	// 商品上傳格式

	public static function product_upload_format( $data )
	{

		$_this = new self();

		$txt = $_this->txt;

		$result = array();

		$output_data = array();

		$column_header = array();

		$file_name = date("Ymd")."product_upload_format";

		foreach ($data as $row) 
		{

			$column_header[] = $txt[$row['name']];

		}

		$output_data[] = $column_header;

		$output_data[] = Product_logic::product_upload_sample_output( $data );

		$result = Excel::create($file_name, function($excel) use($output_data,$txt) {
			// Set the title
			$excel->setTitle($txt["product_upload_format"]);
			$excel->setDescription('report file');

			$excel->sheet('sheet1', function($sheet) use($output_data) {
				$sheet->fromArray($output_data, null, 'A1', false, false);
				$sheet->cells('A1:Z1', function($cells) {
					$cells->setBackground('#FFFF00');
				});
			});
		});

		return $result;

	}

	// 商品上傳流程

	public static function product_upload_process( $path )
	{

		$_this = new self();

		$data = Excel::load($path)->toArray();

		$Login_user = Session::get("Login_user");

		$error_data = array();

		$i = 2;

		try 
		{

			if (empty($data)) 
			{

				$error_msg = "資料無法解析，上傳失敗！";

			   throw new \Exception($error_msg);

			}

			$extra_column = Product_logic::get_product_extra_column();

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				$main_data = Product_logic::insert_main_format( $row );

				if ( !empty($main_data["product_name"]) ) 
				{

					$product_id = Product_logic::add_product( $main_data );

					$extra_data = Product_logic::insert_extra_format( $row, $extra_column, $product_id );

					Product_logic::add_extra_data( $extra_data );

				}
				else
				{
					
					// 錯誤處理

					$error_data[] = $i;

				}

			}

			if (!empty($error_data)) 
			{

				$error_row = implode(",", $error_data);

				$error_msg = "第{$error_row}列資料無法解析，上傳失敗！其餘列數成功！";

			  	throw new \Exception($error_msg);

			}

			$subject = $content = "商品上傳成功！";

			Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		} 
		catch (\Exception $e) 
		{
			
    		$subject = "商品上傳失敗";

    		$content = "商品上傳失敗！失敗原因: ". $e->getMessage();

    		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		}

	}

	// 商品規格上傳格式

	public static function product_spec_upload_format( $data )
	{

		$_this = new self();

		$txt = $_this->txt;

		$result = array();

		$output_data = array();

		$column_header = array();

		$file_name = date("Ymd")."product_spec_upload_format";

		foreach ($data as $row) 
		{

			$column_header[] = $txt[$row['name']];

		}

		$output_data[] = $column_header;

		$data =  Product_logic::spec_upload_sample_output( $data );

		foreach ($data as $row) 
		{

			$output_data[] = $row;

		}

		$result = Excel::create($file_name, function($excel) use($output_data,$txt) {
			// Set the title
			$excel->setTitle($txt["product_spec_upload_format"]);
			$excel->setDescription('report file');

			$excel->sheet('sheet1', function($sheet) use($output_data) {
				$sheet->fromArray($output_data, null, 'A1', false, false);
				$sheet->cells('A1:Z1', function($cells) {
					$cells->setBackground('#FFFF00');
				});
			});
		});

		return $result;

	}

	// 商品規格上傳流程

	public static function product_spec_upload_process( $path )
	{

		$_this = new self();

		$data = Excel::load($path)->toArray();

		$Login_user = Session::get("Login_user");

		$product_name = array();

		try 
		{

			if (empty($data)) 
			{

				$error_msg = "資料無法解析，上傳失敗！";

			   throw new \Exception($error_msg);

			}

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				$product_name[] = $row->product_name;

			}

			

		} 
		catch (\Exception $e) 
		{
			
    		$subject = "商品上傳失敗";

    		$content = "商品上傳失敗！失敗原因: ". $e->getMessage();

    		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		}

		$subject = $content = "商品上傳成功！";

		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );


	}

	// 進貨上傳格式

	public static function purchase_upload_format( $data )
	{

		$_this = new self();

		$txt = $_this->txt;

		$result = array();

		$output_data = array();

		$column_header = array();

		$file_name = date("Ymd")."purchase_upload_format";

		foreach ($data as $row) 
		{

			$column_header[] = $txt[$row['name']];

		}

		$output_data[] = $column_header;

		$output_data[] = Purchase_logic::purchase_upload_sample_output( $data );

		$result = Excel::create($file_name, function($excel) use($output_data,$txt) {
			// Set the title
			$excel->setTitle($txt["product_upload_format"]);
			$excel->setDescription('report file');

			$excel->sheet('sheet1', function($sheet) use($output_data) {
				$sheet->fromArray($output_data, null, 'A1', false, false);
				$sheet->cells('A1:Z1', function($cells) {
					$cells->setBackground('#FFFF00');
				});
			});
		});

		return $result;

	}

	// 進貨上傳流程

	public static function purchase_upload_process( $path )
	{

		$_this = new self();

		$data = Excel::load($path)->toArray();

		$Login_user = Session::get("Login_user");

		$error_data = array();

		$i = 2 ;

		try 
		{

			if (empty($data)) 
			{

				$error_msg = "資料無法解析，上傳失敗！";

			   throw new \Exception($error_msg);

			}

			$extra_column = Purchase_logic::get_purchase_extra_column();

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				$main_data = Purchase_logic::insert_format( $row );

				if ( !empty($main_data['product_id']) ) 
				{

					$purchase_id = Purchase_logic::add_purchase( $main_data );

					$extra_data = Purchase_logic::insert_extra_format( $row, $extra_column, $purchase_id );

					Purchase_logic::add_extra_purchase( $extra_data );

				}
				else
				{
					
					// 錯誤處理

					$error_data[] = $i;

				}

				$i++;

			}


			if (!empty($error_data)) 
			{

				$error_row = implode(",", $error_data);

				$error_msg = "第{$error_row}列資料無法解析，上傳失敗！其餘列數成功！";

			  	throw new \Exception($error_msg);

			}

			$subject = $content = "進貨上傳成功！";

			Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		} 
		catch (\Exception $e) 
		{
			
    		$subject = "進貨上傳失敗";

    		$content = "進貨上傳失敗！失敗原因: ". $e->getMessage();

    		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		}

	}

	// 訂單上傳格式

	public static function order_upload_format( $data )
	{

		$_this = new self();

		$txt = $_this->txt;

		$result = array();

		$output_data = array();

		$column_header = array();

		$file_name = date("Ymd")."order_upload_format";

		foreach ($data as $row) 
		{

			$column_header[] = $txt[$row['name']];

		}

		$output_data[] = $column_header;

		$output_data[] = Order_logic::order_upload_sample_output( $data );

		$result = Excel::create($file_name, function($excel) use($output_data,$txt) {
			// Set the title
			$excel->setTitle($txt["product_upload_format"]);
			$excel->setDescription('report file');

			$excel->sheet('sheet1', function($sheet) use($output_data) {
				$sheet->fromArray($output_data, null, 'A1', false, false);
				$sheet->cells('A1:Z1', function($cells) {
					$cells->setBackground('#FFFF00');
				});
			});
		});

		return $result;

	}

	// 訂單上傳流程

	public static function order_upload_process( $path )
	{

		$_this = new self();

		$data = Excel::load($path)->toArray();

		$Login_user = Session::get("Login_user");

		$error_data = array();

		$i = 2 ;

		try 
		{

			if (empty($data)) 
			{

				$error_msg = "資料無法解析，上傳失敗！";

			   throw new \Exception($error_msg);

			}

			$extra_column = Order_logic::get_order_extra_column();

			foreach ($data as $row) 
			{

				$row = $_this->column_name_to_english( $row );

				$main_data = Order_logic::insert_format( $row );

				if ( !empty($main_data['product_id']) ) 
				{

					$order_id = Order_logic::add_order( $main_data );

					$extra_data = Order_logic::insert_extra_format( $row, $extra_column, $order_id );

					Order_logic::add_extra_order( $extra_data );

				}
				else
				{
					
					// 錯誤處理

					$error_data[] = $i;

				}

				$i++;

			}


			if (!empty($error_data)) 
			{

				$error_row = implode(",", $error_data);

				$error_msg = "第{$error_row}列資料無法解析，上傳失敗！其餘列數成功！";

			  	throw new \Exception($error_msg);

			}

			$subject = $content = "訂單上傳成功！";

			Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		} 
		catch (\Exception $e) 
		{

    		$subject = "訂單上傳失敗";

    		$content = "訂單上傳失敗！失敗原因: ". $e->getMessage();

    		Msg_logic::add_notice_msg( $subject, $content, $Login_user["user_id"] );

		}

	}

}







