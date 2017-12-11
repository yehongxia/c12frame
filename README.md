构建属于自己的框架：php
=======================

目的
---
*   熟悉什么是框架
*   熟悉框架的核心运行原理
*   熟悉与掌握框架的使用

要求
---
*   框架的运行原理以及全部流程
*   每一行代码需要加上注释
*   不要求默打，这个难度比较大，尽量做到


需要使用的知识点
--------------
*   php
*   mysql
*   composer`项目提交composer的packagist`
*   git简单知识`项目提交至github`

准备工作
-------
github注册账号<br>
创建一个项目<br>
克隆下来到www目录<br>
-------------------
安装composer

实现步骤
-------
###1.本地创建框架的目录，使用`composer init` 初始化项目
```
    composer init初始化之后会自动声场vendor目录以及composer.json文件
```
###2.构建框架文件以及目录(目录名全部小写规范)
```
|--app（开发者写代码的地方）
|    |--home（前台模块）
|    |    |--controller(控制器类)
|    |    |--view(视图)
|--houdunwang（系统核心）
|    |--core
|    |--model
|    |--view
|--public(入口、静态资源)
|    |--static(静态资源)
|    |--view（公共模板文件）
|--system(配置)
|    |--config (配置项)
|    |--model （处理业务的各种模型类）
```
```
MVC
M---model
V--view
C--controller
```
###3.创建框架的启动类houdunwang\core\Boot.php类`类名和文件名首字母大写`
```
<?php
//命名空间
namespace houdunwang\core;
class Boot
{
    public static function run ()
        {
        echo 'run';
    }
}
```
```
<?php

namespace houdunwang\core;

class Boot
{
	public static function run ()
	{
		//错误处理
		self::handler();
		//初始化框架
		self::init();
		//执行应用
		self::appRun ();
	}

	public static function handler(){
		$whoops = new \Whoops\Run;
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
		$whoops->register();
	}
	public static function init()
	{
		session_id () || session_start ();
		date_default_timezone_set ('PRC');
	}
	public static function appRun ()
	{
		if ( isset( $_GET[ 's' ] ) ) {
			$info = explode ('/',$_GET['s']);
			$info[1] = ucfirst ($info[1]);
			$class = "\app\\{$info[0]}\controller\\{$info[1]}";
			$action = $info[2];

			define ('MODULE',strtolower ($info[0]));
			define ('CONTROLLER',lcfirst ($info[1]));
			define ('ACTION',$info[2]);
		} else {
			$class = "\app\home\controller\Entry";
			$action = "index";

			define ('MODULE','home');
			define ('CONTROLLER','entry');
			define ('ACTION',$action);
		}
		echo call_user_func_array ( [ new $class , $action ] , [] );
		exit;
	}
}
```
###4.在public目录中创建index.php单一入口文件
```
<?php
require_once '../vendor/autoload.php';
\houdunwang\core\Boot::run ();
```
###5.访问框架入口：http://localhost:8080/frame/public/
这个时候报错：
```
Fatal error: Class 'houdunwang\core\Boot' not found in /Applications/MAMP/htdocs/frame/public/index.php on line 3
```
因为类加载使用时需满足：include加载文件和导入命名空间<br>
继续往下解决这个问题
###6.配置composer配置文件
修改composer.json文件。注意`autoload`该项需自己增加
```
{
    "name": "chaorenwubin/frame",
    "description": "后盾网php框架系统",
    "type": "project",
    "licence":"MIT",
    "authors": [
        {
            "name": "武斌",
            "email": "wubin.mail@foxmail.com"
        }
    ],
    "minimum-stability": "dev",
    "require": {},
    "autoload":{
        "files":[],
        "psr-4":{
           "houdunwang\\":"houdunwang\\",
           "app\\":"app\\"
        }
    }
}
```
PSR-4
```
1.是制定的代码规范，简称PSR，是代码开发的事实标准
2.PSR-4使代码更加规范，能够满足面向package的自动加载，它规范了如何从文件路径自动加载类，同时规范了自动加载文件的位置
3.如果是： "houdunwang\\":"houdunwang\\"，当实例化\houdunwang\core\Boot类时，会加载houdunwang/core/Boot.php
4.如果是： "houdunwang\\":"hdphp\\"，当实例化\houdunwang\core\Boot类时，hdphp/core/Boot.php
5.如果是： "houdunwang\\":""，当实例化\houdunwang\core\Boot类时，Boot.php
5.反斜线是转义
```
生成composer自动载入文件,在Terminal中进入项目跟目录执行：
```
composer dump
```
这时，访问项目入口public文件输出`run`
###7.Boot.php中书写appRun方法：
引入助手函数
```
1.将以前我们写的helper.php文件放入system/helper.php
2.修改composer.json文件中autoload项的files
"autoload":{
        "files":[
            "system/helper.php"
        ],
        "psr-4":{
           "houdunwang\\":"houdunwang\\",
           "app\\":"app\\"
        }
}
3.在Teminal终端执行composer dump
```
appRun方法具体代码
```
public static function appRun ()
	{
		if(isset($_GET['s'])){
			//通过?s=模块/控制器/方法(?s=home/entry/index)进行访问
			//接受get参数s，并将其转为数组
			$info = explode ('/',$_GET['s']);
			//dd($info);
			$info[1] = ucfirst ($info[1]);
			//dd($info);
			$c = "app\\{$info[0]}\controller\\{$info[1]}";
			$a  = $info[2];
			define ('MODULE',strtolower ($info[0]));
			define ('CONTROLLER',strtolower ($c));
			define ('ACTION',$a);
		}else{
			$c = "\app\home\controller\\entry";//控制器类
			$a = 'index';//方法
			define ('MODULE','home');
			define ('CONTROLLER','entry');
			define ('ACTION',$a);
		}
		return call_user_func_array ([new $c,$a],[]);
	}
```
###8.测试访问app中我们构建的程序
在app/home/controller中创建Entry.php
```
<?php

namespace app\home\controller;

class Entry
{
	public function index(){
		echo 'Welcome';
	}
}
```
通过地址栏访问`public`或`public/index.php`或`public/index.php?s=home/entry/index`<br>
也可通过尝试其他地址访问，如：`public/index.php?s=home/entry/store`<br>
浏览器正常输出`Welcome`
###9.构建控制器层 C
public/view放入message.php模板文件<br>
```
自己参考代码
```
在 core 目录中创建Controller.php,并让app\home\controller\Entry.php及以后类继承Controller
```
<?php

namespace houdunwang\core;

class Controller
{
	private $url;

	protected function message($msg){
		include './view/message.php';
		exit;
	}

	protected function setRedirect($url = ''){
		if($url){
			$this->url = "location.href='$url'";
		}else{
			$this->url = "window.history.back()";
		}
		return $this
	}
}
```
app/controller/Entry.php,中增加store方法进行测试,增加store方法进行测试<br>
通过地址栏访问store方法：`public/index.php?s=home/entry/store`<br>
可看到提示页面，3秒之后自动跳转
```
<?php

namespace app\home\controller;

use houdunwang\core\Controller;

class Entry extends Controller
{
	public function index(){
		echo 'Welcome';
	}

	public function store(){
		$this->setRedirect ('?s=home/entry/index')->message ('添加成功');
	}
}
```
###10.构建视图层 V
在core/view目录中建立View.php和Base.php<br>
View.php
```
<?php

namespace houdunwang\view;

class View
{
	public function __call ( $name , $arguments )
	{
		// TODO: Implement __call() method.
		return self::parseAction ( $name , $arguments );
	}

	public static function __callStatic ( $name , $arguments )
	{
		// TODO: Implement __callStatic() method.
		return self::parseAction ( $name , $arguments );
	}

	public static function parseAction ( $name , $arguments )
	{
		return call_user_func_array ( [ new Base , $name ] , $arguments );
	}
}
```
Base.php
```
<?php

namespace houdunwang\view;

class Base
{
	private $file;
	private $data;

	public function make ()
	{
		
		$this->file = "../app/" . MODULE . '/view/' . CONTROLLER . '/' . ACTION . '.php';

		return $this;         
	}

	public function with ( $var )
	{
		$this->data = $var;

		return $this;
	}
	
	//当输出对象的时候出发，比如说echo $obj->make();
	public function __toString ()
	{
		// TODO: Implement __toString() method.
		extract ($this->data);
		include $this->file;
		return '';
	}
}
```
###11构建模型层和数据库ORM数据对象关系映射
数据库配置项文件`system/config/database.php`
```
<?php
return [
	'driver'=>'mysql',
	'host'     => '127.0.0.1',
	'user'     => 'root',
	'password' => 'root',
	'dbname'     => '',
];
```
system/helper.php助手函数中增加加载配置项的c函数
```
function c ( $path )
	{
		$info   = explode ( '.' , $path );
		$config = include "../system/config/" . $info[ 0 ] . '.php';

		return isset( $config[ $info[ 1 ] ] ) ? $config[ $info[ 1 ] ] : null;

	}
```
`houdunwang/model/Model.php`
```
<?php

namespace houdunwang\model;


class Model
{
	/**
	 * @param $name
	 * @param $arguments
	 */
	public function __call ( $name , $arguments )
	{
		// TODO: Implement __call() method.
		return self::parseAction ( $name , $arguments );
	}

	/**
	 * @param $name
	 * @param $arguments
	 */
	public static function __callStatic ( $name , $arguments )
	{
		// TODO: Implement __callStatic() method.
		return self::parseAction ( $name , $arguments );
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 */
	public static function parseAction ( $name , $arguments )
	{
		$class = get_called_class ();
		return call_user_func_array ( [ new Base($class) , $name ] , $arguments );
	}
}
```
base.php
```
<?php

namespace houdunwang\model;

use PDO;
use PDOException;
use Exception;

/**
 * 模型基础类
 * Class Base
 *
 * @package houdunwang\model
 */
class Base
{
	protected $pdo;

	public function __construct ( $class )
	{
		//1.链接数据库
		$this->connect ();
		//$class打印结果：system\model\Article
		$class       = explode ( '\\' , $class );
		$this->table = strtolower ( $class[ 2 ] );
	}

	private function connect ()
	{
		$dsn      = c ( 'database.driver' ) . ":host=" . c ( 'database.host' ) . ";dbname=" . c ( 'database.dbname' );
		$user     = c ( 'database.user' );
		$password = c ( 'database.password' );
		try {
			$this->pdo = new PDO( $dsn , $user , $password );//链接数据库
			$this->pdo->query ( 'SET NAMES UTF8' );//设置字符集
			//设置错误属性
			$this->pdo->setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			//输出错误
			exit( $e->getMessage () );
		}
	}


	

	/**
	 * 执行有结果集的查询
	 *
	 * @param $sql  查询的sql语句
	 *
	 * @return mixed  查询结果结
	 */
	public function query ( $sql )
	{
		try {
			$res = $this->pdo->query ( $sql );

			return $res->fetchAll ( PDO::FETCH_ASSOC );
		} catch ( PDOException $e ) {
			throw new Exception( "sql:{$sql} | " . $e->getMessage () );
		}
	}

	public function exec ( $sql )
	{
		try {
			$affectedRows = $this->pdo->exec ( $sql );
			if ( $lastInsertId = $this->pdo->lastInsertId () ) {
				//返回自增id
				return $lastInsertId;
			} else {//否则返回受影响的条数
				return $affectedRows;
			}
		} catch ( PDOException $e ) {
			throw new Exception( "sql:{$sql} | " . $e->getMessage () );
		}
	}
}
```
需要在system/model建立Article.php进行协助测试测试，Article.php名字不是固定的
```
<?php

namespace system\model;


use houdunwang\model\Model;

class Article extends Model
{
	
}
```
在Entry.php中进行测试，注意system中的类不会自动进行加载，需要修改composer.json配置项
```
"autoload":{
        "files":[
            "system/helper.php"
        ],
        "psr-4":{
            "houdunwang\\":"houdunwang",
            "app\\":"app\\",
            "system\\":"system\\"
        }
    }
```
然后执行
```
composer dump
```
###错误处理方法
composer的packagist(https://packagist.org/)搜索：`whoop`<br>
选择第一个：filp/whoops<br>
进入到项目根目录执行：
```
composer require filp/whoops
```
在houdunwang/core/Boot.php中加入
```
    $whoops = new \Whoops\Run;
   $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
   $whoops->register();
```
推送上到composer仓库之后即可以下载了
```
composer create-project chaorenwubin/frame frame dev-master --prefer-dist
```












