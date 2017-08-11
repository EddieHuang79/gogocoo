<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Upload extends Migration
{

    protected $service_table = "service";
    protected $role_service_table = "role_service_relation";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // service
        $upload_id = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '資料上傳',
                'link'          => '',
                'parents_id'    => 0,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 39,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '商品上傳',
                'link'          => '/product_upload',
                'parents_id'    => $upload_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 40,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '進貨上傳',
                'link'          => '/purchase_upload',
                'parents_id'    => $upload_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 42,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '訂單上傳',
                'link'          => '/order_upload',
                'parents_id'    => $upload_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 43,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

         // role_service
        $service_data = DB::table('service')->select('id')->whereIn('name', array('資料上傳','商品上傳','進貨上傳','訂單上傳'))->get();
        foreach ($service_data as $row) 
        {
            DB::table($this->role_service_table)->insert(
                array(
                    'role_id'      => 1,
                    'service_id'   => $row->id
                )
            );
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // role_service
        $service_data = DB::table('service')->select('id')->whereIn('name', array('資料上傳','商品上傳','進貨上傳','訂單上傳'))->get();
        foreach ($service_data as $row) 
        {
            DB::table($this->role_service_table)->where("service_id" ,"=", $row->id)->delete();
        }

        foreach ($service_data as $row) 
        {
            DB::table($this->service_table)->where("id" ,"=", $row->id)->delete();
        }

    }
}
