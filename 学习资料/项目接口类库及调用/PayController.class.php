<?php
namespace Home\Controller;
use Think\Controller;
//支付宝支付控制器
class PayController extends Controller {
    public function update() {
        $order_sn = makeSn();
        $insert = array(
            'memberid' => 99999999,
            'paymoney' => 0.01,
            'paypmoney' => 0,
            'payrmoney' => 0.01,
            'paytype' => 1,
            'paysn' => makeSn(),
            'paystatus' => 0,
        );
        $res = M('zxzq_wallet_record')->data($insert)->add();
        if($res){
            $rst = M('payment')->where(array('payment_code' => 'alipay'))->find();
            $config = unserialize($rst['payment_config']);
            $rst['payment_config'] = $config;
            
            $order_info['subject'] = '预存款充值';
            $order_info['order_type'] = 'zq_yck';
            $order_info['api_pay_amount'] = $insert['payrmoney'];
            $order_info['pay_sn'] = $insert['paysn'];
            $order_info['body'] = "用户ID：{$insert['memberid']}, 金额：{$data['paymoney']},订单生成时间：" . date('Y-m-d H:i:s');
            $payment_api = new \Think\Pay\alipay($rst,$order_info);
            @header("Location: ".$payment_api->get_payurl()); 
        }
    }
    //异步通知回调
    public function notify(){
        if(I('trade_status') == 'TRADE_SUCCESS'){
			$check = M('zxzq_wallet_record')->where(array('paysn' => I('out_trade_no')))->find();
			if(!$check['paystatus']){
				$data['payid'] = I('trade_no');
				$data['paystatus'] = 1;
				$data['paytime'] = date('Y-m-d H:i:s');
				$res = M('zxzq_wallet_record')->data($data)->where(array('paysn' => I('out_trade_no')))->save();
				$redata = M('zxzq_wallet_record')->where(array('paysn' => I('out_trade_no')))->find();
				$wall_data = M('zxzq_wallet')->where(array('memberud'=>$redata['memberid']))->find();
				$savedata = array(
					'money' => $wall_data['money'] + $redata['paymoney']
				);
				$wres = M('zxzq_wallet')->data($savedata)->where(array('memberud'=>$redata['memberid']))->save();
				if(res && $wres){
					echo 'success';exit();
				}
			}
        }
		
	}
	
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