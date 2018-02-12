<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Ecoupon extends Migration
{


    protected $service_table = "service";
    protected $user_role_table = "user_role_relation";
    protected $role_service_table = "role_service_relation";
    protected $ecoupon_table = "ecoupon";
    protected $ecoupon_user_table = "ecoupon_user_list";
    protected $ecoupon_use_record_table = "ecoupon_use_record";
    protected $store_table = "store";
    protected $mall_record_table = "mall_record";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // type: 1 - 折扣,2 - 代金, 3 - 滿減

        // ecoupon_content: 1 - percent, 2 - int, 3 - JSON ARRAY
        
        // send_type: 1 - login, 2 - other....

        // max_num: 最大發放數

        Schema::create($this->ecoupon_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('type')->default(1)->index();
            $table->string('name');
            $table->string('description');
            $table->string('ecoupon_content');
            $table->integer('match_type')->default(1)->index();
            $table->string('match_id');
            $table->integer('send_type')->default(1)->index();
            $table->integer('max_num');
            $table->dateTime('ecoupon_active_date');
            $table->dateTime('deadline');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        // status: 1 - 未使用，2 - 已使用

        Schema::create($this->ecoupon_user_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('ecoupon_id')->unsigned();
            $table->integer('store_id')->unsigned();
            $table->string('code');
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table($this->ecoupon_user_table, function($table) {
           $table->foreign('ecoupon_id')->references('id')->on($this->ecoupon_table);
           $table->foreign('store_id')->references('id')->on($this->store_table);
        });

        Schema::create($this->ecoupon_use_record_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('record_id')->unsigned();
            $table->integer('ecoupon_use_id')->unsigned();
            $table->integer('store_id')->unsigned();
            $table->integer('discount')->default(0);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table($this->ecoupon_use_record_table, function($table) {
           $table->foreign('record_id')->references('id')->on($this->mall_record_table);
           $table->foreign('ecoupon_use_id')->references('id')->on($this->ecoupon_user_table);
           $table->foreign('store_id')->references('id')->on($this->store_table);
        });


        // service
        $parents_id = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => 'Ecoupon管理',
                'link'          => '',
                'parents_id'    => 0,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 51,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '新增Ecoupon',
                'link'          => '/ecoupon/create',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 52,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => 'Ecoupon列表',
                'link'          => '/ecoupon',
                'parents_id'    => $parents_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 53,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );


         // role_service

        $service_data = DB::table('service')->select('id')->whereIn('name', array('Ecoupon管理','新增Ecoupon','Ecoupon列表'))->get();
        
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

        $service_data = DB::table('service')->select('id')->whereIn('name', array('Ecoupon管理','新增Ecoupon','Ecoupon列表'))->get();
        
        foreach ($service_data as $row) 
        {
        
            DB::table($this->role_service_table)->where("service_id" ,"=", $row->id)->delete();
        
        }

        foreach ($service_data as $row) 
        {
        
            DB::table($this->service_table)->where("id" ,"=", $row->id)->delete();
        
        }

        Schema::dropIfExists($this->ecoupon_use_record_table);
    
        Schema::dropIfExists($this->ecoupon_user_table);
    
        Schema::dropIfExists($this->ecoupon_table);

    }

}
