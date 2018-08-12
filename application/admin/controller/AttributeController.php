<?php
namespace app\admin\controller;
use app\admin\model\Attribute;
use app\admin\model\Type;
class AttributeController extends CommonController{
	public function add(){
	  //判断是否是post请求
	if (request()->isPost()) {
		//接收参数
		$postData = input('post.');
		//验证器验证
		//如果属性录入方式为列表选择
		if ($postData['attr_input_type'] == 1) {

			$result = $this->validate($postData,'Attribute.add',[],true);
		}else{
			//如果属性录入方式为手工输入
			$result = $this->validate($postData,'Attribute.except',[],true);
		}
		
		if ($result !==true) {
			$this->error(implode(',', $result));
		}
		//实例化模型存入数据库
		$attrModel = new Attribute();
		if ($attrModel->allowField(true)->save($postData)) {
			$this->success('添加成功',url('/admin/attribute/index'));
		}else{
			$this->error('添加失败');
		}
	}
	//取出商品类型
	$types = Type::select();
     return $this->fetch('',['types'=>$types]);
   }
   
   //商品属性列表
   public function index(){
   	//取出数据,分配到模板
   	$attrs = Attribute::alias('t1')
   	                 ->field('t1.*,t2.type_name')
   	                 ->join('sh_type t2','t1.type_id = t2.type_id','left')
   	                 ->paginate(2);
   	                 
   	   return $this->fetch('',['attrs'=>$attrs]);              
   }

   //商品属性编辑
   public function upd(){
   		  //判断是否是post请求
	if (request()->isPost()) {
		//接收参数
		$postData = input('post.');
		//验证器验证
		//如果属性录入方式为手工录入
		if ($postData['attr_input_type'] == 0) {

			$result = $this->validate($postData,'Attribute.except',[],true);
		}else{
			//如果属性录入方式为列表选择
			$result = $this->validate($postData,'Attribute.add',[],true);
		}
		
		if ($result !==true) {
			$this->error(implode(',', $result));
		}
		//实例化模型存入数据库
		$attrModel = new Attribute();
		if ($attrModel->allowField(true)->update($postData)) {
			$this->success('编辑成功',url('/admin/attribute/index'));
		}else{
			$this->error('编辑失败');
		}
	}  
	//接收参数,回显数据
	   $attr_id = input('attr_id');
	   $attribute = Attribute::find($attr_id);

       //取出商品类型
	  $types = Type::select();
       return $this->fetch('',['types'=>$types,'attribute'=>$attribute]);
     
   }

   //删除商品属性
    public function del(){
    	//接收参数
    	$attr_id = input('attr_id');
    	if (Attribute::destroy($attr_id)) {
    		$this->success('删除成功',url('/admin/attribute/index'));
    	}else{
    		$this->error('删除失败');
    	}
    }
}