<?php

namespace App\logic;

use App\model\Msg;
use App\logic\Redis_tool;
use Illuminate\Support\Facades\Session;
use App\logic\Role_logic;
use App\logic\Web_cht;

class Msg_logic extends Basetool
{

	protected $role_data;
	protected $msg_type;
	protected $show_type;
	protected $txt;

	public function __construct()
	{

		// 文字
		$this->txt = Web_cht::get_txt();

		// 角色
		$this->role_data = Role_logic::get_role_array();
	
		// 訊息種類
		$this->msg_type = array(
								1 => $this->txt["normal_msg"],
								2 => $this->txt["system_msg"]
							);

		// 顯示管道
		$this->show_type = array(
								1 => $this->txt["msg_icon"],
								2 => $this->txt["popup"],
								3 => $this->txt["mail"]
							);

	}

	// 新增格式
	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array(
		            "subject"          	=> isset($data["subject"]) ? $_this->strFilter($data["subject"]) : "",
		            "content"          	=> isset($data["content"]) ? $_this->strFilter($data["content"]) : "",
		            "role_id"          	=> isset($data["role_id"]) ? intval($data["role_id"]) : "",
		            "show_type"        	=> isset($data["show_type"]) ? intval($data["show_type"]) : "",
		            "msg_type"         	=> isset($data["msg_type"]) ? intval($data["msg_type"]) : "",
		            "public"        	=> isset($data["public"]) ? intval($data["public"]) : 0,
		            "start_date"        => isset($data["start_date"]) ? $_this->strFilter($data["start_date"]) . " 00:00:00" : "",
		            "end_date"          => isset($data["end_date"]) ? $_this->strFilter($data["end_date"]) . " 23:59:59" : "",
		            "created_at"    	=> date("Y-m-d H:i:s"),
		            "updated_at"    	=> date("Y-m-d H:i:s")
		         );

