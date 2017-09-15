<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ProductCategory extends Migration
{

    protected $user_table = "user";
    protected $store_table = "store";
    protected $option_table = "option";
    protected $role_table = "role";
    protected $service_table = "service";
    protected $user_role_table = "user_role_relation";
    protected $role_service_table = "role_service_relation";
    protected $service_user_table = "service_user_relation";
    protected $product_category_table = "product_category";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create($this->product_category_table, function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->integer('shop_id')->default(0);
            $table->integer('parents_id')->default(0);
            $table->string('name');
            $table->engine = 'InnoDB';
        });

        DB::table($this->product_category_table)->insert(
            array(
                'shop_id'       => 0,
                'parents_id'    => 0,
                'name'          => "食物"
            )
        );

        DB::table($this->product_category_table)->insert(
            array(
                'shop_id'       => 0,
                'parents_id'    => 0,
                'name'          => "衣服"
            )
        );

        DB::table($this->product_category_table)->insert(
            array(
                'shop_id'       => 0,
                'parents_id'    => 1,
                'name'          => "食品"
            )
        );

        DB::table($this->product_category_table)->insert(
            array(
                'shop_id'       => 0,
                'parents_id'    => 1,
                'name'          => "飲料"
            )
        );

        DB::table($this->product_category_table)->insert(
            array(
                'shop_id'       => 0,
                'parents_id'    => 1,
                'name'          => "零食"
            )
        );

        DB::table($this->product_category_table)->insert(
            array(
                'shop_id'       => 0,
                'parents_id'    => 2,
                'name'          => "上衣"
            )
        );

        DB::table($this->product_category_table)->insert(
            array(
                'shop_id'       => 0,
                'parents_id'    => 2,
                'name'          => "褲子"
            )
        );

        DB::table($this->product_category_table)->insert(
            array(
                'shop_id'       => 0,
                'parents_id'    => 2,
                'name'          => "裙子"
            )
        );

        // service
        $product_id = DB::table($this->service_table)->insertGetId(
            array(
                'name'          => '商品分類管理',
                'link'          => '',
                'parents_id'    => 0,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 44,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '新增商品分類',
                'link'          => '/product_category/create',
                'parents_id'    => $product_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 45,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );
        DB::table($this->service_table)->insert(
            array(
                'name'          => '商品分類列表',
                'link'          => '/product_category',
                'parents_id'    => $product_id,
                'status'        => 1,
                'public'        => 4,
                'sort'          => 46,
                'created_at'    => date("Y-m-d H:i:s"),
                'updated_at'    => date("Y-m-d H:i:s")
            )
        );



        // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%商品分類%')->get();
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

        Schema::dropIfExists($this->product_category_table);

        // role_service
        $service_data = DB::table('service')->select('id')->where('name', 'like', '%商品分類%')->get();
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
