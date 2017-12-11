<?php

namespace houdunwang\core;
/**
 * 公共父级类
 * Class Controller
 *
 * @package houdunwang\core
 */
class Controller
{
	private $url;
	/**
	 * 消息提示
	 * @param $msg   提示消息
	 */
	public function message($msg){
		include './view/message.php';
	}

	/**
	 * 跳转连接
	 * @param string $url
	 */
	public function setRedirect($url = ''){
		if($url){
			//说明指定了跳转地址
			$this->url = "location.href='$url'";
		}else{
			//说明没有给跳转地址，默认back
			$this->url  = "window.history.back()";
		}
		return $this;
	}
}