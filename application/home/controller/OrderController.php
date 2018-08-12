<?php 
namespace app\home\controller; 
use think\Controller; 
use think\Db;
use app\home\model\Order;
use app\home\model\OrderGoods;
use app\home\model\Goods;
class OrderController extends Controller{

	//获取购物车商品详情
	public function getCartGood()
	{
		//1.通过购物车类的getCart方法获取购物车的数据
        $cart = new \cart\Cart();
        $carts = $cart->getCart();
        //2.构造一定的数组结构
        $cartData = [];
        foreach($carts as $key => $goods_number){
            $arr = explode('-',$key);
            $goods_id = $arr[0];
            $goods_attr_ids = $arr[1];
            $data=[
                'goods_id' => $goods_id ,
                'goods_attr_ids' => $goods_attr_ids ,
                'goods_number' => $goods_number ,
                'goodsInfo' => Db::name('goods')->find($goods_id),
                'attr'=> Db::name('goods_attr')
                        ->field("group_concat(t2.attr_name,':',t1.attr_value separator '<br />') attrInfo,sum(t1.attr_price) attrTotalPrice")
                        ->alias('t1')
                        ->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
                        ->where("t1.goods_attr_id",'in',$goods_attr_ids)
                        ->find()
            ];

             $cartData[] = $data;
        }
            return $cartData;
    } 

    //订单和订单商品表的数据入库
    public function orderPay()
    {
     	$member_id = session('member_id');
     	if (!$member_id) {
     		$this->error('请重新登录');
     	}
     	//1.接收参数
     	$postData = input('post.');
     	//2.验证器验证
     	$result = $this->validate($postData,'Order.pay',[],true);
     	if ($result !== true) {
     	 $this->error( implode(',', $result) );
     	}
     	//3.获取订单的总价 并且生成唯一的订单号
     	$order_id = date('ymdhis').time().uniqid();
     	//获取购物车数据,得到总价 通过上面的方法
     	$cartData = $this->getCartGood();
     	if (!$cartData) {
     		$this->error('购物车空荡荡的,赶快去逛逛吧!!',url('/'));
     	}
     	$total_price = 0;
     	foreach ($cartData as $v) {
     		$total_price += ( $v['goodsInfo']['goods_price'] + $v['attr']['attrTotalPrice'])*$v['goods_number'];
     	}
     	//4.开启事务,先入库到订单表
     	$postData['order_id'] = $order_id;
     	$postData['total_price'] = $total_price;
     	$postData['member_id'] = $member_id;
     	Db::startTrans();
     	//5.订单表入库成功后才可以入库到订单商品表
     	try{
     		$order_result = Order::create($postData);
            if (!$order_result) {
            	//说明订单表入库失败 抛出异常
            	throw new  \Exception('订单入库失败');
            	
            }
            //若是成功,则可以入库到订单商品表
            $goodsModel = new Goods();
            foreach ($cartData as $v) {
            	$goods_price = ($v['goodsInfo']['goods_price'] + $v['attr']['attrTotalPrice'])*$v['goods_number'];
            	$order_goods_result = OrderGoods::create([
            		        'order_id'=>$order_id,
            		        'goods_id'=>$v['goods_id'],
                            'goods_attr_ids'=>$v['goods_attr_ids'],
                            'goods_number'=>$v['goods_number'],
                            'goods_price'=>$goods_price,
            		     ]);
            	//同时减少对应商品的库存 
            	$where = ['goods_id'=>$v['goods_id'],'goods_number'=>['>=',$v['goods_number']]];
            	$goods_result = $goodsModel->where($where)->setDec('goods_number',$v['goods_number']);
            	if (!$goods_result || !$order_goods_result) {
            		throw new \Exception("下单失败 或 库存不足");
            		
            	}
            }
            //上面都成功之后,提交事务
            Db::commit();
     	}catch(\Exception $e){
     		//捕获异常进行回滚
     		Db::rollback();
     	}
     	//6.结算成功,清空购物车
     	$cart = new \cart\Cart();
     	$cart->clearCart();
     	//7.唤起支付宝进行支付
     	//echo "去付款吧!";die();
        $this->_payMoney($total_price,$order_id,'支付宝测试','PC端支付宝支付');
    } 

