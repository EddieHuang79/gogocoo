<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Stock_logic;
use App\logic\Product_logic;

class StockController extends Controller
{

    public function stock_batch_list()
    {

        $data = Stock_logic::get_stock_batch_list( $_GET );

        $stock_list = $data['stock_list'];

        $stock_data = $data['data'];

        $htmlData = Stock_logic::stock_batch_list_data_bind( $stock_list );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "stock/stock_batch_list";

        $data = compact('assign_page', 'stock_list', 'stock_data', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    public function stock_total_list()
    {

        $data = Stock_logic::get_stock_total_list( $_GET );

        $stock_list = $data['stock_list'];

        $stock_data = $data['data'];

        $htmlData = Stock_logic::stock_total_list_data_bind( $stock_list );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "stock/stock_total_list";

        $data = compact('assign_page', 'stock_list', 'stock_data', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    public function immediate_stock_list()
    {

        $data = Stock_logic::get_immediate_stock_list( $_GET );

        $stock_list = $data['stock_list'];

        $stock_data = $data['data'];

        $htmlData = Stock_logic::stock_immediate_list_data_bind( $stock_list );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "stock/immediate_stock_list";

        $data = compact('assign_page', 'stock_list', 'stock_data', 'htmlJsonData');

        return view('webbase/content', $data);

    }

    public function lack_of_stock_list()
    {

        $data = Stock_logic::get_lack_of_stock_list( $_GET );

        $stock_list = $data['stock_list'];

        $stock_data = $data['data'];

        $htmlData = Stock_logic::stock_lack_list_data_bind( $stock_list );

        $htmlJsonData = json_encode($htmlData);

        $assign_page = "stock/lack_of_stock_list";

        $data = compact('assign_page', 'stock_list', 'stock_data', 'htmlJsonData');

        return view('webbase/content', $data);

    }

}
