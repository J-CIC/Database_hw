<?php
/*
* @Author:            CIC
* @LastModify:     2017-06-25 16:18:33
* @Description:    权限管理
*/
namespace Admin\Model;
use Think\Model;
class ProvinceModel extends Model{
	protected $tableName = 'province_access';

	public function getProvince($id){
        $condition['role_id'] = $id;
		return $this->where($condition)->select();
	}

	public function getAllProvince(){
		return $this->table("province")->order("id asc")->select();
	}

	public function getSingleProvince($id){
		$condition["id"] = $id;
		return $this->table("province")->where($condition)->find();		
	}
}