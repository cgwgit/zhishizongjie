<?php
namespace Think\Lib\Alidayu;
include('TopSdk.php');
//就是下面两句把我搞惨了，别嫌弃，我只是入门了而已
use TopClient; 
use AlibabaAliqinFcSmsNumSendRequest;
class SendMSM {
    //$recNum短信接收的号码
    //$smsParam短信模板变量json格式，例如：传入{"code":"1234","product":"alidayu"}
    // 短信模板变量，传参规则{"key":"value"}，key的名字须和申请模板中的变量名一致，多个变量之间以逗号隔开。示例：针对模板“验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”，传参时需传入{"code":"1234","product":"alidayu"}
    //$smsTemplateCode
    // 短信模板ID，传入的模板必须是在阿里大于“管理中心-短信模板管理”中的可用模板。示例：SMS_585014?
    // $smsFreeSignName短信签名
    // 短信签名，传入的短信签名必须是在阿里大于“管理中心-短信签名管理”中的可用签名。如“阿里大于”已在短信签名管理中通过审核，则可传入”阿里大于“（传参时去掉引号）作为短信签名。短信效果示例：【阿里大于】欢迎使用阿里大于服务。SMS_39360132
    public function send($recNum='', $smsParam='', $smsTemplateCode='SMS_46955038', $smsFreeSignName='前程保'){
        $c = new TopClient;
        $c->format = "json";
        //在配置文件中做相应的配置appid和secret
        $c->appkey = C('AlidayuAppKey');
        $c->secretKey = C('AlidayuAppSecret');
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        //$req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName($smsFreeSignName);
        $req->setSmsParam($smsParam);
        $req->setRecNum($recNum);
        $req->setSmsTemplateCode($smsTemplateCode);
        $resp = $c->execute($req);
        return $resp;
    }
    
}