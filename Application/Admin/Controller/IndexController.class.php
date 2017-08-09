<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;

class IndexController extends BasicController {
	public function index(){
		$page["title"] = "微信后台管理系统";
		$this->assign('page',$page);
		$this->assign('statistics',$statistics);
		$this->display();
	}
}