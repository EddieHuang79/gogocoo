<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Upload_logic;
use App\logic\Product_logic;
use App\logic\Purchase_logic;
use App\logic\Order_logic;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    public function product_upload()
    {
 
        $assign_page = "upload/product_upload";

        $data = compact('assign_page');

        return view('webbase/content', $data);

    }

    public function product_spec_upload()
    {
 
        $assign_page = "upload/product_spec_upload";

        $data = compact('assign_page');

        return view('webbase/content', $data);

    }

    public function purchase_upload()
    {
 
        $assign_page = "upload/purchase_upload";

        $data = compact('assign_page');

        return view('webbase/content', $data);

    }

    public function order_upload()
    {

        // get now page

        // Service_logic::get_service_id_by_url_and_save( $request->path() );

        // search bar setting

        // $search_tool = array(5,6);

        // Redis_tool::set_search_tool( $search_tool );

        // $store = store_logic::get_store_info( $_GET );
 
        $assign_page = "upload/order_upload";

        $data = compact('assign_page');

        return view('webbase/content', $data);

    }

    public function product_upload_format_download()
    {

        $main_column = array(
                            array(
                                "name" => "product_name"
                            ),
                            array(
                                "name" => "safe_amount"
                            )
                        );

        $extra_column = Product_logic::get_product_extra_column();

        $product_column = array_merge( $main_column, $extra_column );

        $product_upload_format = Upload_logic::product_upload_format( $product_column );

        $product_upload_format->download('xlsx');

    }

    public function product_spec_upload_format_download()
    {

        $main_column = array(
                            array(
                                "name" => "product_name"
                            )
                        );

        $extra_column = Product_logic::get_product_extra_column();

        $product_column = array_merge( $main_column, $extra_column );

        if ( Product_logic::is_spec_function_active() ) 
        {

            $spec_column = Product_logic::get_product_spec_column();

            $spec_column = Product_logic::product_spec_header_download_format( $spec_column );

            $product_spec_column = array_merge( $product_column, $spec_column );

            $product_spec_upload_format = Upload_logic::product_spec_upload_format( $product_spec_column );

            $product_spec_upload_format->download('xlsx');

        }

    }

    public function purchase_upload_format_download()
    {

        $main_column = array(
                            array(
                                "name" => "product_name"
                            ),
                            array(
                                "name" => "number"
                            ),
                            array(
                                "name" => "in_warehouse_date"
                            ),
                        );

        $extra_column = Purchase_logic::get_purchase_extra_column();

        $purchase_column = array_merge( $main_column, $extra_column );

        $purchase_upload_format = Upload_logic::purchase_upload_format( $purchase_column );

        $purchase_upload_format->download('xlsx');

    }

    public function order_upload_format_download()
    {

        $main_column = array(
                            array(
                                "name" => "product_name"
                            ),
                            array(
                                "name" => "number"
                            ),
                            array(
                                "name" => "out_warehouse_date"
                            ),
                        );

        $extra_column = Order_logic::get_order_extra_column();

        $order_column = array_merge( $main_column, $extra_column );

        $order_upload_format = Upload_logic::order_upload_format( $order_column );

        $order_upload_format->download('xlsx');

    }    

    public function product_upload_process(Request $request)
    {

        if ($request->hasFile('user_product_upload')) 
        {
            
            Storage::makeDirectory("user_product_upload");

            $upload_files = $request->file('user_product_upload')->store('user_product_upload');

            Upload_logic::product_upload_process( $upload_files );
            
        }       

        return redirect("/product_upload");

    }

    public function product_spec_upload_process(Request $request)
    {

        if ($request->hasFile('user_product_spec_upload')) 
        {
            
            Storage::makeDirectory("user_product_spec_upload");

            $upload_files = $request->file('user_product_spec_upload')->store('user_product_spec_upload');

            Upload_logic::product_spec_upload_process( $upload_files );
            
        }  

        return redirect("/product_spec_upload");

    }

    public function purchase_upload_process(Request $request)
    {

        if ($request->hasFile('user_purchase_upload')) 
        {
            
            Storage::makeDirectory("user_purchase_upload");

            $upload_files = $request->file('user_purchase_upload')->store('user_purchase_upload');

            Upload_logic::purchase_upload_process( $upload_files );
            
        }   

        return redirect("/purchase_upload");

    }

    public function order_upload_process(Request $request)
    {

        if ($request->hasFile('user_order_upload')) 
        {
            
            Storage::makeDirectory("user_order_upload");

            $upload_files = $request->file('user_order_upload')->store('user_order_upload');

            Upload_logic::order_upload_process( $upload_files );
            
        }   

        return redirect("/order_upload");

    } 

}
