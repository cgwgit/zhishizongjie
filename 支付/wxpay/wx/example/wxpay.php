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
     * 组装包含支付信息的url(模式2)
     */
    public function get_payurl(){
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

    public function getPay(){
       ini_set('date.timezone','Asia/Shanghai');//设置时区
        //error_reporting(E_ERROR);
        require_once BASE_PATH."/wx/lib/WxPay.Api.php";
        require_once BASE_PATH."/wx/example/WxPay.JsApiPay.php";
        require_once BASE_PATH.'/wx/example/log.php';
        //初始化日志
        $logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
        $log = Log::Init($logHandler, 15);
        
        //打印输出数组信息
        function printf_info($data)
        {
            foreach($data as $key=>$value){
                echo "<font color='#00ff55;'>$key</font> : $value <br/>";
            }
        }

        //①、获取用户openid
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();
        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody($this->_order_info['pay_sn'].'订单');//商品描述
        $input->SetAttach($this->_order_info['order_type']);//附加数据
        $input->SetOut_trade_no($this->_order_info['pay_sn']);//商户订单号
        $input->SetTotal_fee($this->_order_info['api_pay_amount']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://123.57.252.16/index.php/Home/Pay/wxnotify/");//支付异步回调地址
        $input->SetTrade_type("JSAPI");//手机支付
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);

        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();

        //③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
        /**
         * 注意：
         * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
         * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
         * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
         */
    }
}
