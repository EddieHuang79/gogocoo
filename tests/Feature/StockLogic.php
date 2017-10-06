<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\logic\Stock_logic;

class StockLogic extends TestCase
{

    public function testInsertFormat()
    {

		$test1 = Stock_logic::insert_format( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::insert_format( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::insert_format( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

    }

	public function testAddStock()
	{

		$test1 = Stock_logic::add_stock( array() );

		$this->assertFalse( $test1 );

	}

	public function testGetStockBatchList()
	{

		$test1 = Stock_logic::get_stock_batch_list( array(), 0 );

		$this->assertTrue( is_array($test1) );

	}

	public function testGetStockTotalList()
	{

		$test1 = Stock_logic::get_stock_total_list( array(), 0 );

		$this->assertTrue( is_array($test1) );

	}

	public function testGetImmediateStockList()
	{

		$test1 = Stock_logic::get_immediate_stock_list( array(), 0 );

		$this->assertTrue( is_array($test1) );

	}

	public function testGetLackOfStockList()
	{

		$test1 = Stock_logic::get_lack_of_stock_list( array(), 0 );

		$this->assertTrue( is_array($test1) );

	}

	public function testFIFOGetStockId()
	{

		$test1 = Stock_logic::FIFO_get_stock_id( "", "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::FIFO_get_stock_id( 0, 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::FIFO_get_stock_id( array(), array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testCostStock()
	{

		$test1 = Stock_logic::cost_stock( array() );

		$this->assertFalse( $test1 );

	}

	public function testGetStockAnalytics()
	{

		$test1 = Stock_logic::get_stock_analytics( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::get_stock_analytics( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::get_stock_analytics( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testGetStockAnalyticsAddParentsCategory()
	{

		$test1 = Stock_logic::get_stock_analytics_add_parents_category( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::get_stock_analytics_add_parents_category( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::get_stock_analytics_add_parents_category( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testGetStockAndSafeAmount()
	{

		$test1 = Stock_logic::get_stock_and_safe_amount( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::get_stock_and_safe_amount( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Stock_logic::get_stock_and_safe_amount( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

}
