<?php
//曹国伟
//2017年1月11日08:42:42
//微信支付宝支付类（自己的前程保，微信待验证）
namespace Home\Controller;
use Think\Controller;
class PayController extends Controller {
	//展示支付订单页面
	public function dingdanzhifu(){
	   $post = I('post.');
	   if(empty($post['tname'])){
	   	$this->error('参保人信息不能为空',U('Social/orderinfo',array('cid' => $post['cid'])));exit;
	   }
       $info = M('chargedetail')->where(array('cid' => $post['cid'], 'order_status' =>'0'))->find();
       $info['allcount'] = number_format($info['allcount'],2);
       $this->assign('info', $info);
       $this->display();
	}

	//处理订单支付(如果传过来1微信支付，2支付宝支付)
	public function selectPay(){
		$order_info = I('post.');
		if($order_info['pay'] == 1){
			$this->wxpay($order_info);
		}else{
            $this->alipay($order_info);
		}
	}
	//支付宝
	public function alipay($order_info){
        $rst = M('chargedetail')->where(array('order_sn' => $order_info['order_sn']))->find();
        if($rst){
        	$config = array(
        		'alipay_service' => C('alipay_service'),
                'alipay_account' =>C('alipay_account'),
                'alipay_key' => C('alipay_key'),
                'alipay_partner' => C('alipay_partner')
        		);
		    $rst['payment_config'] = $config;
		    $order_info['subject'] = '前程保保单支付';
		    $order_info['order_type'] = 'qiancheng';
		    $order_info['api_pay_amount'] = $rst['allcount'];
		    $order_info['pay_sn'] = $rst['order_sn'];
		    $order_info['body'] = "参保用户ID：{$rst['cid']}, 金额：{$rst['allcount']},订单生成时间：" . $rst['1486363487'];
		    $payment_api = new \Think\Pay\alipay($rst,$order_info);
		    @header("Location: ".$payment_api->get_payurl()); 
        }
	    
	}

	//支付宝异步通知回调(在支付宝类中设置改毁掉地址)
    public function notify(){
        if(I('trade_status') == 'TRADE_SUCCESS'){
        	//取出查看支付的这条订单
			$check = M('chargedetail')->where(array('order_sn' => I('out_trade_no')))->find();
			//如果没有支付，修改支付状态，支付成功
			if(!$check['order_status']){
				// $data['payid'] = I('trade_no');
				$data['order_status'] = '1';
				$data['paytime'] = time();
				$res = M('chargedetail')->where(array('order_sn' => I('out_trade_no')))->save($data);
				M('cinfo')->where(array('id' => $check['cid']))->save(array('status' => '1'));
				if(res){
					echo 'success';exit();
				}
			}
        }
		
	}
	//微信支付
	public function wxpay($order_info){
		$rst = M('chargedetail')->where(array('order_sn' => $order_info['order_sn']))->find();
		if($rst){
			$config = array(
        		'appid' => C('appid'),
                'mchid' =>C('mchid'),
                'key' => C('key')
        		);
		    $rst['payment_config'] = $config; 
	    	$order_info['subject'] = '前程保保单支付'.$rst['order_sn'];
	        $order_info['order_type'] = 'baodan_order';
	        $order_info['pay_sn'] = $rst['order_sn'];
	        $order_info['api_pay_amount'] = $rst['allcount'];
	        Vendor('wx.example.wxpay');
	        $payment_api = new \wxpay($rst,$order_info);//使用构造方法初始化一些变量

	        $payment_api->getPay();
		}
	}
	  //微信二维码
	  public function qrcode(){
        $data = base64_decode($_GET['data']);
        $data = decrypt($data,MD5_KEY,30);
        // var_dump($data);die;
        Vendor('wxpay.phpqrcode.phpqrcode');
        \QRcode::png($data);
    }
         //微信回调
    	public function wxnotify(){
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		$array = $this->xmlToArray($xml);
		file_put_contents('text.txt', json_encode($array));
		if($array['result_code'] == 'SUCCESS'){
			$check = M('zxzq_wallet_record')->where(array('paysn' => $array['out_trade_no']))->find();
			if(!$check['paystatus']){
				$data['payid'] = $array['transaction_id'];
				$data['paystatus'] = 1;
				$data['paytime'] = date('Y-m-d H:i:s');
				
				$res = M('zxzq_wallet_record')->data($data)->where(array('paysn' => $array['out_trade_no']))->save();
				$redata = M('zxzq_wallet_record')->where(array('paysn' => $array['out_trade_no']))->find();
				$wall_data = M('zxzq_wallet')->where(array('memberid'=>$redata['memberid']))->find();
				$savedata = array(
					'money' => $wall_data['money'] + $redata['paymoney']
				);
				$wres = M('zxzq_wallet')->data($savedata)->where(array('memberid'=>$redata['memberid']))->save();
				if(res && $wres){
					echo 'success';exit();
				}
			}
		}
	}
	
	//将XML转为array
    public function xmlToArray($xml)
    {    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }
}