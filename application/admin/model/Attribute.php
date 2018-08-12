<?php
namespace app\admin\model;
use think\Model;
class Attribute extends Model{
		protected $pk = 'attr_id';
	    //时间戳自动写入
	    protected $autoWriteTimestamp = true;

}