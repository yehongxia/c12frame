<?php

namespace houdunwang\model;

class Model
{
	public function __call ( $name , $arguments )
	{
		return self ::runParse ( $name , $arguments );
	}

	public static function __callStatic ( $name , $arguments )
	{
		return self ::runParse ( $name , $arguments );
	}

	public static function runParse ( $name , $arguments )
	{
		//p(get_called_class ());
		//获取当前调用的模型的名称作为查询的数据表名
		$class = get_called_class ();
		//p($class);//system\model\Student
		return call_user_func_array ( [ new Base($class) , $name ] , $arguments );
	}
}

