<?php
class duanxinController{
    //云通讯获取验证码的接口
    public function checkcode(){
    	$code = rand(1000,9999);
        session('code', $code);
        $to = $_GET['telphone'];
        // $code需要发送的验证码，1为验证码失效的时间为1分钟
        $datas = array($code,1);
        $tempId = 1;
        $accountSid= '8aaf070855c4a7270155ca34b0be09e6';
		//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
		$accountToken= 'd85d9b1323a04280aa8921e2b5c1a2c8';
		//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
		//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
		$appId='8aaf070855c4a7270155ca34b11b09ec';
		//请求地址
		//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
		//生产环境（用户应用上线使用）：app.cloopen.com
		$serverIP='sandboxapp.cloopen.com';
		//请求端口，生产环境和沙盒环境一致
		$serverPort='8883';
		//REST版本号，在官网文档REST介绍中获得。
		$softVersion='2013-12-26';
		/**
		  * 发送模板短信
		  * @param to 手机号码集合,用英文逗号分开
		  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
		  * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
		  */
        Vendor('duanxin.duanxin');
        $rest = new \REST($serverIP,$serverPort,$softVersion);
	    $rest->setAccount($accountSid,$accountToken);
	    $rest->setAppId($appId);
	    // 发送模板短信
	    $result = $rest->sendTemplateSMS($to,$datas,$tempId);
	    // var_dump($result);die;
	    if($result == NULL ) {
	       return false;
	    }
	    if($result->statusCode!=0) {
	       return false;
	    }else{
	        return true;
	    }
	}

    //阿里大鱼短信调用方法
	public function aliduanxin(){
		$code = rand(1000,9999);
		//code为要发送的验证码，product为模板内容中的标签
		$data = array('code' => "{$code}",'product' => '前程保');
		cookie('code', $code,3600);
		$datas = json_encode($data);
        $to = $_GET['telphone'];
        $alidayu = new \Think\Lib\Alidayu\SendMSM();
        $result = $alidayu->send($to,$datas);
        if($result->err_code == 0){
        	echo 1;
        }else{
        	echo $result->err_code;
        }
    }

}