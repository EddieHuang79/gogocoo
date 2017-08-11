<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use App\logic\Redis_tool;
use App\logic\Admin_user_logic;

class Service
{

	protected $table = "service";

	protected $user_role = 'user_role_relation';

	protected $role_service = "role_service_relation";

	protected $service_user = "service_user_relation";

	protected $role = 'role';

	protected $user = 'user';

	public static function get_service_list( $option = array(), $pagesize = 15 )
	{

		$_this = new self();

		$data = DB::table($_this->table);
		$data = !empty($option["service_id"]) ? $data->whereIn("id", $option["service_id"]) : $data ;
		$data = !empty($option["service_name"]) ? $data->where("name", "like", "%".$option["service_name"]."%") : $data ;
		$data = !empty($option["status"]) ? $data->where("status", "=", $option["status"]) : $data ;		
		$data = $data->paginate($pagesize);

		return $data;

	}

	public static function get_service( $id = 0 )
	{

		$_this = new self();

		$data = DB::table($_this->table)->find($id);

		return $data;

	}

	public static function get_service_data()
	{

		$_this = new self();

		$data = DB::table($_this->table)->get();

		return $data;

	}

	public static function get_active_service()
	{

		$_this = new self();

		$data = DB::table($_this->table)->where("status", "=", "1")->get();

		return $data;

	}

	public static function add_service( $data )
	{

		$_this = new self;

		$service_id = DB::table($_this->table)->insertGetId($data);

		return $service_id;

	}

	public static function edit_service( $data, $where )
	{

		$_this = new self;

		$result = DB::table($_this->table)->where('id', $where)->update($data);

		return $result;

	}


	public static function get_parents_service()
	{

		$_this = new self();

		$data = DB::table($_this->table)->where("parents_id", "=", "0")->where("status", "=", "1")->get();

		return $data;

	}

    public static function menu_list( $redis_key )
    {	

    	$_this = new self;

    	// $menu_data = Redis_tool::get_menu_data( $redis_key );

    	$Login_user = Session::get('Login_user');

    	// 判斷是否為系統管理者
    	$is_admin = Admin_user_logic::is_admin( $Login_user );

    	// 預設擁有所有權限
    	if ( $is_admin === true ) 
    	{
			$service = DB::table($_this->table)
						->where($_this->table.'.status', '=', 1)
						->orderBy($_this->table.'.sort')
	                    ->get();
    	}
    	else
    	{
    		// 判斷是否為店鋪管理者
    		$is_sub_admin = Admin_user_logic::is_sub_admin( $Login_user );

    		if ( $is_sub_admin === true ) 
    		{
    			
    			// 取得免費服務
				
				$service1 = DB::table($_this->table)
		                    ->leftJoin($_this->role_service, $_this->table.'.id', '=', $_this->role_service.'.service_id')
		                    ->leftJoin($_this->user_role, $_this->user_role.'.role_id', '=', $_this->role_service.'.role_id')
		                    ->select($_this->table.'.*')
		                    ->where($_this->user_role.'.user_id', '=', $Login_user["user_id"])
		                    ->where($_this->table.'.status', '=', 1)
		                    ->where($_this->table.'.public', '=', 4)
		                    ->orderBy($_this->table.'.sort')
		                    ->get();

		        // 取得所有已購買的付費服務

				$service2 = DB::table($_this->table)
		                    ->leftJoin($_this->role_service, $_this->table.'.id', '=', $_this->role_service.'.service_id')
		                    ->leftJoin($_this->user_role, $_this->user_role.'.role_id', '=', $_this->role_service.'.role_id')
		                    ->leftJoin($_this->service_user, $_this->service_user.'.service_id', '=', $_this->table.'.id')
		                    ->select($_this->table.'.*')
		                    ->where($_this->user_role.'.user_id', '=', $Login_user["user_id"])
		                    ->where($_this->table.'.status', '=', 1)
		                    ->where($_this->table.'.public', '=', 1)
		                    ->where($_this->service_user.'.user_id', '=', $Login_user["user_id"])
		                    ->orderBy($_this->table.'.sort')
		                    ->get();

		        $service = $service1->merge($service2);

    		}
    		else
    		{

    			// 取得parents_id

    			$parents_id = Admin_user_logic::get_parents_id( $Login_user );

    			// 取得免費服務

				$service1 = DB::table($_this->table)
		                    ->leftJoin($_this->role_service, $_this->table.'.id', '=', $_this->role_service.'.service_id')
		                    ->leftJoin($_this->user_role, $_this->user_role.'.role_id', '=', $_this->role_service.'.role_id')
		                    ->select($_this->table.'.*')
		                    ->where($_this->user_role.'.user_id', '=', $Login_user["user_id"])
		                    ->where($_this->table.'.status', '=', 1)
		                    ->where($_this->table.'.public', '=', 4)
		                    ->orderBy($_this->table.'.sort')
		                    ->get();  

		        // 取得所有已購買的付費服務

				$service2 = DB::table($_this->table)
		                    ->leftJoin($_this->role_service, $_this->table.'.id', '=', $_this->role_service.'.service_id')
		                    ->leftJoin($_this->user_role, $_this->user_role.'.role_id', '=', $_this->role_service.'.role_id')
		                    ->leftJoin($_this->service_user, $_this->service_user.'.service_id', '=', $_this->table.'.id')
		                    ->select($_this->table.'.*')
		                    ->where($_this->user_role.'.user_id', '=', $Login_user["user_id"])
		                    ->where($_this->table.'.status', '=', 1)
		                    ->where($_this->table.'.public', '=', 1)
		                    ->where($_this->service_user.'.user_id', '=', $parents_id)
		                    ->orderBy($_this->table.'.sort')
		                    ->get();

		        $service = $service1->merge($service2);

    		}

 		
    	}

  
        // empty($menu_data) ? Redis_tool::set_menu_data( $redis_key, $service ) : "" ;

		return $service;

    }

	public static function get_service_id_by_role( $role_id )
	{

		$_this = new self();

		$data = DB::table($_this->role_service)->select("service_id")->where("role_id", "=", $role_id)->get();

		return $data;

	}

	public static function get_service_id_by_url_and_save( $url )
	{

		$_this = new self();

		$data = DB::table($_this->table)->select("id")->where("link", "=", $url)->get();

		return $data;

	}

	public static function get_unpublic_service_data()
	{

		$_this = new self();

		$data = DB::table($_this->table)->select("id", "name", "public")
					->where("status", "=", 1)
					->orderBy($_this->table.'.id', 'desc')
					->paginate(15);

		return $data;

	}

}

