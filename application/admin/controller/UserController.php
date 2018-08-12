<?php
namespace app\admin\controller;
//use think\Controller;
use app\admin\model\User;
use app\admin\model\Role;
class UserController extends CommonController{

	//用户添加
	public function add(){
      //1.判断post请求
      if (request()->isPost()) {
       	$userModel = new User();
       	//2.接收参数
       	$postData = input('post.');

       	//3.验证器验证
       	$result = $this->validate($postData,'User.add',[],true);
       	if ($result !== true) {
       		$this->error(implode(',', $result));
       	}
       	//4.编辑update 或 添加入库 save
       	//给密码password字段进行加密
       	//模型中进行了事件处理 
       	//$postData['password'] = md5($postData['password'].config('password_salt'));
       	if ($userModel->allowField(true)->save($postData)) {
       		$this->success('添加成功',url('/admin/user/index'));
       	}else{
       		$this->error('添加失败');
       	}

       } 
       //取出所有的角色数据,分配到模板中
       $roles = Role::select();
       return $this->fetch('',['roles'=>$roles]);
	}

	//用户列表
	public function index(){
		//1.获取数据
		$users = User::alias('t1')
		              ->field('t1.*,t2.role_name')
		              ->join('sh_role t2','t1.role_id = t2.role_id','left')
		              ->paginate(2);//tp5分页函数
		//2.输出模板
		return $this->fetch('',['users'=>$users]);

	}

	//用户编辑
	public function upd(){
		//编辑入库
		//1.判断是否是post请求
		if (request()->isPost()) {
			$userModel = new User();
			//2.接收post参数
			$postData = input('post.');
			//3.验证器验证
			//当前密码和确认密码都为空的时候,只验证username,保留原密码
			if ($postData['password']=='' && $postData['repassword']=='') {
				$result = $this->validate($postData,'User.onlyUsername',[],true);
				if ($result !==true) {
					$this->error( implode(',', $result));
				}
			}else{
					//说明其中一个密码验证不为空,则进行UsernamePassword场景验证
					$result = $this->validate($postData,'User.UsernamePassword',[],true);
					if ($result !== true) {
						$this->error(implode(',', $result));
					}
				}
			
			//4.判断编辑是否成功
			if ($userModel->allowField(true)->isUpdate(true)->save($postData)) {
				$this->success('编辑成功',url('/admin/user/index'));
		    }else{
				$this->error('编辑失败');
			}
		}
		//接收数据 回显到模板视图
		$user_id =input('user_id');
		$userData = User::find($user_id);
		return $this->fetch('',['userData'=>$userData]);
	}

	//用户删除
	public function del(){
		//接收参数
		$user_id = input('user_id');
		//判断是否删除成功
		if (User::destroy($user_id)) {
			$this->success('删除成功',url('/admin/user/index'));
		}else{
			$this->success('删除失败');
		}
	}
}