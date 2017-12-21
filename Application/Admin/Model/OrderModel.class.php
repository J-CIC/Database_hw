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

    public function getItemCustomerTop10(){     //终端受众度top10
        $result = $this->table("t_order_item")->field("count(order_id) as customer_count,goods_name as name")
            ->group("goods_id")->order("customer_count desc")
            ->limit(0,10)->select();
        return $result;
    }

    public function getItemCustomerProv($item_name){    //终端受众度全国分布
        $subQuery1 = $this->table('id_name')->field('prov_cd,prov_nm')->group('prov_cd')->select(false);
        $subQuery2 = $this->table('t_order_data data,t_order_item item')->field('province_id')->where('data.order_id = item.order_id and item.goods_name="'.$item_name.'"')->select(false);
        $result = $this->table("(".$subQuery2.")uniondata,(".$subQuery1.")idtoname")->field('province_id,max(prov_nm) as prov_nm,count(province_id) as count')->group('province_id')->where('uniondata.province_id=idtoname.prov_cd')->order('count desc')->select();
        return $result;
    }

    public function getItemCustomerCity($item_name,$prov_nm){   //终端受众度省份分布
        $prov_cd = $this->table('id_name')->field('prov_cd')->where('prov_nm="'.$prov_nm.'"')->group('prov_nm')->select()[0]['prov_cd'];
        $subQuery1 = $this->table('t_order_data data,t_order_item item')->field('user_id,province_id,city_id')->where('data.order_id = item.order_id and goods_name="'.$item_name.'"')->select(false);
        $subQuery2 = $this->table('id_name')->field('Id_area_cd,Id_area_nm')->where('prov_cd='.$prov_cd)->group('Id_area_cd')->select(false);
        $result = $this->table("(".$subQuery1.")uniondata,(".$subQuery2.")idtoname")->field('city_id,Id_area_nm,count(city_id) as count')->group('city_id')->where('uniondata.city_id=idtoname.Id_area_cd')->order('count desc')->select();
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

    //商品销售渠道特征
    public function getOrderWay(){
        $subQuery = $this->table('t_order_data')->field('user_id,order_way')->group('order_way,user_id')->select(false);
        $result = $this->table("(".$subQuery.")temptable")->field('temptable.order_way as order_way,count(*) as count')->group('order_way')->select();
        return $result;
        //select temptable.order_way,count(*) from (SELECT user_id,order_way FROM `t_order_data` group by order_way,user_id) temptable group by order_way
    }

}