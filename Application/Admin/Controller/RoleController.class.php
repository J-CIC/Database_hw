<?php
/*
* @Author:            CIC
* @LastModify:     2017-06-23 16:17:22
* @Description:    RBAC对权限的操作
*/
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;

class RoleController extends BasicController{
	public function index(){
		$arr = D("Role")->getAllRole();
        $this->assign("Role",$arr);
		$this->display();
	}
    public function editRole(){
        if(IS_POST){
            if(D("Role")->save(I("post."))){
                $this->success("更新成功");
            }else{
                $this->error("更新失败");
            }
        }else{
            $condition["id"] = I("get.id",1,'intval');
            $result = D("Role")->where($condition)->find();
            $arr = D("Role")->getAllRole();
            $this->assign("Roles",$arr);
            $this->assign("Role",$result);
            $this->assign("id",I("get.id"));
            $this->display();
        }
        
        
    }
    public function addRole(){
        if(IS_POST){
            $result = D("Role")->add(I("post."));
            if($result['status']){
                $this->success("插入角色组成功");
            }else{
                $this->error($result["info"]);
            }
        }else{
            $arr = D("Role")->getAllRole();
            $this->assign("Role",$arr);
            $this->display();
        }
    }
    public function deleteRole(){
		$condition['id'] = I("get.id");
		if(D("Role")->where($condition)->delete()){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
    }
    public function getSingleRole(){
        $condition["id"] = I("post.id",1,'intval');
        $this->ajaxReturn(D("Role")->where($condition)->find());
    }
    public function editAccess(){
        if(IS_POST){
            $data = array();
            $input = I("post.Access");
            $input []= "1";
            foreach ($input as $key => $value) {
                $condition["id"] = $value;
                $temp = D("Node")->where($condition)->find();
                $arr = array("role_id"=>I("post.id",1,'intval'),"node_id"=>$value,"level"=>$temp["level"],"module"=>$temp["name"]);
                $data []= $arr;
            }
            $cond["role_id"] = I("post.id",1,'intval');
            if((D("Access")->where($cond)->delete())!==false){
                if(D("Access")->addAll($data)){
                    $this->success("更改成功");
                }else{
                    $this->error("更改失败");
                }
            }else{
                $this->error("更改失败");
            }
        }else{
            $id = I("get.id",1,'intval');
            $arr = D("Role")->getAllRole();
            $Access = D("Access")->getAccessList($this->role_id);
            $this->assign("Access",$Access);
            $this->assign("Roles",$arr);
            $this->assign("id",$id);
            $this->display();
        }
    }
    public function getRoleAccess(){
        $result = array("Access"=>D("Access")->getAccess(I("post.id")));
        $this->ajaxReturn($result);
    }
}