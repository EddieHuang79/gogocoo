<?php

namespace App\logic;

use App\model\Edm;
use App\logic\Web_cht;
use Illuminate\Support\Facades\Storage;
use File;

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
				"content"   	=> isset($data["content"]) ? $_this->strFilter($data["content"]) : "",
				"send_time"     => isset($data["send_time"]) ? date("Y-m-d H:i:00", strtotime($data["send_time"])) : date("Y-m-d H:i:s"),
				"status"        => 1,
				"created_at"    => date("Y-m-d H:i:s"),
				"updated_at"    => date("Y-m-d H:i:s")
			);

			if ( isset($data["edm_image"]) && !empty($data["edm_image"]) ) 
			{

				$result["content"] = $data["edm_image"];

			}

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

			if ( isset($data["edm_image"]) && !empty($data["edm_image"]) ) 
			{

				$result["content"] = $data["edm_image"];

			}

			if ( isset($data["type"]) && $data["type"] == 1 ) 
			{

				$result["content"] = isset($data["content"]) ? $_this->strFilter($data["content"]) : "";
			
			}

		}

		return $result;

	}


	// 內容處理

	public static function content_handle( $data = array() )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) && isset( $data["content"] ) ) 
		{

			switch ($data["type"]) 
			{

				// 文字EDM

				case 1:

					$data["content"] = strpos($data["content"], "edm_image") === false ? $data["content"] : "" ;

					break;
				
				// image

				case 2:

					$data["content"] = strpos($data["content"], "edm_image") !== false ? $data["content"] : "" ;

					break;
			
			}

		}

		$result = $data;

		return $result;

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

			$data = Edm::get_single_edm( $edm_id );

			$result = $data;

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

		$_this = new self;

		$result = array();

		$data = Edm::get_edm_to_send();

		if ( !empty($data) && is_object($data) ) 
		{

			$result = $data;

			$result->edm_list = $_this->get_send_list( $result->id );

		}

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

	protected function get_send_list( $id )
	{

		$result = array();

		if ( !empty($id) && is_int($id) ) 
		{

			$data = File::get( storage_path('app/edm_list/' . $id . '.txt') );

			$result = explode("\n", $data);

		}

		return $result;

	}

}
