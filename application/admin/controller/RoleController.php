<?php
namespace app\admin\controller;
use app\admin\model\Auth;
use app\admin\model\Role;
use think\Db;
class RoleController extends CommonController{

	//角色添加
	public function add(){

		//接收参数
		if (request()->isPost()) {
			$postData = input('post.');
			
			//验证器验证
			$result = $this->validate($postData,'Role.add',[],true);
			if ($result !== true) {
				$this->error(implode(',', $result));
			}
			
			//存入数据库
			$roleModel = new Role();
			if ($roleModel->allowField(true)->save($postData)) {
				$this->success('添加成功',url('/admin/role/index'));
			}else{
				$this->error('添加失败');
			}

		}
       $authModel = new Auth();
       $oldauths = $authModel->select()->toArray();
       //以auth_id作为$auths的二维数组下标
       $auths = [];
       foreach ($oldauths as $v) {
       	   $auths[$v['auth_id']] = $v;
       }
       //把所有的权限以pid进行分组
       $children = [];
       foreach ($oldauths as $vv) {
       	   $children[$vv['pid']][] = $vv['auth_id'];
       }
       return $this->fetch('',['children'=>$children,'auths'=>$auths]);
	}

	//角色列表
	public function index(){
        $roles = Db::query("select t1.*, GROUP_CONCAT(t2.auth_name) as all_auth from sh_role  t1 left join sh_auth t2  on FIND_IN_SET(t2.auth_id,t1.auth_ids_list) group by t1.role_id");
        return $this->fetch('',['roles'=>$roles]);
    }
    //角色编辑
    public function upd(){

      if(request()->isPost()){
            //接收参数
            $postData = input('post.');
            //验证器验证
            $result = $this->validate($postData,"Role.upd",[],true);
            if($result !== true){
                $this->error( implode(',',$result) );
            }
            //写入数据库
            $roleModel = new Role();
            if($roleModel->update($postData)){
                $this->success("编辑成功",url("/admin/role/index"));
            }else{
                $this->error("编辑失败");
            }
        }
    	//接收参数
    	$role_id = input('role_id');
    	//取出所有权限
    	 $oldAuths = Auth::select()->toArray();
        //以每个权限的auth_id作为$oldAuths二维数组的下标
        $auths = [];
        foreach($oldAuths as $v){
            $auths[ $v['auth_id'] ] = $v;
        }
        //根据pid进行分组，把具有相应的pid分为同一组
        $children = [];
        foreach( $oldAuths as $vv){
            $children[ $vv['pid'] ][] = $vv['auth_id'] ;
        }
        //取出当前角色已有的权限
        $role = Role::find( $role_id);
        return $this->fetch('',[
            'auths' => $auths,
            'children' => $children,
            'role' => $role,
        ]);
    }

	//角色删除
	public function del(){
		//接收参数
		$role_id = input('role_id');
		//判断是否删除成功
		if (Role::destroy($role_id)) {
			$this->success('删除成功',url('/admin/role/index'));
		}else{
			$this->success('删除失败');
		}
	}
}