<?php
/*
* @Author:            CIC
* @LastModify:     2017-01-12 17:01:51
* @Description:    节点控制器
*/
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;

class NodeController extends BasicController{
	public function index(){
		$this->assign("Node",D("Node")->getAllNode());
		$this->display();
	}
	public function addNode(){
		if(IS_POST)
		{
			$result = D("Node")->addNode(I("post."));
			if($result["status"]){
				$this->success('插入节点成功','addNode',3);
			}else{
				$this->error($result["info"]);
			}
		}else{
			$node = D("Node")->getAllPid();
			$this->assign("node",$node);
			$this->display();
		}
	}
	public function editNode(){
		if(IS_POST){
			$data = I("post.id",0,int);
			if ($data!=0) {
				if(D("Node")->edit(I("post."))){
					$this->success("修改成功");
				}
			}else{
				$this->ajaxReturn(D("Node")->getAllNode());
			}
		}else{
			$id = I("get.id",1,'intval');
			$node = D("Node")->getAllNode();
			$pnode = D("Node")->getAllPid();
			$this->assign("node",$node);
			$this->assign("pnode",$pnode);
			$this->assign("id",$id);
			$this->display();
		}
	}
	public function getSingleNode(){
		if(IS_POST){
			$this->ajaxReturn(D("Node")->getSingleNode(I("post.id")));
		}
	}
	public function deleteNode(){
		$condition['id'] = I("get.id");
		if(D("Node")->where($condition)->delete()){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}
}