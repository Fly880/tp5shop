<?php
namespace app\admin\model;
use think\Model;
class Role extends Model{
		protected $pk = 'role_id';
	    //时间戳自动写入
	    protected $autoWriteTimestamp = true;
	    //事件机制
	    protected static function init(){
	    	//入库前
	    	Role::event('before_insert',function($role){
                //把权限数组形式变为字符串进行入库
                //防止没有分配权限没有auth_ids_list 导致判断错误
                if (isset($role['auth_ids_list'])) {
                	$role['auth_ids_list'] = implode(',', $role['auth_ids_list']);
                }
	    	});

	    	 //编辑前的事件
	        Role::event('before_update',function($role){
	    	    //把权限数组形式变为字符串进行入库
                //防止没有分配权限没有auth_ids_list 导致判断错误
                if (isset($role['auth_ids_list'])) {
                	$role['auth_ids_list'] = implode(',', $role['auth_ids_list']);
                }
	    	});
	    } 
}