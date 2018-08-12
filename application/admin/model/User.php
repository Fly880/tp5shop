<?php
namespace app\admin\model;
use think\Model;
use app\admin\model\Role;
use app\admin\model\Auth;
class User extends Model{
	//表的主键字段
	protected $pk = "user_id";
	//时间戳的自动写入
	protected $autoWriteTimestamp = true;



/*定义事件语法说明：定义在模型的init方法中 
protected static function init(){
//入库前的事件 before_insert
模型类名::event('事件名',function(数据对象){
   // return false 不会写入数据库
});

//...定义更多的事件
} */


	//事件的处理方法
	protected static function init(){
		//入库前的事件 before_insert
		User::event('before_insert',function($user){
			//return  false 不会入库,不会执行save方法
			//参数$user 表单中的数据对象后面把此对象中的数据进行入库
			//入库之前可以进行对数据的操作
			//给密码password字段进行加密
			$user['password'] = md5($user['password'].config('password_salt'));
		});
	}

	//登录验证
	public function checkUser($username,$password){
		$where = [
		     'username'=>$username,
		     'password'=>md5($password.config('password_salt')),
		];
        
        $userInfo = $this->where($where)->find();
	  if($userInfo){
		//用户名 密码匹配正确,把信息存到 session 中
		session('user_id',$userInfo['user_id']);
		session('username',$userInfo['username']);
		//通过用户的role_id,把当前用户的权限存到session中
		$this->getAuthWriteSession($userInfo['role_id']);
		return true;
	   }else{
		return false;
	  }
   }

   function getAuthWriteSession($role_id){
      //获取角色表中的auth_ids_list字段的值
      $auth_ids_list = Role::where('role_id','=',$role_id)->value('auth_ids_list');
      //如果是超级管理员 $auth_ids_list =>  *
      if ($auth_ids_list == '*') {
      	//超级管理员拥有权限表所有数据
      	$oldAuths = Auth::select()->toArray();
      } else {
      	//如果是非超级管理员只能取出已有的权限 $auth_ids_list => 1,2,3,4
	    $oldAuths = Auth::where("auth_id",'in',$auth_ids_list)->select()->toArray();
      }
      		//两个技巧取出数据
		//1.每个数组的auth_id为二维数组的下标
		$auths = [];
		foreach($oldAuths as $v){
			$auths[ $v['auth_id'] ] = $v;
		}
		//2.通过pid进行分组
		$children = [];
		foreach ($oldAuths as $vv) {
			$children[ $vv['pid'] ][] = $vv['auth_id'];
		}
		//写入到session中去
		session('auths',$auths);
		session('children',$children);
		//写入管理员可访问的权限到session中去，用于后面的防翻墙
		if($auth_ids_list == '*'  ){
			//超级管理员
			session('visitorAuth','*');
		}else{
			//非超级管理员 [auth/add,auth/index,..]
			$visitorAuth = [];
			foreach($oldAuths as $v){
				$visitorAuth[] = strtolower($v['auth_c'].'/'.$v['auth_a']);
			}
			session('visitorAuth',$visitorAuth);
		}
      
   }
}