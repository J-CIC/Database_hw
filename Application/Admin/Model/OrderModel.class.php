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
            $result = $this->field('sum(a_pay_sum) as pay_sum,left(create_time,8) as times')->group('times')->order('pay_sum desc')->limit(0,20)->select();
        }else{
            $result = $this->field('sum(a_pay_sum) as pay_sum,left(create_time,8) as times')->group('times')->order('times')->select();
        }
        //SELECT SUM(a_pay_sum) as pay_sum,left(create_time,8) AS times FROM `t_order_data` GROUP BY left(create_time,8) ORDER BY pay_sum DESC
        return $result;
    }

}