<?php
namespace app\admin\controller;
use app\admin\model\Category;

class CategoryController extends CommonController{
	   //商品分类的添加
    public function add(){

        //判断是否是post请求
	 if (request()->isPost()) {
		//接收参数
		$postData = input('post.');
		//验证器验证
		$result = $this->validate($postData,'Category.add',[],true);
		if ($result !==true) {
			$this->error(implode(',', $result));
		}
		//实例化模型存入数据库
		$categoryModel = new Category();
		if ($categoryModel->allowField(true)->save($postData)) {
			$this->success('添加成功',url('/admin/category/index'));
		}else{
			$this->error('添加失败');
		}
	 }
	   //取出无限极分类的数据
	 	$categoryModel = new Category();
	 	$categorys = $categoryModel->getSonsCat( $categoryModel->select() );
	 	return $this->fetch('',['categorys'=>$categorys]);
   }

   //商品分类列表
   public function index(){

   		$categoryModel = new Category();
   		$cats  = $categoryModel->getSonsCat( $categoryModel->select() );
   		return $this->fetch('',['cats'=>$cats]);
   }

   //商品分类编辑
      public function upd(){

  	//判断是否是post请求
		if(request()->isPost()){
			//接收post参数
			$postData = input('post.');
			//验证器验证
			$result = $this->validate($postData,'Category.upd',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//实例化模型写入数据库
			$categoryModel = new Category();
			if($categoryModel->update($postData)){
				$this->success("编辑成功",url("/admin/category/index"));
			}else{
				$this->error("编辑失败");
			}
		}
     //接收参数,回显数据
     $cat_id = input('cat_id');
     $categoryModel = new Category();
     $cat = $categoryModel->find($cat_id);

	 //取出无限极分类的数据
	 $categoryModel = new Category();
	 $categorys = $categoryModel->getSonsCat( $categoryModel->select() );
	 return $this->fetch('',[
	 	      'categorys'=>$categorys,
	 	      'cat'=>$cat
	 	      ]);
   }

   //删除商品分类
    public function del(){
    	//接收参数
    	$cat_id = input('cat_id');
    	if (Category::destroy($cat_id)) {
    		$this->success('删除成功',url('/admin/category/index'));
    	}else{
    		$this->error('删除失败');
    	}
    }
}