    //支付宝付款
    private function _payMoney($total_price,$order_id,$title='',$body='')
    {
        $payData = [
                 //商户订单号，商户网站订单系统中唯一订单号，必填
                'WIDout_trade_no'=>$order_id,
                 //付款金额，必填
                'WIDtotal_amount'=>$total_price,
                 //订单名称，必填
                'WIDsubject'=>$title,
                //商品描述，可空
                'WIDbody'=>$body,
        ];
     //引入支付宝唤起支付的文件
     include "../extend/alipay/pagepay/pagepay.php";
    } 

    //支付宝get同步方式跳转的地址
    public function returnUrl()
    {
        require_once("../extend/alipay/config.php");
        require_once("../extend/alipay/pagepay/service/AlipayTradeService.php");
        //会以get方式携带支付的结果参数到此页面
        $arr = input('get.');
        //dump($arr);die();
        $alipayService = new \AlipayTradeService($config);
        $result = $alipayService->check($arr);
        if($result){//验证成功
            //请在这里加上商户的业务逻辑程序代码
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            
            //商户订单号
            $out_trade_no = htmlspecialchars($arr['out_trade_no']);
            //支付宝交易号
            $trade_no = htmlspecialchars($arr['trade_no']);
                
            //更新订单的状态为已支付
            $orderModel = new Order();
            $result = $orderModel->where("order_id", $out_trade_no)->update(['pay_status'=>1,'ali_order_id'=>$trade_no]);
            if($result){
                //支付成功
                $this->error('支付成功',url('/home/order/orderDone'));
            }else{
                //支付失败（跳转到个人订单列表）
                $this->error('支付失败',url('/'));
            }
        }
        else {
            //验证失败
            echo "验证失败";
        }        
    }

      //支付宝 post 异步方式跳转的地址
      //异步通知只能线上环境才可以进行调试
    public function notifyUrl()
    {
        require_once("../extend/alipay/config.php");
        require_once("../extend/alipay/pagepay/service/AlipayTradeService.php");
        //会以get方式携带支付的结果参数到此页面
        $arr = input('post.');
        //dump($arr);die();
        $alipayService = new \AlipayTradeService($config);
        $result = $alipayService->check($arr);
        if($result){//验证成功
            //请在这里加上商户的业务逻辑程序代码
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            
            //商户订单号
            $out_trade_no = htmlspecialchars($arr['out_trade_no']);
            //支付宝交易号
            $trade_no = htmlspecialchars($arr['trade_no']);
                
            //更新订单的状态为已支付
            $orderModel = new Order();
            $result = $orderModel->where("order_id", $out_trade_no)->update(['pay_status'=>1,'ali_order_id'=>$trade_no]);
            if($result){
                //支付成功
                echo 'success';//异步支付成功只能是返回这样
            }else{
                //支付失败（跳转到个人订单列表）
                $this->error('支付失败',url('/'));
            }
        }
        else {
            //验证失败
            echo "验证失败";
        }        
    }

    //支付成功
    public function orderDone($value='')
    {
        return $this->fetch('');
    }

    //订单展示
    public function selfOrder()
    {   
        $member_id = session('member_id');
        $selfModel = new Order();
        $orderData = $selfModel->where('member_id',$member_id)->select();
        return $this->fetch('',['orderData'=>$orderData]);
    }

    //订单付款
    public function payMoney()
    {
        $order_id = input('order_id');
        $total_price = Order::where('order_id',$order_id)->value('total_price');
        //进行支付宝支付
        $this->_payMoney($total_price,$order_id,'支付测试','有钱买买买');
    }
}  