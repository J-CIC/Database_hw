<?php
/*
* @Author:            CIC
* @LastModify:     2017-01-15 14:06:21
* @Description:    NodeModel节点数据库模型
*/
namespace Admin\Model;
use Think\Model;

class NodeModel extends Model{
	protected $tableName = "think_node";

	//根据层级返回
	public function getNode(){
		$condition['level'] = 1;
		$arr = $this->table("think_node")->where($condition)->select();
		foreach ($arr as $key1 => $value) {
			$cond['pid'] = $arr[$key1]["id"];
			$cond['level'] = 2;
			$arr2 = $this->table("think_node")->where($cond)->order("id asc")->select();
			foreach ($arr2 as $key2 => $value) {
				$cond['pid'] = $arr2[$key2]["id"];
				$cond['level'] = 3;
				$arr3 = $this->table("think_node")->where($cond)->order("id asc")->select();
				$arr2[$key2]['sub'] = $arr3;
			}
			$arr[$key1]['sub'] = $arr2;
		}
		return $arr;
	}

	public function addNode($data){
		if(empty($data["name"])||empty($data["title"])||!isset($data["pid"])||empty($data["level"]))
			return array("status"=>false,"info"=>"数据中含空项");
		$data["display"] = isset($data["display"])?$data["display"]:1;
		$data["status"] = isset($data["status"])?$data["status"]:1;
		$data = array(
			"name"=>$data["name"],
			"title"=>$data["title"],
			"remark"=>$data["remark"],
			"sort"=>$data["sort"],
			"pid"=>$data["pid"],
			"level"=>$data["level"],
			"status"=>$data["status"],
			"display"=>$data["display"],
		);
		if($id = $this->table("think_node")->add($data)){
			//默认添加原始管理员的权限
			$access = array(
				"role_id"=>1,
				"node_id"=>$id,
				"level"=>$data["level"], 
				"module"=>$data["name"],
			);
			if(M("think_access")->add($access))
				return array("status"=>true);
			// echo M("think_access")->fetchSql(true)->add($access);
			return array("status"=>false,"info"=>"更新access表失败");
		}else{
			print_r($this);
			return array("status"=>false,"info"=>"节点插入失败");
		}
	}

	public function getAllPid(){
		$condition['level'] = array("LT",3);
		return $this->where($condition)->select();
	}

	public function getAllNode(){
		return $this->order("id")->select();
	}
	public function getSingleNode($id){
		$con['id'] = $id;
		return $this->where($con)->find();
	}
	public function edit($data){
		if($this->save($data))
			return array("status"=>true);
		return array("status"=>false,"info"=>"更新失败");
	}
}
?>