<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model {


	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
}
