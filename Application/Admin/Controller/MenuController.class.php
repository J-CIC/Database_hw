<?php
/*
* @Author:            CIC
* @LastModify:     2017-06-27 16:11:22
* @Description:    消息管理
*/
namespace Admin\Controller;
use Think\Controller;
use Wechat\WeixinBasic;

class MenuController extends BasicController{

    public function index(){
        $this->display();
    }
    public function editMenu(){
        $wechat = new WeixinBasic();
        if(IS_POST){
            if($_POST['json']==""){
                if($wechat->deleteMenu()){
                    $this->success("删除成功");
                }else{
                    $this->error($wechat->errMsg);
                }
            }else{
                $data = $_POST['json'];
                $data = json_decode($data);
                if($wechat->createMenu($data)){
                    $this->success("修改成功");                    
                }else{
                    $this->error($wechat->errMsg);                     
                }
            }
        }else{
            $data = $wechat->getMenu();
            $data = $data['menu']['button'];
            $this->ajaxReturn($data);
        }
    }
}