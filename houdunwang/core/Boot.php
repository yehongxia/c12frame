<?php

namespace houdunwang\core;

use app\home\controller\Index;

/**
 * 启动类
 * Class Boot
 *
 * @package houdunwang\core
 */
class Boot
{
	public static function run ()
	{
		//处理错误
		self::handler();
		/************************第一步测试:程序能否正常运行到这里*********************************/
		//1.这个echo 是用来协助单一入口测试Boot.php是否可以通过composer自动加载
		//echo 'run';
		/************************第二步测试:加载助手函数*********************************/
		//2.程序正常运行到这里，处理助手函数的加载
		//将助手函数放在system/helper中
		//p(1);//这个时候刷新页面会报错：houdunwang\core\p() 找不到
		//解决办法：composer自动加载文件：修改conposer配置文件：autoload里面files:system/helper.php
		//p(1);//再次刷新页面,这个时候还报错
		//需要早终端执行composer dump
		//p(1);//再测试，页面正常加载，并且有p样式了
		//补充：函数默认调用当前空间的函数，如果当前空间没有，那么会往跟空间找该函数
		/************************第三步*********************************/
		//1.执行初始化的动作
		self ::init ();
		//执行应用
		self::appRun();
	}
	//错误异常处理
	public static function handler(){
		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
		$whoops->register();
	}
	/**
	 * 运行应用
	 */
	public static function appRun(){
		/************************第四步*********************************/
		//2.执行应用(运行app里面类库)
		//首先app/home/controller/创建一个类文件Index.php
		//然后测试Index.php类是否能加载到
		//这里注意使用user导入命名空间(在该文件最上面)
		//这个时候并不能实力化到类，会报错
		//修改composer配置文件，app增加到psr-4里面，然后执行composer dump
		//(new Index())->index ();
		//这里创建app/home/controller，创建两个类
		//这里创建app/member/controller，创建1个类
		//用来作测试用
		//(new \app\home\controller\Index())->index ();
		//(new \app\home\controller\Article())->index ();
		//(new \app\member\controller\Article())->index ();

		//通过get参数来控制访问的模块、控制器类、方法：?c=Index&a=index&m=home
		//这里get参数样子换种写法：?s=模块/控制器/方法(?s=home/Index/index),我们按照这种方式来处理
		if ( isset( $_GET[ 's' ] ) ) {
			//这个时候地址栏测试地址：?s=home/article/index
			$s = $_GET[ 's' ];//home/article/index
			//p($_GET['s']);die;
			//将$s转为数组
			$info = explode ( '/' , $s );
			//p($info);die;
			$m = $info[ 0 ];//模块
			$c = ucfirst ( $info[ 1 ] );//控制器类,首字母大写，因为他是类名字
			$a = $info[ 2 ];//方法
		}else {
			//不存在参数的时候给默认值
			$m = 'home';//模块
			$c = 'Index';//控制器类
			$a = 'index';//方法
		}
		//定义常量,为了在后面是使用的时候比较方便，以为define定义的常量可以不受命名空间限制
		define('MODULE',$m);
		define('CONTROLLER',$c);
		define('ACTION',$a);
		$controller = "\app\\{$m}\controller\\{$c}";
		//( new $controller ) -> $a ();
		//下面这句话，就详单与上面这句
		//new $controller这个类，调用$a,并且把该函数的第二个参数作为$a方法的参数
		echo call_user_func_array ( [ new $controller , $a ] , [] );
		//接下来，我们构建MVC中的C就是controler
		//接下来在app/home/controller/Index.php文件中进行测试
	}
	/**
	 * 初始化框架
	 */
	public static function init ()
	{
		//1.头部
		header ( 'Content-type:text/html;charset=utf8' );
		//2.设置时区
		date_default_timezone_set ( 'PRC' );
		//3.开启session
		//如果已经有session_id()说明session开启过了
		//如果没有session_id，则再开启session
		//重复开启session，会导致报错
		session_id () || session_start ();
	}
}