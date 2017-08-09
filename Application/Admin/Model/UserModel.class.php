<?php
/*
* @Author:            CIC
* @LastModify:     2017-01-11 21:58:53
* @Description:    UserModel，连接用户数据库
*/
namespace Admin\Model;
use Think\Model;

class UserModel extends Model {
	protected $tableName = 'think_user';

	public function update_info($data){
		return $this->save($data);
	}
	public function getAllUser(){
		$field = array("password");//排除字段
		$data = $this->field($field,true)->order("id")->select();
		return array("data"=>$data);
	}
	public function addUser($data){
		if(empty($data['username'])||empty($data['nickname'])||empty($data['email'])||empty($data['status'])||empty($data['password'])){
			$result = array("status"=>0,"info"=>"信息不全");
			return $result;
		}
		$condition['username'] = $data['username'];
		$result = $this->where($condition)->find();
		if(count($result)>0){
			$result = array("status"=>0,"info"=>"账户已存在");
			return $result;
		}
		$data['createTime'] = date("Y-m-d H:i:s" ,time());
		$id = $this->add($data);
		$result = array("status"=>1,"info"=>"success","id"=>$id);
		return $result;
	}
}