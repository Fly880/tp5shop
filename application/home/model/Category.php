<?php
namespace app\home\model;
use think\Model;

class Category extends Model{
	protected $pk = 'cat_id';
	//商品类型数据
	public function getNavData($limit){
		return $this->where('is_show','=','1')->limit($limit)->select();
	}

	//商品分类
	public function getFamilysCat($data,$cat_id)
	{
		static $result = [];
		foreach ($data as $k => $v) {
			//第一次循环,先找自己
			if ($v['cat_id'] == $cat_id) {
				$result[] = $v;
				//删除已经判断过的分类
				unset($data[$k]);
				//递归调用
				$this->getFamilysCat($data,$v['pid']);
			}
		}
		//返回结果 ,数组反转 为了导航的位置正确输出
		return array_reverse($result);
	}
     //分类下的子孙分类
	public function getSonsCatId($data,$cat_id)
	{	static $sonsId = [];
		foreach($data as $k=>$v){
			if($v['pid'] == $cat_id){
				$sonsId[] = $v['cat_id']; //只存储cat_id即可
				unset( $data[ $k] );
				//递归调用
				$this->getSonsCatId($data,$v['cat_id']);
			}
		}
		return $sonsId;
	}
}