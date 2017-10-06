<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\logic\Msg_logic;

class MsgLogic extends TestCase
{

	public function testInsertFormat()
	{

		$test1 = Msg_logic::insert_format( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Msg_logic::insert_format( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Msg_logic::insert_format( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testUpdateFormat()
	{

		$test1 = Msg_logic::update_format( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Msg_logic::update_format( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Msg_logic::update_format( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testTimeFormat()
	{

		$test1 = Msg_logic::time_format( "" );

		$this->assertTrue( is_object($test1) );
		$this->assertEquals($test1, new \stdClass);

		$test1 = Msg_logic::time_format( 0 );

		$this->assertTrue( is_object($test1) );
		$this->assertEquals($test1, new \stdClass);

		$test1 = Msg_logic::time_format( array() );

		$this->assertTrue( is_object($test1) );
		$this->assertEquals($test1, new \stdClass);

	}

	public function testShowPopupMsg()
	{

		$test1 = Msg_logic::show_popup_msg( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Msg_logic::show_popup_msg( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Msg_logic::show_popup_msg( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testGetMsgList()
	{

		$test1 = Msg_logic::get_msg_list( "" );

		$this->assertTrue( true );

	}

	public function testGetMsgOption()
	{

		$test1 = Msg_logic::get_msg_option();

		$this->assertTrue( is_array($test1) );

	}

	public function testGetSingleMsg()
	{

		$test1 = Msg_logic::get_single_msg( "" );

		$this->assertTrue( is_object($test1) );
		$this->assertEquals($test1, new \stdClass);

		$test1 = Msg_logic::get_single_msg( 0 );

		$this->assertTrue( is_object($test1) );
		$this->assertEquals($test1, new \stdClass);

		$test1 = Msg_logic::get_single_msg( array() );

		$this->assertTrue( is_object($test1) );
		$this->assertEquals($test1, new \stdClass);

	}

	public function testEditMsg()
	{

		$test1 = Msg_logic::edit_msg( "", "" );

		$this->assertFalse( $test1 );

		$test1 = Msg_logic::edit_msg( 0, 0 );

		$this->assertFalse( $test1 );

		$test1 = Msg_logic::edit_msg( array(), array() );

		$this->assertFalse( $test1 );

	}

	public function testAddMsg()
	{

		$test1 = Msg_logic::add_msg( "" );

		$this->assertFalse( $test1 );

		$test1 = Msg_logic::add_msg( 0 );

		$this->assertFalse( $test1 );

		$test1 = Msg_logic::add_msg( array() );

		$this->assertFalse( $test1 );

	}

	public function testCloneMsg()
	{

		$test1 = Msg_logic::clone_msg( "" );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Msg_logic::clone_msg( 0 );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

		$test1 = Msg_logic::clone_msg( array() );

		$this->assertTrue( empty($test1) );
		$this->assertTrue( is_array($test1) );
		$this->assertEquals($test1, array());

	}

	public function testAddNormalMsg()
	{

		$test1 = Msg_logic::add_normal_msg( "", "" );

		$this->assertFalse( $test1 );

		$test1 = Msg_logic::add_normal_msg( 0, 0 );

		$this->assertFalse( $test1 );

		$test1 = Msg_logic::add_normal_msg( array(), array() );

		$this->assertFalse( $test1 );

	}

	public function testAddNoticeMsg()
	{

		$test1 = Msg_logic::add_notice_msg( "", "" );

		$this->assertFalse( $test1 );

		$test1 = Msg_logic::add_notice_msg( 0, 0 );

		$this->assertFalse( $test1 );

		$test1 = Msg_logic::add_notice_msg( array(), array() );

		$this->assertFalse( $test1 );

	}


}
