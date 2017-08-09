<?php
/*
* @Author:            CIC
* @LastModify:     2017-01-12 16:47:22
* @Description:    RBAC对用户的操作
*/
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;

class UserController extends BasicController{
	public function index(){
		$arr = D("User")->getAllUser();
		$this->assign("data",$arr["data"]);
		$this->assign("page",$arr["page"]);
		$this->display();
	}
	public function addUser(){
		if(IS_POST){
			$data = I("post.");
			$data['password'] = md5($data['password']);
			$arr = D("User")->addUser($data);
			if($arr["status"]==1){
				$access["role_id"] = $this->role_id;
				$access["user_id"] = $arr["id"];
				if(D("think_role_user")->add($access)){
					$this->success("插入成功","index",3);
				}else{
					$this->error("更新access表失败");
				}
			}else{
				$this->error($arr["info"]);
			}
		}else{
			$condition['pid'] = $this->role_id;
			$condition['id'] = $this->role_id;
			$condition['_logic'] = 'OR';
			$Role = D("Role")->where($condition)->select();
			$this->assign("Role",$Role);
			$this->display();
		}
	}
	public function editUser(){
		if(IS_POST){
			$condition["id"] = I("post.id");
			$data = I("post.");
			$data['password'] = md5($data['password']);
			if(D("User")->where($condition)->save()){
				$this->success("编辑成功","index");
			}else{
				$this->error("编辑失败");
			}
		}else{
			$condition["id"] = I("get.id");
			$this->assign("user",D("User")->where($condition)->find());
			$this->display();
		}
	}
	public function deleteUser(){
		$condition['id'] = I("get.id",0,'intval');
		if(D("User")->where($condition)->delete()){
			$cond["user_id"] = I("get.id",0,'intval');
			D("think_role_user")->where($cond)->delete();
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}
}