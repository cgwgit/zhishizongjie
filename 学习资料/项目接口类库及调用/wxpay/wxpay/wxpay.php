<?php 
/**
 * 微信扫码支付生成支付所用的二維碼
 *
 * v3-b12
 *
 * by 中孝科技 运营版
 */
// defined('ZXKeJi') or exit('Access Invalid!');

class wxpay{

    /**
     * 存放支付订单信息
     * @var array
     */
    private $_order_info = array();

    /**
     * 支付信息初始化
     * @param array $payment_info
     * @param array $order_info
     */
    public function __construct($payment_info = array(), $order_info = array()) {
        define('WXN_APPID', $payment_info['payment_config']['appid']);
        define('WXN_MCHID', $payment_info['payment_config']['mchid']);
        define('WXN_KEY', $payment_info['payment_config']['key']);
        $this->_order_info = $order_info;
    }

    /**
     * 组装包含支付信息的url(模式1)生成扫码的url
     */
    public function get_payurls() {
        require_once BASE_PATH.'/wxpay/lib/WxPay.Api.php';
        require_once BASE_PATH.'/WxPay.NativePay.php';
        require_once BASE_PATH.'/wxpay/log.php';
        $logHandler= new CLogFileHandler(BASE_DATA_PATH.'/log/wxpay/'.date('Y-m-d').'.log');
        $logwx = logwx::Init($logHandler, 15);
        $notify = new NativePay();
        return $notify->GetPrePayUrl($this->_order_info['pay_sn']);
    }

    /**
     * 组装包含支付信息的url(模式2)
     */
    public function get_payurl() {
        // echo BASE_PATH.'/api/payment/wxpay/lib/WxPay.Api.php';die;
        // echo BASE_PATH.'/api/payment/wxpay/lib/WxPay.Api.php';die;
        require_once BASE_PATH.'/wxpay/lib/WxPay.Api.php';
        require_once BASE_PATH.'/wxpay/WxPay.NativePay.php';
        require_once BASE_PATH.'/wxpay/log.php';
        // $logHandler= new CLogFileHandler(BASE_DATA_PATH.'/log/wxpay/'.date('Y-m-d').'.log');
        // $Logwx = Logwx::Init($logHandler, 15);
        //统一下单输入对象
        $input = new WxPayUnifiedOrder();
        $input->SetBody($this->_order_info['pay_sn'].'订单');//设置订单信息
        $input->SetAttach($this->_order_info['order_type']);//附加数据
        $input->SetOut_trade_no($this->_order_info['pay_sn']); //商户订单号
        $input->SetTotal_fee($this->_order_info['api_pay_amount']*100);//付款金额
        $input->SetTime_start(date("YmdHis"));//订单开始时间
        $input->SetTime_expire(date("YmdHis", time() + 3600));//订单结束时间
        $input->SetGoods_tag('');//商品标记
        $input->SetNotify_url('http://zq.zxyl1688.com/index.php/Home/Pay/wxnotify/data/');//支付成功后异步通知的notify_url
        $input->SetTrade_type("NATIVE");//订单支付类型，该类型为原生扫码支付
        //$input->SetOpenid($openId);
        $input->SetProduct_id($this->_order_info['pay_sn']);//商品id，用户自己定义
        //调用统一下单接口
        $result = WxPayApi::unifiedOrder($input);//
//        print_r($result);exit();
        // Logwx::DEBUG("unifiedorder-:" . json_encode($result));
        return $result["code_url"];
    }
}
