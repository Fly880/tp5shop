<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
class PublicController extends Controller{
	public function login(){
		//1.判断post请求
		if (request()->isPost()) {
			//接收参数
			$postData = input('post.');
			//3.验证器验证
			$result = $this->validate($postData,'User.login',[],true);
			if ($result !== true) {
				$this->error(implode(',', $result));
			}
			//4.判断是否登录成功
			$userModel = new User();
			if ($userModel->checkUser($postData['username'],$postData['password'])) {
				//登录成功
				$this->redirect('/');
			}else{
				$this->error('用户名或密码错误');
			}
		}
		return $this->fetch();
	}
	public function logout(){
		//清除登录成功后保存的用户session信息
		session('user_id',null);
		session('username',null);
		//退出回到登录页
		$this->redirect('/admin/public/login');
	}
}