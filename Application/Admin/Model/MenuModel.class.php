<?php
/*
* @Author:            CIC
* @LastModify:     2017-01-12 18:35:33
* @Description:    获取菜单
*/
namespace Admin\Model;
use Think\Model;
class MenuModel extends Model{
	protected $tableName = 'think_node';

	public function getMenuList($roleid){
		// dump($roleid);
		$condition['access.role_id'] = $roleid;
		$condition['node.status'] = 1;
		$condition["node.display"] = 1;
		$condition['node.level'] = array("EQ",2);
		$result = $this->table('think_node node, think_access access')->where($condition)->where("node.id = access.node_id")->field('node.id as id, node.name as name, node.title as title, node.remark as remark,node.level as level')->order('node.level desc,node.sort desc' )->select();
		foreach ($result as $key => &$value) {
			$value["sub"] = $this->getSubMenu($roleid,intval($value["id"]));
		}
		return $result;
	}

	public function getSubMenu($roleid,$menuid){
		$condition['access.role_id'] = $roleid;
		$condition['node.status'] = 1;
		$condition["node.display"] = 1;
		$condition['node.pid'] = $menuid;
		$condition['node.level'] = array("EQ",3);
		$result = $this->table('think_node node, think_access access')->where($condition)->where("node.id = access.node_id")->field('node.id as id,node.name as name,node.title as title, node.remark as remark,node.level as level')->order('node.level desc,node.sort desc' )->select();
		return $result;
	}
}