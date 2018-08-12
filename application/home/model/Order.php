<?php
namespace app\home\model;
use think\Model;

//订单商品表
class Order extends Model{
	protected $pk = 'id';
	//时间戳自动写入
	protected $autoWriteTimestamp = true;
}