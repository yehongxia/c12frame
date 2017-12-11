<?php

namespace app\home\controller;

use houdunwang\core\Controller;
use system\model\Student;

class Index extends Controller {
	public function index(){

			//获取学生表中id(主键)=1数据
			//$data = Student::find(1);
			//p($data);
			//获取学生表中id(主键)=1的年龄与姓名字段数据
			//$data = Student::field('age,sname')->find(1);
			//p($data);

			//根据其余字段(不是主键)查找某一条数据
			//$data = Student::where("sname='赵虎'")->first();
			//p($data);

			//获取数据表所有数据
			//$res = Student::getAll();
			//p($res);

			//查找年龄>30的同学
			//$data = Student::where("age>30 or sex='男'")->getAll();
			//p($data);

			//查询指定列
			//$data = Student::where('age>30')->field("sname,sex")->getAll();
			//p($data);

			//排序封装
			//	$data="select*from student where age>30 order by age desc";
			$data = Student::where('age>30')->order('age,desc')->getAll();
			p($data);

		//insert写入数据
		//id不用写
		//结构保持与数据库一样
		//$data = [
		//	'sname'=>'艾丽丝',
		//	'age'=>20,
		//	'sex'=>'女',
		//	'cid'=>1,
		//];
		//$res = Student::insert($data);
		//	p($res);
		//修改数据
		//$data = [
		//	'sname'=>'王朝修改',
		//	'age'=>28,
		//	'sex'=>'男',
		//];
		//$res = Student::where('id=1')->update($data);
		//p($res);

		//删除数据
		//$res = Student::where('id=10')->delete();
		//	p($res);
	}


	public function add(){
		View::make();
	}


}