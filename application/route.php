<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];

use think\Route;

//定义后台首页路由
Route::get('a','admin/index/index');
//后台路由
Route::group('admin',function(){

	
   Route::get('left','/admin/index/left');
   Route::get('top','/admin/index/top');
   Route::get('main','/admin/index/main');

   //用户添加路由
   Route::any('user/add','admin/user/add');
   //用户列表
   Route::get('user/index','admin/user/index');
   //用户编辑
   Route::any('user/upd','admin/user/upd');
   //用户删除
   Route::get('user/del','admin/user/del');

   //后台登录
   Route::any('public/login','admin/public/login');
   //后台退出
   Route::any('public/logout','admin/public/logout');

   //权限路由
   //添加
   Route::any('auth/add','admin/auth/add');
   //列表
   Route::get('auth/index','admin/auth/index');
   //编辑
   Route::any('auth/upd','admin/auth/upd');
   //删除
   Route::get('auth/del','admin/auth/del');

   //角色路由
   //添加
   Route::any('role/add','admin/role/add');
   //列表
   Route::get('role/index','admin/role/index');
   //编辑
   Route::any('role/upd','admin/role/upd');
   //删除
   Route::get('role/del','admin/role/del');

   //商品类型
   //添加
   Route::any('type/add','admin/type/add');
   //列表
   Route::get('type/index','admin/type/index');
   //编辑
   Route::any('type/upd','admin/type/upd');
   //删除
   Route::get('type/del','admin/type/del');

   //商品属性
   //添加
   Route::any('attribute/add','admin/attribute/add');
   //列表
   Route::get('attribute/index','admin/attribute/index');
   //编辑
   Route::any('attribute/upd','admin/attribute/upd');
   //删除
   Route::get('attribute/del','admin/attribute/del');

    //商品分类
   //添加
   Route::any('category/add','admin/category/add');
   //列表
   Route::get('category/index','admin/category/index');
   //编辑
   Route::any('category/upd','admin/category/upd');
   //删除
   Route::get('category/del','admin/category/del');


    //商品
   //添加
   Route::any('goods/add','admin/goods/add');
   //列表
   Route::get('goods/index','admin/goods/index');
   //编辑
   Route::any('goods/upd','admin/goods/upd');
   //删除
   Route::get('goods/del','admin/goods/del');
   //ajax获取商品类型的属性
   Route::get('goods/getTypeAttr','admin/goods/getTypeAttr');
   
   //订单
   //列表
   Route::any('order/index','admin/order/index');
   //订单分配物流
   Route::any('order/setwuliu','admin/order/setwuliu');
   //查询物流
   Route::any('order/getwuliu','admin/order/getwuliu');

});
//前台路由组
//前台首页
Route::get('/','home/index/index');

Route::group("home",function(){
   //注册
   Route::any('public/register','home/public/register'); 
   //登录
   Route::any('public/login','home/public/login');
   //注册手机号短信验证
   Route::any('public/sendsms','home/public/sendsms');
   //忘记密码
   Route::get('public/find','home/public/find');
    //发送邮件
   Route::get('public/sendEmail','home/public/sendEmail'); 
    //重置新密码的路由 
   Route::any('public/setnewpassword/:member_id/:hash/:time','home/public/setnewpassword');
   //分类列表
   Route::any('category/index','home/category/index');
   //商品详情
   Route::any('goods/detail','home/goods/detail');
   //商品加入购物车
   Route::any('cart/addgoodstocart','home/cart/addgoodstocart');
   //购物车列表
   Route::any('cart/cartlist','home/cart/cartlist');
   //删除购物车商品
   Route::any('cart/delCartGood','home/cart/delCartGood');
   //清空购物车
   Route::any('cart/clearCartGood','home/cart/clearCartGood');
   //更新购物车商品数量
   Route::any('cart/updateCartGood','home/cart/updateCartGood');
   //订单结算
   Route::any('cart/orderAccount','home/cart/orderAccount');
   //订单结算页面商品列表
   Route::any('cart/getCartGoods','home/cart/getCartGoods');
   //提交订单
   Route::any('order/orderpay','home/order/orderpay');
   //支付宝同步通知路由
   Route::any('order/returnurl','home/order/returnurl');
   //支付宝异步通知路由
   Route::any('order/notifyurl','home/order/notifyurl');
   //支付成功
   Route::any('order/orderdone','home/order/orderdone');
   //订单展示
   Route::any('order/selforder','home/order/selforder');
   //订单付款
   Route::any('order/payMoney','home/order/payMoney');
});