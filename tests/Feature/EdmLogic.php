<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\logic\Edm_logic;

class EdmLogic extends TestCase
{

	public function testInsertFormat()
	{

		$test1 = Edm_logic::insert_format( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Edm_logic::insert_format( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Edm_logic::insert_format( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testUpdateFormat()
	{

		$test1 = Edm_logic::update_format( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Edm_logic::update_format( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Edm_logic::update_format( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testContentHandle()
	{

		$test1 = Edm_logic::content_handle( "" );

		$this->assertTrue( empty($test1) );
		// $this->assertTrue( is_array($test1) );
		// $this->assertEquals($test1, array());

		$test1 = Edm_logic::content_handle( 0 );

		$this->assertTrue( empty($test1) );
		// $this->assertTrue( is_array($test1) );
		// $this->assertEquals($test1, array());

		$test1 = Edm_logic::content_handle( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testAddEdm()
	{

		$test1 = Edm_logic::add_edm( "" );

		$this->assertFalse( $test1 );

		$test1 = Edm_logic::add_edm( 0 );

		$this->assertFalse( $test1 );

		$test1 = Edm_logic::add_edm( array() );

		$this->assertFalse( $test1 );

	}

	public function testEditEdm()
	{

		$test1 = Edm_logic::edit_edm( "", "" );

		$this->assertFalse( $test1 );

		$test1 = Edm_logic::edit_edm( 0, 0 );

		$this->assertFalse( $test1 );

		$test1 = Edm_logic::edit_edm( array(), array() );

		$this->assertFalse( $test1 );

	}

	public function testGetSingleEdm()
	{

		$test1 = Edm_logic::get_single_edm( "" );

		$this->assertEquals($test1, new \stdClass);

		$test1 = Edm_logic::get_single_edm( 0 );

		$this->assertEquals($test1, new \stdClass);

		$test1 = Edm_logic::get_single_edm( array() );

		$this->assertEquals($test1, new \stdClass);

	}

	public function testCloneEdm()
	{

		$test1 = Edm_logic::clone_edm( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Edm_logic::clone_edm( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Edm_logic::clone_edm( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testChangeStatus()
	{

		$test1 = Edm_logic::change_status( "", "" );

		$this->assertFalse( $test1 );

		$test1 = Edm_logic::change_status( 0, 0 );

		$this->assertFalse( $test1 );

		$test1 = Edm_logic::change_status( array(), array() );

		$this->assertFalse( $test1 );

	}

}
