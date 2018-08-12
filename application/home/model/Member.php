<?php
namespace app\home\model;
use think\Model;

class Member extends Model{
	protected $pk = 'member_id';
	//时间戳自动写入
	protected $autoWriteTimestamp = true;

	//事件机制
	protected static function init()
	{
		//数据入库前
		Member::event('before_insert',function($member){
			$member['password'] = md5($member['password'].config('password_salt'));
		});
	}

	//登录验证
	public function checkUser($username,$password)
	{
		$where = [
		      'username'=>$username,
		      'password'=>md5($password.config('password_salt'))
		      ];
		$userInfo = $this->where($where)->find();
		if ($userInfo) {
			//成功后把用户信息保存到session中
			session('member_username',$userInfo['username']);
			session('member_id',$userInfo['member_id']);
			return true;
		}else{
			return false;
		}
	}
}