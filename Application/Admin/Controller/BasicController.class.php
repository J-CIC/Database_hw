<?php
/*
* @Author:            CIC
* @LastModify:     2017-01-11 13:08:42
* @Description:    BasicController 基础权限控制类，其余需要权限的类继承自此类
*/
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;

class BasicController extends Controller
{
	public $user_id;
	public $user_level;
	public $username;
	public $role_id;

	protected function _initialize()
	{
		if (!Rbac::checkLogin()) {
			redirect(__ROOT__ .C('USER_AUTH_GATEWAY'));
		}
		if(!Rbac::AccessDecision()){  
			$this->error("你没有对应的权限");
			die();  
		}
		$this->role_id=session("role_id");
		$this->user_id=session("authId");
		$loginInfo["loginip"] = session("loginip");
		$loginInfo["logintime"] = session("logintime");
		$loginInfo["nickname"] = session("nickname");
		$this->assign("USERINFO",$loginInfo);
		$database = D("Admin/Menu");
		$menu = $database->getMenuList($this->role_id);
		$this->assign("menu",$menu);
	}
	public function index(){
		$this->show('hello','utf-8');
	}
}