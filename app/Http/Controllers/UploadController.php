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

        $htmlData = Upload_logic::get_product_upload_input_template_array();

        $htmlData["action"] = "/product_upload_process";

        $htmlData["method"] = "post"; 
  
        $htmlJsonData = json_encode($htmlData);


        $assign_page = "upload/product_upload";

        $data = compact('assign_page', 'htmlJsonData');

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
 
        $htmlData = Upload_logic::get_purchase_upload_input_template_array();

        $htmlData["action"] = "/purchase_upload_process";

        $htmlData["method"] = "post"; 
  
        $htmlJsonData = json_encode($htmlData);


        $assign_page = "upload/purchase_upload";

        $data = compact('assign_page', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    public function order_upload()
    {

        $htmlData = Upload_logic::get_order_upload_input_template_array();

        $htmlData["action"] = "/order_upload_process";

        $htmlData["method"] = "post"; 
  
        $htmlJsonData = json_encode($htmlData);

 
        $assign_page = "upload/order_upload";

        $data = compact('assign_page', 'htmlJsonData');

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

        Upload_logic::product_upload_format( $product_column );

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

        Upload_logic::purchase_upload_format( $purchase_column );

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

        Upload_logic::order_upload_format( $order_column );

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

            // Upload_logic::order_upload_process( $upload_files );
            Upload_logic::assign_order_upload_process( $upload_files );
            
        }   

        return redirect("/order_upload");

    } 

}
