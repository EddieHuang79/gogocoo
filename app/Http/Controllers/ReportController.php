<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\logic\Report_logic;

class ReportController extends Controller
{

	// 每月訂單銷售狀況

	public function month_order_view()
	{

        $month_order_view = Report_logic::month_order_view();

        echo $month_order_view;

        exit();

	}

	// 每日出入庫狀況

	public function year_stock_view()
	{

        $year_stock_view = Report_logic::year_stock_view();

        echo $year_stock_view;

        exit();

	}

	// 商品庫存佔比

	public function stock_analytics()
	{

        $stock_analytics = Report_logic::stock_analytics();

        echo $stock_analytics;

        exit();

	}

	// 每月銷售top 5

	public function year_product_top5()
	{

        $year_product_top5 = Report_logic::year_product_top5();

        echo $year_product_top5;

        exit();

	}

	// TOP 5 庫存

	public function product_top5_stack()
	{

        $product_top5_stack = Report_logic::product_top5_stack();

        echo $product_top5_stack;

        exit();

	}

}
