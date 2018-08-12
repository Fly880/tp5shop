<?php 
namespace app\admin\validate;
use think\Validate;
class Order extends Validate{
	//验证规则
	protected $rule = [
		//表单name名称 => 规则1|规则2...
		'ali_order_id' => 'require:order',
		
	];
	//验证不通过的提示信息
	protected $message = [
		//表单name名称.规则名 => '规则提示信息'
		'ali_order_id.require' => '订单号必填',
		// 'ali_order_id.unique' => '订单号重复',

	];
	//验证场景
	protected $scene = [
		//场景名=>[元素=>规则1|规则2]
		//场景名=>[元素] 验证元素的所有的规则
		'info' => ['ali_order_id'],
		
		
	];
}