<?php
/*
* @Author:         CIC
* @LastModify:     2017-06-23 18:17:22
* @Description:    UI界面
*/
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;

class UIController extends BasicController{
	public function icons(){
		$this->display();
	}
}