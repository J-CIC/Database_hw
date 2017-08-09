<?php
/*
* @Author:            CIC
* @LastModify:     2017-06-23 16:18:33
* @Description:    权限管理
*/
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model{
	protected $tableName = 'think_role';

	public function getAllRole(){
		return $this->select();
	}

	public function addRole($data){
		if(empty($data["name"])||empty($data["pid"]))
			return array("status"=>false,"info"=>"数据中含空项");
        if($this->add($data)){
            return array("status"=>true,"info"=>"插入角色组成功");
        }else{
            return array("status"=>false,"info"=>"插入失败");
        }
	}
}