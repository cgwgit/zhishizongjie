<?php
//网页授权获取用户信息
namespace Tools;
//定义一个wechat类，用来存放微信接口请求的一些方法
class wechat{
  //封装 公有 私有 被保护的
  //封装属性为私有化，更加安全

  //构造，创建对象自动调用的一个方法
  //微信公众平台认证方法
     private $code = '';
     private $openid="";
     private $accesstoken="";
    public function valid()
      {
          $echoStr = $_GET["echostr"];
          if($this->checkSignature()){
            echo $echoStr;
            exit;
          }
      }
    //检查签名方法
    private function checkSignature()
    {
      if (!defined("TOKEN")) {
          throw new Exception('TOKEN is not defined!');exit;
      }
      $signature = $_GET["signature"];
      $timestamp = $_GET["timestamp"];
      $nonce = $_GET["nonce"];
      $token = TOKEN;
      $tmpArr = array($token, $timestamp, $nonce);
      sort($tmpArr, SORT_STRING);
      $tmpStr = implode( $tmpArr );
      $tmpStr = sha1( $tmpStr );
      if( $tmpStr == $signature ){
        return true;
      }else{
        return false;
      }
    }
  //封装请求方法
  public function request($url,$https=true,$method='get',$data=null){
    //1.初始化url
    $ch = curl_init($url);
    //2.设置相关的参数
    //字符串不直接输出,进行一个变量的存储
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //判断是否为https请求
    if($https === true){
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    //判断是否为post请求
    if($method == 'post'){
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    //3.发送请求
    $str = curl_exec($ch);
    //4.关闭连接
    curl_close($ch);
    //返回请求到的结果
    return $str;
  }
  //用户通过微信公众号跳转网页，如何获取该用户的openid呢
  //1、进入微信公众平台后台后，依次进入 服务-我的服务，找到OAuth2.0网页授权，
//点击右侧的修改授权回调域名配置规范为全域名并且不带http
//用户点击的url地址"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcd983566d32442bc&redirect_uri=http://192.168.1.1/weixin/weixin.do?action=viewtest&response_type=code&scope=snsapi_base&state=1#wechat_redirect" 
//2、在回调地址redirect_url中得到code 的值
//3、通过code获取用户的openid
//4、请求上面的https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openID.'&lang=zh_CN';即可获得用户的信息
   	/**
 	 * 授权获取code(微信授权第一步)
 	 */
 	function get_code_by_authorize($redirect_uri){
 		$APPID=C('appid');
 		$url_get_code="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$APPID&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
 		header("Location: $url_get_code");//重定向请求微信用户信息		
 	}

 	//通过code获取accesstoken
 	public function getAccesstoken($Code){
 		$APPID=C('appid');
 		$SECRET=C('secret');
 		$code=$Code;
 		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=$APPID&secret=$SECRET&code=$code&grant_type=authorization_code";
		$content=$this->request($url);
		$o=json_decode($content,true);
		$this->accesstoken = $o['access_token'];
		$this->openid = $o['openid'];
		return $this->getUserinfo();
 	}
 	//通过openid获取用户信息
 	public function getUserinfo(){
 		$access_token = $this->accesstoken;
 		$openid = $this->openid;
 		$url= "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
 		$content2 = $this->request($url);
 		$o2=json_decode($content2,true);
 		return $o2;
 	}
}