<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ProductModule extends Migration
{

    protected $user_table = "user";
    protected $role_table = "role";
    protected $service_table = "service";
    protected $user_role_table = "user_role_relation";
    protected $role_service_table = "role_service_relation";
    protected $service_user_table = "service_user_relation";
    protected $product_table = "product";
    protected $product_extra_table = "product_extra";
    protected $product_spec_table = "product_spec";
    protected $option_table = "option";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // 主資料

        Schema::create($this->product_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('product_name');
            $table->integer('safe_amount');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });


        // 額外欄位

        Schema::create($this->product_extra_table, function (Blueprint $table) {
            $table->increments('extra_id')->unique();
            $table->integer('product_id')->unsigned();
            $table->integer('shop_id');
            $table->string('qc_number');
            $table->string('barcode');
            $table->integer('keep_for_days');
            $table->engine = 'InnoDB';
        });

        Schema::table($this->product_extra_table, function($table) {
           $table->foreign('product_id')->references('id')->on($this->product_table);
        });


        // service
        $product_id = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '商品管理',
                'link'          => '',
                'parents_id'    => 0,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 25,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '新增商品',
                'link'          => '/product/create',
                'parents_id'    => $product_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 26,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '商品列表',
                'link'          => '/product',
                'parents_id'    => $product_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 27,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );

         // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%商品%')->get();
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

        Schema::dropIfExists($this->product_extra_table);
        Schema::dropIfExists($this->product_table);

        // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%商品%')->get();
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
