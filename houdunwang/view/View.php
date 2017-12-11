<?php

namespace houdunwang\view;
class View
{
	public function __call ( $name , $arguments )
	{
		//p($name);//make
		//p($arguments);
		return self ::runParse ( $name , $arguments );
	}

	public static function __callStatic ( $name , $arguments )
	{
		//p($arguments);die;
		return self ::runParse ( $name , $arguments );
	}

	public static function runParse ( $name , $arguments )
	{
		//p($arguments);die;
		//(new Base)->$name($arguments);
		return call_user_func_array ( [ new Base , $name ] , $arguments);
	}

}