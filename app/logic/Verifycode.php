<?php

namespace App\logic;

use Illuminate\Support\Facades\Session;

class Verifycode{

	private $rules = array(
						1 => array(
							0 => "零",
							1 => "壹",
							2 => "貳",
							3 => "參",
							4 => "肆",
							5 => "伍",
							6 => "陸",
							7 => "柒",
							8 => "捌",
							9 => "玖"
						),
						2 => array(
							0 => "0",
							1 => "1",
							2 => "2",
							3 => "3",
							4 => "4",
							5 => "5",
							6 => "6",
							7 => "7",
							8 => "8",
							9 => "9"
						),
						3 => array(
							0 => "零",
							1 => "一",
							2 => "二",
							3 => "三",
							4 => "四",
							5 => "五",
							6 => "六",
							7 => "七",
							8 => "八",
							9 => "九"
						),
						4 => array(
							0 => "零",
							1 => "一筒",
							2 => "二筒",
							3 => "三筒",
							4 => "四筒",
							5 => "五筒",
							6 => "六筒",
							7 => "七筒",
							8 => "八筒",
							9 => "九筒"
						),
						5 => array(
							0 => "0",
							1 => "一條",
							2 => "二條",
							3 => "三條",
							4 => "四條",
							5 => "五條",
							6 => "六條",
							7 => "七條",
							8 => "八條",
							9 => "九條"
						),
						6 => array(
							0 => "0",
							1 => "一萬",
							2 => "二萬",
							3 => "三萬",
							4 => "四萬",
							5 => "五萬",
							6 => "六萬",
							7 => "七萬",
							8 => "八萬",
							9 => "九萬"
						),
					);

	private $use_rules = array();

	private $verifycode = "";

	private $verifycode_length = 4;

	private $filename = array();

	private function random_num( $length = 4, $min = 0, $max = 9999 )
	{
		
		$num = rand($min, $max);
		
		$result = str_pad($num, $length, "0", STR_PAD_LEFT);
		
		return $result;

	}

	private function encode_num( $num )
	{
		return md5($num);
	}

	private function set_rules()
	{
		
		$tmp = array();

		$rules_map = $this->rules;

		$key = $this->random_num(1,1,6);

		$this->use_rules = $rules_map[$key];

		$this->verifycode = $this->random_num($this->verifycode_length,0,9999);

		for ($i=0; $i < $this->verifycode_length; $i++) 
		{ 
			$tmp[$i] = $this->encode_num($this->use_rules[$this->verifycode[$i]]);
		}

		$this->filename = $tmp;

	}

	public function get_verify_img()
	{

		return $this->filename; 	
	
	}

	public static function auth_verify_code( $input )
	{

		$result = $input == Session::get('Verifycode') ? true : false ;

        Session::forget('Verifycode');

        Session::forget('Verifycode_img');

		return $result;

	}

	public static function get_verify_code()
	{

		$_this = new self();

		$Verifycode = Session::get('Verifycode');
		$Verifycode_img = Session::get('Verifycode_img');

		if ( !isset($Verifycode) && !isset($Verifycode_img) )
		{
	
			$_this->set_rules();

			Session::put('Verifycode', $_this->verifycode);
			
			Session::put('Verifycode_img', $_this->get_verify_img());
	
		}
	
	}

}

?>