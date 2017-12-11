<?php
//头部
/**
 * 助手函数库
 */

if ( ! function_exists ( 'dd' ) ) {
	/**
	 * 打印函数
	 *
	 * @param $var    打印的变量
	 */
	function dd ( $var )
	{
		echo '<pre style="background: #ccc;padding: 8px;border-radius: 6px">';
		var_dump ( $var );
		echo '</pre>';
	}
}

if ( ! function_exists ( 'p' ) ) {
	/**
	 * 打印函数
	 *
	 * @param $var    打印的变量
	 */
	function p ( $var )
	{
		echo '<pre style="background: #ccc;padding: 8px;border-radius: 6px">';
		if ( is_bool ( $var ) || is_null ( $var ) ) {
			var_dump ( $var );
		}
		else {
			print_r ( $var );
		}
		echo '</pre>';
	}
}

if ( ! function_exists ( 'u' ) ) {
	/**
	 * 生成url ?s=模块/控制器/方法
	 *
	 * @param $url    url参数
	 */
	function u ( $url )
	{
		//将$url拆成数组
		$info = explode ( '/' , $url );
		//p($info);die;
		//p(MODULE);
		//p(CONTROLLER);
		//p(ACTION);
		//p($url);//home/index/index【模块/控制器/方法】
		//p($url);//index/index【控制器/方法】
		//p($url);//index【方法】
		//以上三个最终都组合成?s=模块/控制器/方法
		if ( count ( $info ) == 1 ) {
			$resUrl = "?s=" . MODULE . '/' . CONTROLLER . '/' . $info[ 0 ];
		}
		else if ( count ( $info ) == 2 ) {
			$resUrl = "?s=" . MODULE . '/' . $info[ 0 ] . '/' . $info[ 1 ];
		}
		else {
			$resUrl = "?s=" . $info[ 0 ] . '/' . $info[ 1 ] . '/' . $info[ 2 ];
		}

		return $resUrl;
	}
}

if ( ! function_exists ( 'c' ) ) {
	/**
	 * 打印函数
	 *
	 * @param $var    打印的变量
	 */
	function c ( $var = '' )
	{
		if ( ! $var ) {
			return null;
		}
		//p($var);//database.driver
		//将字符串转为数组
		$info = explode ( '.' , $var );
		//加载对应的配置项文件
		$file = "../system/config/" . $info[ 0 ] . '.php';
		if ( ! is_file ( $file ) ) {
			return null;
		}
		$data = include $file;
		//p($data);die;
		//如果有一个元素，那么
		if ( count ( $info ) == 1 ) {
			//返回全部数据
			return $data;
		}
		else {
			//说明数组有多个元素
			return isset( $data[ $info[ 1 ] ] ) ? $data[ $info[ 1 ] ] : null;
		}
	}
}

/**
 * 定义常量IS_POST检测表单请求是否为post请求
 * $_SERVER['REQUEST_METHOD'] 请求方式 get post
 * 自己一定要$_SERVER打印出来
 */
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
	//说明是post方式请求
	//为什么定义常量不定义变量：常量全局作用范围
	//以后使用框架，看手册都出现IS_POST
	define ( 'IS_POST' , true );
}
else {
	define ( 'IS_POST' , false );
}

/**
 * $_SERVER['HTTP_X_REQUESTED_WITH']),
 * 想打印查看数据，需要在异步里面进行打印查看
 */
if ( isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] == 'XMLHttpRequest' ) {
	//说明是post方式请求
	//为什么定义常量不定义变量：常量全局作用范围
	//以后使用框架，看手册都出现IS_POST
	define ( 'IS_AJAX' , true );
}
else {
	define ( 'IS_AJAX' , false );
}


