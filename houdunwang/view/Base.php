<?php
namespace houdunwang\view;

class Base{
	private $data = [];//存储变量
	private $file = '';//模板文件
	/**
	 * 显示模板文件
	 */
	public function make(){
		//p(MODULE);
		//p(CONTROLLER);
		//p(ACTION);
		//include '../app/home/view/index/index.html';
		//include '../app/'.MODULE.'/view/'.strtolower (CONTROLLER).'/'.ACTION.'.php';
		$this->file =  '../app/'.MODULE.'/view/'.strtolower (CONTROLLER).'/'.ACTION.'.' . c('view.suffix');
		return $this;
	}

	/**
	 * 分配变量，
	 */
	public function with($var = []){
		//p($var);die;
		$this->data = $var;
		return $this;
	}

	public function __toString ()
	{
		//p($this->data);die;
		//将键名变为变量名字，将键值变为变量值
		extract ($this->data);
		//经过extract之后，就会产生变量
		//产生变量名叫什么：看调用With时候给的变量名字是什么
		//p($data);
		//p($a);
		//die;
		//加载模板文件
		//为了防止调用时候只调用with，不调用make出现的报错
		//你在调用时候View::with(),就会出现报错
		if($this->file){
			include $this->file;
		}
		return '';
	}
}
