<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Member;
class PublicController extends Controller{

	//注册
	public function register(){
		if (request()->isPost()) {
			$postData = input('post.');
			$result = $this->validate($postData,'Member.register',[],true);
			if ($result !== true ) {
				$this->error(implode(',', $result));
			}
			//判断手机验证码是否正确
			if ( md5($postData['phoneCaptcha'].config('sms_salt'))!==cookie('phone') ) {
				$this->error('手机号验证码输入错误,请重新发送');
			}
			//存储到数据库
			$memModel = new Member();
			if ( $memModel->allowField(true)->save($postData) ) {
				//注册成功后删除 cookie 中的验证码
				cookie('phone',null);
				$this->success('注册成功',url('/'));
			}else{
				$this->error('注册失败');
			}
		}
		return $this->fetch('');
	}

	//登录
	public function login(){
		if (request()->isPost()) {
			$postData = input('post.');
			$result = $this->validate($postData,'Member.login',[],true);
			if ($result !== true) {
				$this->error(implode(',', $result));
			}
			//dump($postData);die;
			//判断用户名,密码是否正确
			$memModel = new Member();
			$flag = $memModel->checkUser($postData['username'],$postData['password']);
			if ($flag) {
				//判断是否有goods_id ,如果有,返回到应的商品详情页
				if (input('goods_id')) {
					$this->redirect("/home/goods/detail",['goods_id'=>input('goods_id')]);
				}
				$this->redirect('/');
			}else{
				$this->error('用户名或密码错误!');
			}
		}
		return $this->fetch('');
	}

	//退出
	public function logout()
	{
		//退出要清除用户的session信息
		session('member_id',null);
		session('member_username',null);
		//重定向到登录页
		$this->redirect('/home/public/login');
	}

	//手机号发送短信 ajax 验证
	public function sendSms()
	{
		if (request()->isAjax()) {
			$phone = input('phone');
			$result = $this->validate(["phone"=>$phone],"Member.sendSms",[],false);
			if ($result !== true) {
				$response = ['code'=>-1,'message'=>'该手机号已被注册,请直接登录'];
				return json($response);die;
			}
			//发送短信
			$rand = mt_rand(1000,9999);
			$result = sendSms($phone,array($rand,'5'),'1');
			//判断是否成功
			if ($result->statusCode == '000000') {
				//手机验证码加盐,有效期5分钟
				cookie('phone',md5($rand.config('sms_salt')),300);
				$response = ['code'=>200,'message'=>'发送短信成功'];
				echo json_encode($response);die;
			}else{
				$response = ['code'=>-2,'message'=>'发送失败,请重新发送'.$result->statusMsg];
				echo json_encode($response);die;
			}
		}
	}

	//忘记密码
	public function find()
	{
		return $this->fetch('');
	}

	//忘记密码,ajax发送邮件
	public function sendEmail()
	{
		if (request()->isAjax()) {
			//接收参数
			$email = input("email");
			//验证邮箱之前是否注册过
			$result = Member::where('email','=',$email)->find();
			if (!$result) {
				//没有注册过 表示无法验证
				$response = ['code'=>-1,'message'=>'该邮箱还未注册'];
				echo json_encode($response);die;
			}
			//构造找回密码的链接地址
			$member_id = $result['member_id'];
			$time = time();
			$hash = md5($result['member_id'].$time.config('email_salt')); 
			//把用户的id和邮箱进行加密 防止不法人员篡改
			$href = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST']."/index.php/home/public/setNewPassword/".$member_id.'/'.$hash.'/'.$time;
			$content = "<a href = '$href' target = '_blank'>惊喜商城,找回密码</a>";
			//发送邮件
			
			if (sendEmail([$email],'找回密码',$content)) {
				$response = ['code'=>200,'message'=>'发送成功,请及时查收'];
				echo json_encode($response);die;
			}else{
                 $response = ['code'=>-2,'message'=>'发送失败,请稍后再试'];
				echo json_encode($response);die;
			}
		}
	}

	//重置密码
	public function setNewPassword()
	{ 

		//判断邮件地址是否一致,防止恶意篡改
		if ( md5($member_id.$time.config('email_salt')) != $hash ) {
			//不一致则警告
			exit('地址已被修改');
		}
		//判断邮件是否还在有效期内
		if (time()>$time+1800) {
		     exit('该邮件已失效,请从新发送');
		}
		if (request()->isPost()) {
			$postData = input('post.');
			$result = $this->validate($postData,"Member.setNewPassword",[],true);
			if ($result !== true) {
				$this->error(implode(',', $result));
			}
			//更新密码
			$data = [ 
			       'member_id'=>$member_id,
			       'password'=>md5($postData['password'].config('password_salt'))
			       ];
			$memModel = new Member();
			if ($memModel->update($data)) {
				$this->success('重置密码成功',url("/home/public/login"));
			}else{
				$this->success('重置密码失败,请重新设置');
			}
		}
		return $this->fetch('');
	}
}