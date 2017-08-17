<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Mall extends Migration
{

    protected $user_table = "user";
    protected $service_table = "service";
    protected $mall_product_table = "mall_product";
    protected $mall_product_spec_table = "mall_product_spec";
    protected $mall_record_table = "mall_record";
    protected $role_service_table = "role_service_relation";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // 商城管理

        #   public - 1:yes,2:no

        Schema::create($this->mall_product_table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_name');
            $table->text('description');
            $table->string('pic');
            $table->integer('public');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });        

        // insert msg

        DB::table($this->mall_product_table)->insert(
            array(
                'product_name'  => '購買店舖',
                'description'   => '購買店舖',
                'pic'           => '/product_image/',
                'public'        => 1,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

        #   date_spec - unit: month

        Schema::create($this->mall_product_spec_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mall_product_id')->unsigned();
            $table->integer('cost');
            $table->integer('date_spec');
            $table->engine = 'InnoDB';
        });    

        #   status - 0:未付,1:付,2:退

        Schema::create($this->mall_record_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mall_product_id')->unsigned();
            $table->integer('mall_product_spec_id')->unsigned();
            $table->dateTime('deadline');
            $table->integer('user_id')->unsigned();
            $table->integer('cost');
            $table->integer('number');
            $table->integer('total');
            $table->integer('status');
            $table->dateTime('paid_at');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });    


        Schema::table($this->mall_product_spec_table, function($table) {
           $table->foreign('mall_product_id')->references('id')->on($this->mall_product_table);
        });

        Schema::table($this->mall_record_table, function($table) {
           $table->foreign('mall_product_id')->references('id')->on($this->mall_product_table);
           $table->foreign('mall_product_spec_id')->references('id')->on($this->mall_product_spec_table);
           $table->foreign('user_id')->references('id')->on($this->user_table);
        });

        // service

        $service_id = array();

        $service_id[] = DB::table($this->service_table)->insertGetId(
                                array(
                                    'name'          => '商城管理',
                                    'link'          => '',
                                    'parents_id'    => 0,
                                    'status'        => 1,
                                    'public'        => 4,
                                    'sort'          => 22,
                                    'created_at'    => date("Y-m-d H:i:s"),
                                    'updated_at'    => date("Y-m-d H:i:s")
                                )
                            );

        $service_id[] = DB::table($this->service_table)->insertGetId(
                                array(
                                    'name'          => '建立產品',
                                    'link'          => '/mall/create',
                                    'parents_id'    => $service_id[0],
                                    'status'        => 1,
                                    'public'        => 4,
                                    'sort'          => 23,
                                    'created_at'    => date("Y-m-d H:i:s"),
                                    'updated_at'    => date("Y-m-d H:i:s")
                                )
                            );

        $service_id[] = DB::table($this->service_table)->insertGetId(
                                array(
                                    'name'          => '產品列表',
                                    'link'          => '/mall',
                                    'parents_id'    => $service_id[0],
                                    'status'        => 1,
                                    'public'        => 4,
                                    'sort'          => 24,
                                    'created_at'    => date("Y-m-d H:i:s"),
                                    'updated_at'    => date("Y-m-d H:i:s")
                                )
                            );

        // role_service


        foreach ($service_id as $row) 
        {

            DB::table($this->role_service_table)->insert(
                array(
                    'role_id'      => 1,
                    'service_id'   => $row
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
        
        Schema::dropIfExists($this->mall_record_table);
        Schema::dropIfExists($this->mall_product_spec_table);
        Schema::dropIfExists($this->mall_product_table);
    

         // role_service
        $service_data = DB::table('service')->select('id')->whereIn('name', array('商城管理','建立產品','產品列表'))->get();
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
