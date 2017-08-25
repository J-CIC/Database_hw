<?php
/*
* @Author:         CIC
* @LastModify:     2017-06-23 18:17:22
* @Description:    UI界面
*/
namespace Admin\Controller;
use Think\Controller;
use Org\Util\Rbac;
use Org\Util\Page;

class DataDisplayController extends BasicController{
	public function allData(){

		if(IS_POST){
			$date = I('post.date', Null, 'htmlspecialchars');
			$location = I('post.location', Null, 'htmlspecialchars');
			$date_info = explode("-", $date);

			$map["year"] = $date_info[0];
			$map["month"] = $date_info[1];		
		}else{
			$p = I('get.p', Null, 'htmlspecialchars');
			if($p == Null){
				$this->display();
				return;
			}
		}

		$Data = M('t_order_data'); // 实例化Data数据对象  data 是你的表名

	    import('Think.Page');// 导入分页类
	    $count = $Data->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
	    $Page = new Page($count,10);// 实例化分页类 传入总记录数
	    $Page -> setConfig('header', '<div class="page-info">每页<b>10</b>条&nbsp;共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</div>');
	    $Page -> setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');//(对thinkphp自带分页的格式进行自定义)

	    $show = $Page->show();// 分页显示输出
	    // 进行分页数据查询 

	    $result['info'] = 1;
	    if($date_info[1] == '05' || $date_info[1] == '06'){
	    	$result['name'] = "五一七商品销售";
	    	$stage = ($date_info[1] == '05')?array("all" => "全部", "first" => "一阶段(5.1 - 5.15)", "second" => "二阶段(5.16 - 5.22)", "third" => "三阶段(5.23 - 5.31)"):array("all" => "全部", "fourth" => "四阶段(6.1 - 6.15)", "fifth" => "五阶段(6.16 - 6.30)");
	    	$result["stage"] = $stage;
	    }else{
	    	$result['name'] = "双十一商品销售";
	    	$stage = array("all" => "", "first" => "(5.1 - 5.15)", "second" => "(5.16 - 5.22)", "third" => "(5.23 - 5.31)");
	    	$result["stage"] = $stage;
	    }
	    $result['data'] = $Data->where($map)->limit($Page->firstRow.','.$Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条

	    $this->assign('result',$result);// 赋值数据集
	    $this->assign('page',$show);// 赋值分页输出
	    $this->display('allData'); // 输出模板
	}
	public function OrderTimeDistribution(){
		if(IS_POST){
			$res = D("Order")->getOrdersTimeDistriubution("day");
			$res2 = D("Order")->getOrdersTimeDistriubution("day",true);
			$arr = array("ranks"=>$res,"times"=>$res2);
			$this->ajaxReturn($arr);
		}else{			
			$this->display("ordertime");
		}
	}
}