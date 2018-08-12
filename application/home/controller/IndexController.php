<?php
namespace app\home\controller;
use app\home\model\Category;
use think\Controller;
use app\home\model\Goods;
class IndexController extends Controller{
	public function index(){
     //展示导航栏
    $categoryModel =  new Category();
		$navDatas = $categoryModel->getNavData(5);
		//取出首页的三级分类筛选的数据
		$oldCat = Category::select();
		//两个技巧
		//1、以每个分类的cat_id主键作为cats二维数组的下标
		$cats = [];
		foreach($oldCat as $cat){
			$cats[ $cat['cat_id'] ] = $cat;
		}
		//2、根据pid进行分组
		$children = [];
		foreach($oldCat as $cat){
			$children[ $cat['pid'] ][] = $cat['cat_id'];
		}

		//取出前台推荐位的商品
		$goodsModel = new Goods();
		$crazyDatas = $goodsModel->getGoods('is_crazy',5);
		$hotDatas = $goodsModel->getGoods('is_hot',5);
		$bestDatas = $goodsModel->getGoods('is_best',5);
		$newDatas = $goodsModel->getGoods('is_new',5);

		return $this->fetch('',[
			'navDatas'=>$navDatas,
			'cats'=>$cats,
			'children'=>$children,
			'crazyDatas'=>$crazyDatas,
			'hotDatas'=>$hotDatas,
			'bestDatas'=>$bestDatas,
			'newDatas'=>$newDatas
		]);
    }
}