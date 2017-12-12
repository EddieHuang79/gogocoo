<?php

namespace App\logic;

use App\model\Edm;
use App\logic\Web_cht;
use Illuminate\Support\Facades\Storage;
use File;
use App\logic\Promo_logic;
use Illuminate\Support\Facades\Session;

class Edm_logic extends Basetool
{

	protected $status = array();

	public function __construct()
	{

		// 文字

		$txt = Web_cht::get_txt();

		$this->status = array(
							1 	=>	$txt["draft"],
							2 	=>	$txt["pending"],
							3 	=>	$txt["edm_send"],
							4 	=>	$txt["cancel"]
						);

	}	

	// 新增格式

	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
				"subject"       => isset($data["subject"]) ? trim($data["subject"]) : "",
				"type"     		=> isset($data["type"]) ? intval($data["type"]) : 1,
				"send_time"     => isset($data["send_time"]) ? date("Y-m-d H:i:00", strtotime($data["send_time"])) : date("Y-m-d H:i:s"),
				"status"        => 1,
				"created_at"    => date("Y-m-d H:i:s"),
				"updated_at"    => date("Y-m-d H:i:s")
			);

		}

		return $result;

	}


	// 修改格式

	public static function update_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
				"subject"       => isset($data["subject"]) ? trim($data["subject"]) : "",
				"type"     		=> isset($data["type"]) ? intval($data["type"]) : 1,
				"send_time"     => isset($data["send_time"]) ? date("Y-m-d H:i:00", strtotime($data["send_time"])) : date("Y-m-d H:i:s"),
				"updated_at"    => date("Y-m-d H:i:s")
			);

		}

		return $result;

	}


	// 新增註冊信格式

	public static function insert_register_mail_format()
	{

		$Login_user = Session::get( 'Login_user' );

		$result = array(
			"subject"       => "註冊完成信件: " . $Login_user["account"],
			"data"       	=> json_encode($Login_user),
			"type"     		=> 1,
			"send_time"     => date("Y-m-d H:i:s"),
			"status"        => 2,
			"created_at"    => date("Y-m-d H:i:s"),
			"updated_at"    => date("Y-m-d H:i:s")
		);
			
		return $result;

	}


	// 新增召回信格式

	public static function insert_notice_mail_format( $data )
	{

		$result = array();

		if ( !empty($data) && is_object($data) ) 
		{

			$user = array(
						"account" 		=> $data->account,
						"real_name" 	=> $data->real_name
					);

			$result = array(
				"subject"       => "倒數三天召回信件: " . $user["account"],
				"data"       	=> json_encode($user),
				"type"     		=> 2,
				"send_time"     => date("Y-m-d H:i:s"),
				"status"        => 2,
				"created_at"    => date("Y-m-d H:i:s"),
				"updated_at"    => date("Y-m-d H:i:s")
			);
				
			return $result;

		}

	}


	// 新增首購禮格式

	public static function insert_FirstBuyGift_mail_format( $data )
	{

		$result = array();

		if ( !empty($data) && is_object($data) ) 
		{

			$user = array(
						"account" 		=> $data->account,
						"real_name" 	=> $data->real_name
					);

			$result = array(
				"subject"       => "首購禮贈送信件: " . $user["account"],
				"data"       	=> json_encode($user),
				"type"     		=> 3,
				"send_time"     => date("Y-m-d H:i:s"),
				"status"        => 2,
				"created_at"    => date("Y-m-d H:i:s"),
				"updated_at"    => date("Y-m-d H:i:s")
			);
				
			return $result;

		}

	}


	// 新增EDM

	public static function add_edm( $data )
	{

		$result = false;

		if ( !empty($data) ) 
		{

			$result = Edm::add_edm( $data );

		}

		return $result;

	}


	// 修改EDM

	public static function edit_edm( $data, $edm_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($edm_id) && is_int($edm_id) ) 
		{

			Edm::edit_edm( $data, $edm_id );

			$result = true;

		}

		return $result;

	}

	// 列表

	public static function get_edm_list( $data = array() )
	{

		$_this = new self();

		$result = array();

		$status_txt = $_this->status;

		$data = Edm::get_edm_list( $data );

		foreach ($data as &$row) 
		{

			if ( is_object($row) ) 
			{
				
				$row->status_txt = isset($status_txt[$row->status]) ? $status_txt[$row->status] : "" ; 

				$row->has_list = $_this->has_send_list( (int)$row->id ) ; 

				$row->has_list_txt = $row->has_list === false ? "未上傳名單" : "已上傳名單" ; 
			
			}

		}

		$result = $data;

		return $result;	

	}


	// 取得單筆資料

	public static function get_single_edm( $edm_id )
	{

		$_this = new self;

		$result = new \stdClass;

		if ( !empty($edm_id) && is_int($edm_id) ) 
		{

			$result = Edm::get_single_edm( $edm_id );

		}

		return $result;

	}	


	// 複製EDM

	public static function clone_edm( $data )
	{

		$result = array();

		if ( !empty($data) && is_object($data) && isset($data->subject) ) 
		{

			$result = array(
							"subject" 		=> $data->subject . "_[clone from id:".$data->id."]",
							"content" 		=> $data->content,
							"type" 			=> $data->type,
							"send_time" 	=> $data->send_time,
							"status" 		=> 1,
							"created_at" 	=> date("Y-m-d H:i:s"),
							"updated_at" 	=> date("Y-m-d H:i:s"),
						);

		}

		return $result;

	}


	// 改變狀態

	public static function change_status( $id, $status )
	{

		$result = !empty($id) && is_array($id) && !empty($status) && is_int($status) ? Edm::change_status( $id, $status ) : false;

		return $result;

	}


	// 取得下一筆該發送的edm

	public static function get_edm_to_send()
	{

		$result = Edm::get_edm_to_send();

		return $result;

	}	


	// 是否已上傳名單

	protected function has_send_list( $id )
	{

		$result = false;

		if ( !empty($id) && is_int($id) ) 
		{

			$pathToFile = storage_path('app/edm_list/' . $id . '.txt');

			$result = file_exists( $pathToFile );

		}

		return $result;

	}


	// 取得名單

	public static function get_send_list( $id )
	{

		$result = array();

		if ( !empty($id) && is_int($id) ) 
		{

			$data = File::get( storage_path('app/edm_list/' . $id . '.txt') );

			$tmp = explode("\n", $data);

			foreach ($tmp as $row) 
			{

				$mail_data = explode(",", $row);
				
				$result[] = array(

								"account" 	=> isset($mail_data[0]) ? $mail_data[0] : "",
								"real_name" => isset($mail_data[1]) ? $mail_data[1] : "",

							);

			}

		}

		return $result;

	}


	// 新增關聯格式

	public static function add_edm_rel_format( $data, $edm_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			foreach ($data as $mall_shop_id) 
			{

				$result[] = array(
								"edm_id"        			=> $edm_id,
								"mall_shop_id"     			=> $mall_shop_id
							);

			}

		}

		return $result;

	}


	// 新增EDM

	public static function add_edm_rel( $data )
	{

		$result = false;

		if ( !empty($data) ) 
		{

			$result = Edm::add_edm_rel( $data );

		}

		return $result;

	}


	// 取得關聯

	public static function get_edm_rel( $edm_id )
	{

		$_this = new self;

		$result = new \stdClass;

		if ( !empty($edm_id) && is_int($edm_id) ) 
		{

			$result = Edm::get_edm_rel( $edm_id );

		}

		return $result;

	}	


	// 刪除EDM

	public static function del_edm_rel( $edm_id )
	{

		$result = false;

		if ( !empty($edm_id) && is_int($edm_id) ) 
		{

			$result = Edm::del_edm_rel( $edm_id );

		}

		return $result;

	}


	// 取得關聯商品

	public static function get_edm_rel_product( $edm_id )
	{

		$_this = new self;

		$result = new \stdClass;

		if ( !empty($edm_id) && is_int($edm_id) ) 
		{

			$data = Edm::get_edm_rel_product( $edm_id );

			foreach ($data as &$row) 
			{
				
				$row->promo = Promo_logic::get_active_promo_price( $row->mall_shop_id );

			}

			$result = $data;

		}

		return $result;

	}	

}