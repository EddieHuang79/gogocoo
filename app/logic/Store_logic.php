<?php

namespace App\logic;

use App\model\Store;
use Illuminate\Support\Facades\Session;
use App\logic\Option_logic;
use App\logic\Admin_user_logic;
use App\logic\Shop_logic;

class Store_logic extends Basetool
{

	protected $user_id;

	public function __construct()
	{

		$Login_user = Session::get('Login_user');

		// user_id

		$this->user_id = $Login_user["user_id"];

	}


	// 計算店舖數

	public static function get_store_cnt()
	{

		$_this = new self();

		// 已有的店鋪數

		$store_cnt = Store::get_store_cnt( $_this->user_id );

		// 已購買的店鋪數

		$buy_store_cnt = Shop_logic::get_count_by_action_key( "create_shop" );

		$buy_store_cnt = array_shift($buy_store_cnt);

		// 免費帳號可以有2間的額度

		$free_store = 2 - $store_cnt ;

		// 剩餘額度

		$left = $free_store + $buy_store_cnt["count"];

		// return

		$result = array(
						"free" 					=> $free_store > 0 ? $free_store : 0,
						"buy" 					=> $buy_store_cnt["count"],
						"buy_spec_data"      	=> $buy_store_cnt["data"],
						"left" 					=> $left
					);


		return $result;

	}	


	// 取得店鋪列表

	public static function get_store_info( $data = array() )
	{

		$store_type_array = array();

		$store_type_name = array();
		
		$user_id = array();

		$result = array();

		$_this = new self();


		// 已有的店鋪數

		$store_info = Store::get_store_info( $_this->user_id );


		if ( $store_info->count() > 0 ) 
		{

			foreach ($store_info as $row) 
			{

				if ( is_object($row) ) 
				{

					$store_type_array[] = $row->store_type;
					
					$user_id[] = $row->id;

				}

			}


			// 取得行業別

			$store_type = Option_logic::get_store_type_name( $store_type_array );

			foreach ($store_type as $row) 
			{

				$store_type_name[$row->id] = $row->key . " - " . $row->value;
			
			}


			// 計算店鋪截止日

			$cnt_deadline = Shop_logic::count_deadline( $user_id, "create_shop" );


			// 寫入名稱

			foreach ($store_info as &$row) 
			{

				if ( is_object($row) ) 
				{

					$row->deadline = Shop_logic::get_deadline( $row, $cnt_deadline );

					$row->store_type_name = isset($store_type_name[$row->store_type]) ? $store_type_name[$row->store_type] : "" ;

				}
			
			}

			if ( !empty($data["store_id"]) ) 
			{

				foreach ($store_info as $row) 
				{
			
					if ( is_object($row) && $row->id == $data["store_id"] ) 
					{
			
						$result = $row;
			
					}
			
				}
			
			}
			else
			{

				$result = $store_info;

			}

		}

		return $result;

	}


	// 新增格式

	public static function insert_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{
		     
			$result = array(
			            "store_name"        => isset($data["StoreName"]) ? $_this->strFilter($data["StoreName"]) : "",
			            "store_code"        => isset($data["store_code"]) && !empty($data["store_code"]) ? strtoupper($_this->strFilter($data["store_code"])) : Admin_user_logic::get_rand_string(),
			            "store_type"     	=> isset($data["store_type_id"]) ? intval($data["store_type_id"]) : "",
			            "user_id"     		=> isset($_this->user_id) ? intval($_this->user_id) : "",
			            "is_free"    		=> isset($data["is_free"]) ? intval($data["is_free"]) : 1,
			            "created_at"    	=> date("Y-m-d H:i:s"),
			            "updated_at"    	=> date("Y-m-d H:i:s")
			         );

		}

