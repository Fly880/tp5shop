<?php
namespace app\admin\controller;
//use think\Controller;
use think\Db;

class OrderController extends CommonController{
  
  //订单列表
  public function index()
  {
  	$orderData = Db::name('order')->select();
  	return $this->fetch('',['orderData'=>$orderData]);
  }

  //分配物流
  public function setWuliu()
  {   
  	  //是否post
  	 if (request()->isPost()) {
  	 	//接收参数
  	 	$postData = input('post.');
  	 	$postData['update_time'] = time();
  	 	$postData['send_status'] = 1;
       //验证
       //编辑入库
       $result = Db::name('order')->update($postData);
       if ($result) {
       	  $this->success('配置物流成功',url('/admin/order/index'));
       }else{
       	$this->error('配置物流失败');
       }
  	 }
  	 $order_id = input('order_id');
  	 $orderData = Db::name('order')->where('order_id',$order_id)->find();
  	 return $this->fetch('',['orderData'=>$orderData]);
  }

  public function getWuliu()
  {
  	if (request()->isAjax()) {
  		$company = input('company');
  		$number = input('number');
  		$url = "http://kuaidi100.com/applyual?key=9d37bc60a41e6fe8com={$company}&nu={$number}&show=0";
  		echo file_get_contents($url);
  	}
  }
}