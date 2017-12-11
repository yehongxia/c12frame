<?php
/**
 * composer初始化一个项目：composer init
 * 框架单一入口文件
 * 加载类库满足两个条件：1.include 2.use导入命名空间
 * //生成vendor目录执行命令：composer dump
 */

//加载vendor/autoload.php
require "../vendor/autoload.php";
//注意：写完上面一句刷新页面类还是未找到
//这个时候需要修改composer配置文件composer.json
//手动加入autoload这一项
//autoload里面有两个元素：files--->自动加载文件
//autoload里面有两个元素：psr-4
//在你项目的根目录下执行：composer dump
//这个时候再刷新页面，Boot类就找到了
//"psr-4":{
	//当实例化\houdunwang\core\Boot命名空间类时候，去houduwnang目录加载该类
//	"houdunwang\\":"houdunwang\\"
//}
//调用启动类中run方法
\houdunwang\core\Boot::run ();
//这时候，单入口工作完成了，该去houdunwang/core/Boot.php工作了.....