		return $result;

	}

	// 更新格式
	public static function update_format( $data )
	{

		$_this = new self();

		$result = array(
		            "subject"          	=> isset($data["subject"]) ? $_this->strFilter($data["subject"]) : "",
		            "content"          	=> isset($data["content"]) ? $_this->strFilter($data["content"]) : "",
		            "role_id"          	=> isset($data["role_id"]) ? intval($data["role_id"]) : "",
		            "show_type"        	=> isset($data["show_type"]) ? intval($data["show_type"]) : "",
		            "msg_type"         	=> isset($data["msg_type"]) ? intval($data["msg_type"]) : "",
		            "public"        	=> isset($data["public"]) ? intval($data["public"]) : 0,
		            "start_date"        => isset($data["start_date"]) ? $_this->strFilter($data["start_date"]) . " 00:00:00" : "",
		            "end_date"          => isset($data["end_date"]) ? $_this->strFilter($data["end_date"]) . " 23:59:59" : "",
		            "updated_at"    	=> date("Y-m-d H:i:s")
		         );

		return $result;

	}


	// 取得訊息
	public static function get_msg( $role_id = array(), $show_type = array(), $msg_type = array() )
	{

		$Login_user = Session::get('Login_user');

		return Msg::get_msg( $role_id, $show_type, $msg_type, array($Login_user["user_id"]) );

	}	

	// 轉換時間顯示
	public static function time_format( $data )
	{

		foreach ($data as &$row) 
		{

			$diff = time() - strtotime($row->updated_at);
			$show_data = ($diff / ( 60 * 60 * 24 )) > 0 ? floor($diff / ( 60 * 60 * 24 * 7 )) . " weeks" : "";
			$show_data = ($diff / ( 60 * 60 * 24 )) > 0 ? floor($diff / ( 60 * 60 * 24 )) . " days" : $show_data;
			$show_data = empty($show_data) && ($diff / ( 60 * 60 )) > 0 ? floor($diff / ( 60 * 60 )) . " hours" : $show_data ;
			$show_data = empty($show_data) && $diff / 60 > 0 ? floor($diff / 60) . " mins" : $show_data ;
			$show_data = empty($show_data) ? $diff . " secs" : $show_data ;

			$row->updated_at = $show_data;

		}

		return $data;

	}		

	// 顯示未讀的第一筆 
	public static function show_popup_msg( $data )
	{

		$Login_user = Session::get('Login_user');

		$read_msg = Redis_tool::get_read_msg( $Login_user["user_id"] );

		$result = array();

		$msg_id = 0;

		foreach ($data as $row) 
		{
			if (!in_array($row->id, $read_msg)) 
			{
				$result["subject"] = $row->subject;
				$result["content"] = $row->content;
				$msg_id = $row->id;
				break;
			}
		}

		// add redis
		if (!empty($msg_id)) 
		{
			Redis_tool::set_read_msg( $Login_user["user_id"], $msg_id );
		}

		return $result;

	}	

	// 訊息列表
	public static function get_msg_list( $data )
	{

		$_this = new self();

		$txt = $_this->txt;
		$show_type = $_this->show_type;
		$msg_type = $_this->msg_type;
		$role_data = $_this->role_data;

		$option = array(
						"type" => isset($data['type']) ? intval($data['type']) : 0 
					);

		// 訊息
		$msg = Msg::get_all_msg( $option );


		foreach ($msg as &$row) 
		{

			$row->role_id = isset($role_data[$row->role_id]) ? $role_data[$row->role_id] : $txt["all_role"] ; 
			$row->public_txt = $row->public > 0 ? $txt["yes"] : $txt["no"] ;
			$row->show_type = isset($show_type[$row->show_type]) ? $show_type[$row->show_type] : "" ;
			$row->msg_type = isset($msg_type[$row->msg_type]) ? $msg_type[$row->msg_type] : "" ;

		}

		return $msg;

	}

	// 取得選項
	public static function get_msg_option()
	{

		$_this = new self();

		$result = array(
						"msg_type" 	=> $_this->msg_type,
						"role_data" => $_this->role_data,
						"show_type" => $_this->show_type
					);

		return $result;

	}

	// 取得訊息
	public static function get_single_msg( $msg_id )
	{

		$data = Msg::get_single_msg( $msg_id );

		$data->start_date = date("Y-m-d", strtotime($data->start_date));
		$data->end_date = date("Y-m-d", strtotime($data->end_date));

		return $data;

	}	

	// 修改訊息
	public static function edit_msg( $data, $msg_id )
	{

		return Msg::edit_msg( $data, $msg_id );

	}

	// 新增訊息
	public static function add_msg( $data )
	{

		return Msg::add_msg( $data );

	}

	// 複製訊息
	public static function clone_msg( $data )
	{

		$result = array(
						"subject" 		=> $data->subject . "_[clone from id:".$data->id."]",
						"content" 		=> $data->content,
						"role_id" 		=> $data->role_id,
						"show_type" 	=> $data->show_type,
						"msg_type" 		=> $data->msg_type,
						"public" 		=> 0,
						"start_date" 	=> $data->start_date,
						"end_date" 		=> $data->end_date,
						"created_at" 	=> date("Y-m-d H:i:s"),
						"updated_at" 	=> date("Y-m-d H:i:s"),
					);

		return $result;

	}

	// 新增一般訊息
	public static function add_normal_msg( $subject, $content, $user_id = 0 )
	{

		$data = array(
						"subject" 		=> $subject,
						"content" 		=> $content,
						"role_id" 		=> 0,
						"user_id" 		=> $user_id,
						"show_type" 	=> 2,
						"msg_type" 		=> 1,
						"public" 		=> 1,
						"start_date" 	=> date("Y-m-d H:i:s"),
						"end_date" 		=> date("Y-m-d 23:59:59"),
						"created_at" 	=> date("Y-m-d H:i:s"),
						"updated_at" 	=> date("Y-m-d H:i:s"),
					);

		Msg::add_msg( $data );

	}

	// 新增系統訊息
	public static function add_notice_msg( $subject, $content, $user_id = 0 )
	{

		$data = array(
						"subject" 		=> $subject,
						"content" 		=> $content,
						"role_id" 		=> 0,
						"user_id" 		=> $user_id,
						"show_type" 	=> 2,
						"msg_type" 		=> 2,
						"public" 		=> 1,
						"start_date" 	=> date("Y-m-d H:i:s"),
						"end_date" 		=> date("Y-m-d 23:59:59"),
						"created_at" 	=> date("Y-m-d H:i:s"),
						"updated_at" 	=> date("Y-m-d H:i:s"),
					);

		Msg::add_msg( $data );

	}

}
