<?php

namespace Admin\Model;
use Think\Model;
class ItemModel extends Model{

	protected $tableName = 't_order_data';

	public function getDataByLocation($province_id=-1){

		if($province_id==-1){
			$subQuery1 = $this->table('id_name')->field('prov_cd,prov_nm')->group('prov_cd')->select(false);
			$subQuery2 = $this->table('t_order_data data,t_order_item item')->field('user_id,province_id')->where('data.order_id = item.order_id')->select(false);
			$result = $this->table("(".$subQuery2.")uniondata,(".$subQuery1.")idtoname")->field('province_id,prov_nm,count(province_id) as count')->group('province_id')->where('uniondata.province_id=idtoname.prov_cd')->order('count desc')->select();	

			//select province_id,count(province_id) from (select user_id,province_id from (select order_id,user_id from t_order_data)data,(select order_id,province_id from t_order_item)item where data.order_id = item.order_id)uniondata group by province_id order by count(province_id) desc limit 10
		}else{
			$subQuery1 = $this->table('t_order_data data,t_order_item item')->field('user_id,province_id,city_id')->where('data.order_id = item.order_id')->select(false);
			$subQuery2 = $this->table('id_name')->field('Id_area_cd,Id_area_nm')->where('prov_cd='.$province_id)->group('Id_area_cd')->select(false);
			$result = $this->table("(".$subQuery1.")uniondata,(".$subQuery2.")idtoname")->field('city_id,Id_area_nm,count(city_id) as count')->group('city_id')->where('uniondata.city_id=idtoname.Id_area_cd')->order('count desc')->select();

			//select city_id,count(city_id) from (select user_id,province_id,city_id from (select order_id,user_id from t_order_data)data,(select order_id,province_id,city_id from t_order_item)item where data.order_id = item.order_id)uniondata where province_id = 100 group by city_id order by count(city_id) desc limit 10
		}

		return $result;
	}

}
