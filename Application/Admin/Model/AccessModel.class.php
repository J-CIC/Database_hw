<?php
/*
* @Author:            CIC
* @LastModify:     2017-06-25 16:18:33
* @Description:    权限管理
*/
namespace Admin\Model;
use Think\Model;
class AccessModel extends Model{
	protected $tableName = 'think_access';

	public function getAccess($id,$level = 1){
        $condition['role_id'] = $id;
        $condition['level'] = array("GT",$level);
		return $this->where($condition)->select();
	}

    public function getAccessList($roleid){
		// dump($roleid);
		$condition['access.role_id'] = $roleid;
		$condition['node.status'] = 1;
		$condition['node.level'] = array("EQ",2);
		$result = $this->table('think_node node, think_access access')->where($condition)->where("node.id = access.node_id")->field('node.id as id, node.name as name, node.title as title, node.remark as remark,node.level as level')->order('node.level desc' )->select();
		foreach ($result as $key => &$value) {
			$value["sub"] = $this->getSubAccess($roleid,intval($value["id"]));
		}
		return $result;
	}

    public function getSubAccess($roleid,$menuid){
		$condition['access.role_id'] = $roleid;
		$condition['node.status'] = 1;
		$condition['node.pid'] = $menuid;
		$condition['node.level'] = array("EQ",3);
		$result = $this->table('think_node node, think_access access')->where($condition)->where("node.id = access.node_id")->field('node.id as id,node.name as name,node.title as title, node.remark as remark,node.level as level')->order('node.level desc' )->select();
		return $result;
	}

	public function editRole($data){
		if(empty($data["name"])||empty($data["pid"]))
			return array("status"=>false,"info"=>"数据中含空项");
        if($this->add($data)){
            return array("status"=>true,"info"=>"插入角色组成功");
        }else{
            return array("status"=>false,"info"=>"插入失败");
        }
	}
}