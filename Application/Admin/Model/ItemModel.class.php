<?php

namespace Admin\Model;
use Think\Model;
class ItemModel extends Model{

	protected $tableName = 't_order_data';

	public function getDataByLocation($province_id=-1){

		if($province_id==-1){
			$subQuery = $this->table('t_order_data data,t_order_item item')->field('user_id,province_id')->where('data.order_id = item.order_id')->select(false);
			$result = $this->table("(".$subQuery.")uinondata")->field('province_id,count(province_id) as count')->group('province_id')->order('count desc')->select();

			//select province_id,count(province_id) from (select user_id,province_id from (select order_id,user_id from t_order_data)data,(select order_id,province_id from t_order_item)item where data.order_id = item.order_id)uinondata group by province_id order by count(province_id) desc limit 10
		}else{
			$subQuery = $this->table('t_order_data data,t_order_item item')->field('user_id,province_id,city_id')->where('data.order_id = item.order_id')->select(false);
			$result = $this->table("(".$subQuery.")uinondata")->field('city_id,count(city_id) as count')->group('city_id')->where('province_id='.$province_id)->order('count desc')->select();

			//select city_id,count(city_id) from (select user_id,province_id,city_id from (select order_id,user_id from t_order_data)data,(select order_id,province_id,city_id from t_order_item)item where data.order_id = item.order_id)uinondata where province_id = 100 group by city_id order by count(city_id) desc limit 10
		}

		return $result;
	}

}