		return $result;

	}

	// 更新格式

	public static function update_format( $data )
	{

		$_this = new self();

		$result = array();

		if ( !empty($data) && is_array($data) ) 
		{

			$result = array(
			            "store_name"        => isset($data["StoreName"]) ? $_this->strFilter($data["StoreName"]) : "",
			            "store_code"        => isset($data["StoreCode"]) && !empty($data["StoreCode"]) ? strtoupper($_this->strFilter($data["StoreCode"])) : Admin_user_logic::get_rand_string(),
			            "updated_at"    	=> date("Y-m-d H:i:s")
			         );

		}

		return $result;

	}


	// 修改店鋪

	public static function edit_store( $data, $store_id )
	{

		$result = false;

		if ( !empty($data) && is_array($data) && !empty($store_id) && is_int($store_id) ) 
		{

			$result = Store::edit_store( $data, $store_id );

		}

		return $result;

	}


	// 新增店鋪

	public static function add_store( $data )
	{

		$result = false;

		if ( !empty($data) && is_array($data) ) 
		{

			$result = Store::add_store( $data );

		}

		return $result;

	}


	// 取得店鋪

	public static function get_single_store( $store_id )
	{

		$result = false;

		if ( !empty($store_id) && is_int($store_id) ) 
		{

			$result = Store::get_single_store( $store_id );

		}

		return $result;

	}	


	// 檢查 store user

	public static function check_store_user( $store_id )
	{

		$_this = new self();

		$result = false;

		$store_id = intval($store_id);

		if ( !empty($store_id) && is_int($store_id) ) 
		{

			$data = Store::get_single_store( $store_id );

			$parents_id = Admin_user_logic::get_parents_id( array("user_id" => $_this->user_id ) );

			$parents_id = $parents_id == 0 ? $_this->user_id : $parents_id ;

			$result = $parents_id == $data->user_id ? true : false;

		}

		return $result;

	}	


	// 切換店舖

	public static function change_store( $store_id )
	{

		$_this = new self();

		$result = false;

		$store_id = intval($store_id);

		if ( !empty($store_id) && is_int($store_id) ) 
		{

			Session::put( 'Store', $store_id );

			$result = true;

		}

		return $result;

	}	


	// 擴展店鋪日期

	public static function extend_store_deadline( $user_id, $data )
	{

		$result = false;

		if ( !empty($data) && is_string($data) && !empty($user_id) && is_int($user_id) ) 
		{

			$result = Shop_logic::add_use_record( (int)$user_id, $data, $type = 2 );

		}

	  	return $result;
	     
	}


   	// 以代碼取得店鋪id

	public static function get_store_id_by_code( $store_code )
	{
	    
		$result = !empty($store_code) ? Store::get_store_id_by_code( $store_code ) : false ;

		return $result;

	}	


	// 店鋪驗證

	public static function store_verify( $data )
	{

		$_this = new self();

		$txt = Web_cht::get_txt();

		$result = array();

		$ErrorMsg = array();

		if ( !empty($data) && is_array($data) ) 
		{

			try 
			{

				// 店名

				if ( !$_this->strFilter( $data["StoreName"] ) ) 
				{

					$ErrorMsg[] = $txt["store_name_fail"];

				}


				// 行業別

				if ( intval( $data["store_type_id"] ) < 1 ) 
				{

					$ErrorMsg[] = $txt["store_type_fail"];

				}


				// 商家代號產生

				if ( !empty( $data["StoreCode"] ) && Admin_user_logic::check_store_code_repeat( $data["StoreCode"] ) > 0 )
				{

					$ErrorMsg[] = $txt["Store_code_fail"];

				}


	            if ( !empty($ErrorMsg) ) 
	            {

	               throw new \Exception(json_encode($ErrorMsg));

	            }

			} 
			catch (\Exception $e) 
			{

				$result = json_decode($e->getMessage() ,true);

			}

		}

		return $result;

	}


	// 取得所有關連店鋪

	public static function get_rel_shop_id( $shop_id )
	{

		$result = array();

		if ( !empty($shop_id) && is_int($shop_id) ) 
		{

			$store_data = Store::get_single_store( $shop_id );

			$rel_user_id = Admin_user_logic::get_rel_user_id( $store_data->user_id );

			$data = Store::get_rel_shop_id( $rel_user_id );

			foreach ($data as $row) 
			{

				$result[] = $row->id;
			
			}

		}

		return $result;

	}


   	// 以代碼取得店鋪id

	public static function get_store_parent_type( $store_type_id, $store_type_data )
	{
	    
		$result = 0;

		if ( !empty($store_type_id) && is_int($store_type_id) && !empty($store_type_data) && is_array($store_type_data) ) 
		{

			foreach ($store_type_data as $parents_id => $child) 
			{

				if ( (int)$parents_id === $store_type_id ) 
				{

					$result = $parents_id;

					break;
					
				}
				else
				{

					foreach ($child["data"] as $child_type_id => $value) 
					{

						if ( (int)$child_type_id === $store_type_id ) 
						{

							$result = $parents_id;

							break 2;
							
						}						

					}
				
				}

			}
			
		}

		return $result;

	}	


	// 

	public static function get_store_user_mapping_array()
	{
	    
		$result = array();

		$data = Store::get_all_store();

		foreach ($data as $row) 
		{
			
			$result[$row->id] = $row->user_id;

		}

		return $result;

	}


	// 取得店鋪列表

	public static function get_store_info_logic( $user_id )
	{

		$_this = new self();

		$result = array();

		if ( !empty($user_id) && is_int($user_id) ) 
		{
			
			$result = Store::get_store_info( $user_id );
			
		}

		return $result;

	}


    // 財產列表資料

    public static function store_list_data_bind( $OriData )
    {

      $_this = new self();

      $txt = Web_cht::get_txt();

      $result = array(
                      "title" => array(
                              $txt['store_name'],
                              $txt['store_type'],
                              $txt['store_code'],
                              $txt['create_time'],
                              $txt['deadline'],
                              $txt['action']
                            ),
                      "data" => array()
                  );

      $txt = Web_cht::get_txt();

      if ( !empty($OriData) && $OriData->isNotEmpty() ) 
      {

        foreach ($OriData as $row) 
        {
    
			$data = array(
				"data" => array(
				        "store_name"             => $row->store_name,
				        "store_type"             => $row->store_type_name ,
				        "store_code"             => $row->store_code,
				        "create_time"            => $row->created_at,
				        "deadline"            	 => $row->deadline
				      ),
				"Editlink" 		=> "/store/" . $row->id . "/edit?",
				"ExtendBtn" 	=> true,
				"id" 			=> $row->id
			  );

			$result["data"][] = $data;
        
        }


      }

      return $result;

    }


	// 取得輸入邏輯陣列

	public static function get_store_input_template_array()
	{

		$_this = new self();

		$txt = Web_cht::get_txt();


		$store_type_array = array();

        $store_type = Option_logic::get_store_data();

        foreach ($store_type as $index => $row) 
        {

        	$store_type_array[$index] = $row["name"];

        }

        // 檢查店鋪創立狀況

        $store_status = Store_logic::get_store_cnt();

        $buy_spec_data = array();

        if ( !empty($store_status["buy_spec_data"]) ) 
        {

            foreach ($store_status["buy_spec_data"] as $index => $spec_data) 
            {

                $buy_spec_data[$index] = $spec_data.$txt["buy_date_spec_desc"].date("Y-m-d", strtotime("+".$spec_data." days"));

            }

        }

        $deadline = !empty($store_status['free']) ? date("Y-m-d", strtotime("+30 days")) : $buy_spec_data ;
        
        // 第一間店舖的資訊

        $store_info = Store_logic::get_store_info();

        $store_info = isset($store_info[0]) ? $store_info[0] : new \stdClass() ;


		$htmlData = array(
					"store_name" => array(
						"type"          => 1, 
						"title"         => $txt["store_name"],
						"key"           => "StoreName",
						"value"         => "" ,
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => $txt['store_name_input']
					),
					"store_code" => array(
						"type"          => 1, 
						"title"         => $txt["store_code"],
						"key"           => "store_code",
						"value"         => "",
						"display"       => true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => $txt['store_code_input'],
						"required"		=> false
					),
					"store_code_txt" => array(
						"type"          => 12, 
						"title"         => $txt["store_code"],
						"key"           => "",
						"value"         => "",
						"display"       => false,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => ""
					),
					"store_type_select" => array(
						"type"          => 9, 
						"title"         => $txt["store_type"],
						"key"           => "parents_store_type",
						"value"         => "",
						"data"         	=> $store_type_array,
						"display"       => empty($store_status["free"]) && empty($store) ? true : false,
						"desc"          => "",
						"EventFunc"     => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => "",
						"SubMenuKey"   	=> "store_type_id",
						"SubValue"   	=> ""
					),
					"store_type" => array(
						"type"          => 12, 
						"title"         => $txt["store_type"],
						"key"           => "parents_category",
						"value"         => $store_info->store_type_name,
						"display"       => empty($store_status["free"]) && empty($store) ? false : true,
						"desc"          => "",
						"EventFunc"     => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => ""
					),
					"store_type_id" => array(
						"type"          => 11, 
						"title"         => "",
						"key"           => "store_type_id",
						"value"         => $store_info->store_type,
						"display"       => empty($store_status["free"]) && empty($store) ? false : true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => ""
					),
					"deadline" => array(
						"type"          => 12, 
						"title"         => $txt["deadline"],
						"key"           => "",
						"value"         => isset($store_status["free"]) && $store_status["free"] > 0 ? $txt['free_date_spec_desc'] . " " . $deadline : "",
						"display"       => isset($store_status["free"]) && $store_status["free"] > 0 ? true : false,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => ""
					),
					"date_spec" => array(
						"type"          => 2, 
						"title"         => $txt["deadline"],
						"key"           => "date_spec",
						"value"         => "",
						"data"         	=> $deadline,
						"display"       => isset($store_status["free"]) && $store_status["free"] > 0 ? false : true,
						"desc"          => "",
						"attrClass"     => "",
						"hasPlugin"     => "",
						"placeholder"   => ""
					)
		         );

		return $htmlData;

	}


	// 組合資料

	public static function store_input_data_bind( $htmlData, $OriData )
	{

		$_this = new self();

		$result = $htmlData;

		$store_type_data = Option_logic::get_store_data();

		$store_status = Store_logic::get_store_cnt();

		if ( !empty($OriData) && is_array($OriData) ) 
		{

			foreach ($htmlData as &$row) 
			{

				if ( is_array($row) ) 
				{

				   $row["value"] = isset($OriData[$row["key"]]) ? $OriData[$row["key"]] : "" ;
				   
				}

			}

			$htmlData["store_name"]["value"] = !empty($OriData["store_name"]) ? $OriData["store_name"] : "" ;

			$htmlData["store_type_select"]["value"] = !empty($OriData["store_type"]) ? $_this->get_parents_store_type( $store_type_data, $OriData["store_type"] )  : "" ;

			$htmlData["store_type_select"]["SubValue"] = !empty($OriData["store_type"]) ? $OriData["store_type"] : "" ;
			
			$htmlData["store_type_id"]["value"] = !empty($OriData["store_type"]) ? $OriData["store_type"] : "" ;
			
			$htmlData["store_code"]["display"] = false ;
			
			$htmlData["store_code_txt"]["display"] = true ;

			$htmlData["store_code_txt"]["value"] = !empty($OriData["store_code"]) ? $OriData["store_code"] : "" ;

			$htmlData["date_spec"]["display"] = false ;

		}

		return $htmlData;

	}


	public static function get_parents_store_type( $store_type_data, $store_type )
	{

		$result = 0;

		if ( !empty($store_type_data) && is_array($store_type_data) && !empty($store_type) && is_int($store_type) ) 
		{

	        foreach ($store_type_data as $index => $child) 
	        {

	        	foreach ($child["data"] as $childIndex => $childValue) 
	        	{
	        		
	        		if (  (int)$childIndex === (int)$store_type ) 
	        		{
	        			
	        			$result = $index;

	        			break 2;
	        			
	        		}

	        	}

	        }

			
		}

		return $result;

	}


}
