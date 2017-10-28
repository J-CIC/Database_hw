<?php
/*
* @Author:            CIC
* @LastModify:     2017-08-24 12:26:33
* @Description:    订单分析
*/
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model{
    protected $tableName = 't_order_data';

    /**
    *@param level 时间字符串分割类型，可有year,month,day,hour,minute
    * 传入错误参数或不传参数均默认为day类型
    *@param orderByTime Bool default :false
    *@return data_array
    **/
    public function getOrdersTimeDistriubution($level='day',$orderByTime=false){
        $length_arr = array("year"=>4,"month"=>6,"day"=>8,"hour"=>11,"minute"=>14);
        $length = $length_arr[$level]?$length_arr[$level]:$length_arr["day"];
        if($orderByTime===false){
            $result = $this->field('sum(a_pay_sum) as pay_sum,left(create_time,8) as times')->group('times')->order('pay_sum desc')->limit(0,10)->select();
        }else{
            $result = $this->field('sum(a_pay_sum) as pay_sum,left(create_time,8) as times')->group('times')->order('times')->select();
        }
        //SELECT SUM(a_pay_sum) as pay_sum,left(create_time,8) AS times FROM `t_order_data` GROUP BY left(create_time,8) ORDER BY pay_sum DESC
        return $result;
    }

    public function getPayStatusRatio($condition = array()){
        $result = $this->field("*, succ_num/total_num as pay_ratio")->table("(
            (
                SELECT count(*) as succ_num,goods_id,max(goods_name) as name FROM `t_order_data`,`t_order_item` 
                WHERE t_order_data.order_id=t_order_item.order_id AND t_order_data.state='SS' 
                GROUP BY t_order_item.goods_id 
                ORDER BY succ_num
            ) as succ,
            (
                SELECT count(*) as total_num,goods_id FROM `t_order_data`,`t_order_item` 
                WHERE t_order_data.order_id=t_order_item.order_id
                GROUP BY t_order_item.goods_id 
                ORDER BY total_num
            ) as total
            )")->where($condition)->where("succ.goods_id = total.goods_id")->order("pay_ratio ASC")
            ->limit(0,10)->select();
        return $result;
        // SELECT *, succ_num/total_num as pay_ratio FROM (
        // (
        //     SELECT count(*) as succ_num,goods_id,max(goods_name) FROM `t_order_data`,`t_order_item` 
        //     WHERE t_order_data.order_id=t_order_item.order_id AND t_order_data.state="SS" 
        //     GROUP BY t_order_item.goods_id 
        //     ORDER BY succ_num
        // ) as succ,
        // (
        //     SELECT count(*) as total_num,goods_id FROM `t_order_data`,`t_order_item` 
        //     WHERE t_order_data.order_id=t_order_item.order_id
        //     GROUP BY t_order_item.goods_id 
        //     ORDER BY total_num
        // ) as fail
        // )
        // where succ.goods_id = fail.goods_id
        // Order by pay_ratio ASC
        // Limit 0,10
    }
    public function getItemPayAmount($condition = array()){
        $condition['t_order_data.state']='SS' ;
        $result = $this->field("sum(s_pay_sum) as total_money,max(goods_name) as name ")->table("t_order_data , t_order_item")
            ->where($condition)->where("t_order_data.order_id=t_order_item.order_id")->group(" t_order_item.goods_id ")->order("total_money desc")
            ->limit(0,10)->select();
        return $result;
    }

    /**
    * @return 
    **/
    public function getDistinctYear(){
        $result = $this->field('left(create_time,4) as year')->group('create_time')->order('year asc')->select();
        //SELECT SUM(a_pay_sum) as pay_sum,left(create_time,8) AS times FROM `t_order_data` GROUP BY left(create_time,8) ORDER BY pay_sum DESC
        return $result;
    }


}