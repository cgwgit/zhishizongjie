<?php
//引入配置文件
require './wechat.cfg.php';
//定义一个wechat类，用来存放微信接口请求的一些方法
class Wechat{
  //封装 公有 私有 被保护的
  //封装属性为私有化，更加安全
  private $appid;
  private $appsecret;
  private $token;
  //构造，创建对象自动调用的一个方法
  public function __construct(){
    //给对象属性赋值，方便后面方法的调用和使用
    $this->appid = APPID;
    $this->appsecret = APPSECRET;
    $this->token = TOKEN;
    $this->textTpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[%s]]></MsgType>
                      <Content><![CDATA[%s]]></Content>
                      <FuncFlag>0</FuncFlag>
                      </xml>";
    $this->newsTpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[news]]></MsgType>
                      <ArticleCount>%s</ArticleCount>
                      <Articles>%s
                      </Articles>
                      </xml>";
    $this->item = "<item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>";
    $this->musicTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[music]]></MsgType>
                        <Music>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <MusicUrl><![CDATA[%s]]></MusicUrl>
                        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                        </Music>
                        </xml>";
  }
  //微信公众平台认证方法
    public function valid()
      {
          $echoStr = $_GET["echostr"];
          if($this->checkSignature()){
            echo $echoStr;
            exit;
          }
      }
//处理所有的微信相关信息请求和响应的处理
      public function responseMsg()
      {
      //也就是说基本上$GLOBALS['HTTP_RAW_POST_DATA'] 和 $_POST是一样的。
      //但是如果post过来的数据不是PHP能够识别的，你可以用 $GLOBALS['HTTP_RAW_POST_DATA']来接收，比如 text/xml 或者 soap 等等。
      $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
          //extract post data
      if (!empty($postStr)){
                  /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                     the best way is to check the validity of xml by yourself */
                  //xml安全设置操作，微信建议开启的
                  libxml_disable_entity_loader(true);
                  //postStr数据进行解析
                  $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                  switch ($postObj->MsgType) {
                    case 'text':
                      $this->_doText($postObj);   //处理文本信息的方法
                      break;
                    case 'image':
                      $this->_doImage($postObj);  //处理图片消息的方法
                      break;
                    case 'location':
                      $this->_doLocation($postObj); //处理位置信息的方法
                      break;
                    case 'event':
                      $this->_doEvent($postObj);    //处理事件消息的方法
                      break;
                    default:
                      # code...
                      break;
                  }
          }
      }
    /*
     * 处理事件类型
     * Time:2016年7月10日16:14:20
     * By:php47
     */
    private function _doEvent($postObj){
      //判断event值的类型，进行相对应的操作
      switch ($postObj->Event) {
        case 'subscribe':
          $this->_doSubscribe($postObj);  //处理关注事件
          break;
        case 'unsubscribe':
          $this->_doUnsubscribe($postObj); //处理取消关注事件
          break;
        case 'CLICK':
          $this->_doClick($postObj);  //处理自定义菜单点击事件
          break;
        default:
          # code...
          break;
      }
    }
    /*
     * 点击事件的处理
     * Time:2016年7月10日16:51:53
     * By:php47
     *
     */
    private function _doClick($postObj){
      switch ($postObj->EventKey) {
        case 'news':
          $this->_sendTuwen($postObj); //处理news Click值的方法，发送图文方法
          break;

        default:
          # code...
          break;
      }
    }
    /*
     * 发送音乐信息
     * Time:2016年7月10日17:22:48
     * By:php47
     *
     */
    private function _sendMusic($postObj){
      //1.歌曲信息的组合
      $Title = '小歌曲';
      $Description = '小歌曲';
      $MusicUrl = 'http://so1.111ttt.com:8282/2016/1/06/20/199201048457.mp3?tflag=1466389833&pin=70d37142ea5d912f168918986e2e5ad1';
      $HQMusicUrl = 'http://so1.111ttt.com:8282/2016/1/06/20/199201048457.mp3?tflag=1466389833&pin=70d37142ea5d912f168918986e2e5ad1';
      $ThumbMediaId = 'Q4LZnvVfOWowvVj2q0z4X2YrTqqj2MsOD8SWN8cckROpAaMZ05STV5wWg9aaIHsW';
      //2.组合模板
      $resultStr = sprintf($this->musicTpl, $postObj->FromUserName, $postObj->ToUserName, time(), $Title, $Description, $MusicUrl, $HQMusicUrl, $ThumbMediaId);
      //3.输出模板信息
      echo $resultStr;
    }
    /*
     * 发送图文方法
     * Time:2016年7月10日17:00:49
     * By:php47
     *
     */
    private function _sendTuwen($postObj){
      //组合新闻数组
       $newsList = array(
            array(
                        'Title' => ' 决战！C罗还差一步加冕欧洲之王！',
                        'Description' => '经过了一个多月的鏖战，由24路诸侯几百名球员联袂出演的法兰西之夏即将迎来最终结局。两张决赛门票一张属于东道主法国，经过了一个多月的鏖战，由24路诸侯几百名球员联袂出演的法兰西之夏即将迎来最终结局。两张决赛门票一张属于东道主法国，另一张则是属于低开高走，常规时间五平一胜杀进决赛的葡萄牙。而两支队伍的一切奋斗与汗水都将在7月11日得到答案。是留下一个伤心的背影，抑或是带走所有的蛋糕。',
                        'PicUrl' => 'http://img1.gtimg.com/sports/pics/hv1/233/90/2096/136315583.jpg',
                        'Url' => 'http://sports.qq.com/fans/post.htm?id=1539363816777711645&mid=142#1',
            ),
            array(
                        'Title' => '球探-法国新磐石武装巴萨 欧洲杯36年第一人',
                        'Description' => '腾讯体育7月8日讯 法国淘汰德国杀入欧洲杯决赛，同时也是队史上首次在欧洲杯零封日耳曼战车，蓝衣军防线表现出色，尤其是新锐国脚乌姆蒂蒂，连续两战打出高水准，巴萨斥资2500万欧元提前将其购入，实属明智。',
                        'PicUrl' => 'http://img1.gtimg.com/sports/pics/hv1/92/208/2095/136280507.jpg',
                        'Url' => 'http://sports.qq.com/a/20160708/026374.htm',
                        ),
        );
       $items = '';
       //循环输出item模板
       foreach ($newsList as $key => $value) {
         $items .= sprintf($this->item, $value['Title'], $value['Description'], $value['PicUrl'], $value['Url']);
       }
       $contentStr = sprintf($this->newsTpl, $postObj->FromUserName, $postObj->ToUserName, time(), count($newsList), $items);
       echo $contentStr;
    }
    /*
     * 关注事件处理
     * Time:2016年7月10日16:21:01
     * By:php47
     *
     */
    private function _doSubscribe($postObj){
      $contentStr = '欢迎关注我们，我们是php47期，请常联系！';
      $resultStr = sprintf($this->textTpl, $postObj->FromUserName, $postObj->ToUserName, time(), "text", $contentStr);
      echo $resultStr;
    }
    /*
     * 取消关注事件处理
     * Time:2016年7月10日16:28:31
     * By:php47
     *
     */
    private function _doUnsubscribe($postObj){
      //删除用户的一些信息获取绑定的相关操作
    }

    /*
     * 处理图片消息
     * Time:2016年7月10日15:51:09
     * By:php47
     *
     */
    private function _doImage($postObj){
      //把接收到图片地址链接以文本形式返回
      $PicUrl = $postObj->PicUrl;
      //保存文件到服务器
      // $pic = $this->request($PicUrl,false);
      // file_put_contents('./pic.png',$pic);
      $resultStr = sprintf($this->textTpl, $postObj->FromUserName, $postObj->ToUserName, time(), "text", $PicUrl);
      echo $resultStr;
    }
    /*
     * 处理位置消息
     * Time:22016年7月10日16:03:23
     * By:php47
     *
     */
    private function _doLocation($postObj){
      //组合x,y数据
      $contentStr = '您当前x为：'.$postObj->Location_X.',Y为：'.$postObj->Location_Y;
      $resultStr = sprintf($this->textTpl, $postObj->FromUserName, $postObj->ToUserName, time(), "text", $contentStr);
      echo $resultStr;
    }
    /*
     *  处理文本信息
     *  Time:2016年7月10日15:02:30
     *  By:php47
     */
    private function _doText($postObj){
      // file_put_contents('./test','11111');
      $keyword = trim($postObj->Content);
      if(!empty( $keyword ))
              {
                $msgType = "text";
                // $contentStr = "Welcome to wechat world!";
                //1.机器人api地址
                $url = 'http://api.qingyunke.com/api.php?key=free&appid=0&msg='.$keyword;
                //2.get请求，直接发送
                $contents = $this->request($url,false);
                //3.处理返回值
                //json转化为对象信息
                $contents = json_decode($contents);
                //输出返回的信息
                $contentStr = $contents->content;
                if($keyword == '歌曲'){
                  $this->_sendMusic($postObj);
                  exit;
                }
                if($keyword == 'php47'){
                  $contentStr = "我们是php47期学员！";
                }
                $resultStr = sprintf($this->textTpl, $postObj->FromUserName, $postObj->ToUserName, time(), $msgType, $contentStr);
                echo $resultStr;
              }
    }
//检查签名方法
    private function checkSignature()
    {
          // you must define TOKEN by yourself
          if (!defined("TOKEN")) {
              throw new Exception('TOKEN is not defined!');
          }
          $signature = $_GET["signature"];
          $timestamp = $_GET["timestamp"];
          $nonce = $_GET["nonce"];

      $token = $this->token;
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
  /*
   * 获取access_token（获取很多借口的凭证）
   * Time:2016年7月9日11:25:24
   * By: php47
   *
  */
   public function getAccessToken(){
    //1.url地址
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
    //2.get方式，直接发送放松请求
    $content = $this->request($url);
    //3.处理返回值,先看看
    //返回值json字符串，pph不能直接进行操作，把它传化为一个对象或者数组进行操作
    $content = json_decode($content);
    $access_token = $content->access_token;
    //把access_token缓存到本地文件
    // file_put_contents('./accesstoken', $access_token);
    //通过对象，调用其属性值
    // echo $access_token;
    // var_dump($content);
    //返回获取的access_token的值
    return $access_token;
   }
   /*
    * 直接读取缓存中的access_token
    * Time:2016年7月9日11:45:36
    * By:php47
    *
   */
   public function getAccessTokenCache(){
    //读取文件获取缓存数据
    $access_token = file_get_contents('./accesstoken');
    //打印输出缓存值
    echo $access_tokenl;
    //如果其他方法要调用获取的话
    // return $access_token;
   }
   /*
    * 获取二维码的ticket票据
    * Time:2016年7月9日15:08:27
    * By:php47
    *
    */
   public function getTicket($tmp=0,$scene_id=123){
    //1.url地址
    $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->getAccessToken();
    //2.组合post数据
    if($tmp == '1'){
      $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
    }else{
      $data = '{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
    }
    //3.携带post数据，进行发送请求
    $content = $this->request($url,true,'post',$data);
    //4.处理返回值
    //json转化为对象
    $content = json_decode($content);
    //使用对象去调用其属性并输出，也就是我们要的ticket值
    echo $content->ticket;
   }
   /*
    * 使用ticket换取二维码
    * Time:s2016年7月9日15:27:28
    * By:php47
    *
    *
    */
   public function getQRCode(){
    $ticket = 'gQH28ToAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL3dUajhNOUxseHVLLWdJazZFaGJjAAIEhaWAVwMEgDoJAA==';
    //1.url地址
    $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
    //2.get方式，直接发送请求
    $content = $this->request($url);
    //3.处理返回值
    //保存到本地
    file_put_contents('./qrcode.jpg',$content);
   }
  /*
   * 删除菜单操作
   * Time:2016年7月9日16:59:22
   * By:php47
   *
   */
  public function delMenu(){
    //1.url地址
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$this->getAccessToken();
    //2.get请求方式，直接发送请求
    $content = $this->request($url);
    //3.处理返回值
    //把json转化成一个对象
    $content = json_decode($content);
    //业务逻辑判断
    if($content->errmsg == 'ok'){
      echo '删除菜单成功!';
    }else{
      echo '删除失败,错误代码为:'.$content->errcode;
    }
  }
  /*
   * 创建菜单操作
   * Time:2016年7月9日17:10:06
   * By:php47
   *
   */
  public function createMenu(){
    //1.url地址
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getAccessToken();
    //2.组合post数据，这块也就是我们要创建菜单的数据
    $data = '{
              "button":[
              {
                   "type":"click",
                   "name":"最新资讯",
                   "key":"news"
               },
               {
                    "name":"php47更多",
                    "sub_button":[
                    {
                        "type":"view",
                        "name":"H5主页",
                        "url":"http://panteng.me/demos/whb/"
                     },
                     {
                        "type":"view",
                        "name":"百度",
                        "url":"http://www.baidu.com"
                     },
                     {
                        "name": "发送位置2",
                        "type": "location_select",
                        "key": "rselfmenu_2_0"
                    }]
                }]
          }';
    //3.携带post数据，发送请求
    $content = $this->request($url,true,'post',$data);
    //4.处理返回值
      //把json转化成一个对象
      $content = json_decode($content);
      //业务逻辑判断
      if($content->errmsg == 'ok'){
        echo '创建菜单成功!';
      }else{
        echo '创建失败,错误代码为:'.$content->errcode;
      }
    }
  /*
   * 查询菜单操作
   * Time:2016年7月9日17:18:56
   * By:php47
   *
   */
  public function showMenu(){
    //1.url地址
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->getAccessToken();
    //2.get方式，直接发送请求
    $content = $this->request($url);
    //3.返回值处理,最简单的处理，就是打印出来看看
    var_dump($content);
  }
  /*
   * 获取用户openID列表（总的关注该公众号的openid列表）
   * Time:2016年7月10日09:38:20
   * By:php47
   *
   */
  public function getUserList(){
    //1.url地址
    $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->getAccessToken();
    //2.get方式，直接发送请求
    $content = $this->request($url);
    //3.处理返回值
    //json字符串转化为对象
    $content = json_decode($content);
    echo '用户关注数为:'.$content->total.'<br />';
    echo '本次拉取数量:'.$content->count.'<br />';
    $openIDList = $content->data->openid;
    foreach ($openIDList as $key => $value) {
      echo '<a href="./getuserinfo.php?openid='.$value.'">'.$value.'</a><br />';
    }
  }
  /*
   * 通过openID获取用户基本信息
   * Time:2016年7月10日10:28:42
   * By:php47
   *
   */
  public function getUserInfo(){
    // $openID = 'oGMVlwzYu2pScveNNbrtIzx1L6F8';
    $openID = $_GET['openid'];
    //1.url地址
    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openID.'&lang=zh_CN';
    //2.get请求，直接发送
    $content = $this->request($url);
    //3.处理返回值
    //json转化为对象
    $content = json_decode($content);
    // var_dump($content);
    // die();
    echo '昵称:'.$content->nickname.'<br />';
    echo '性别:'.$content->sex.'<br />';
    echo '省份:'.$content->province.'<br />';
    echo '头像:'.'<img src="'.$content->headimgurl.'"/><br />';
  }
  //用户通过微信公众号跳转网页，如何获取该用户的openid呢
  //1、进入微信公众平台后台后，依次进入 服务-我的服务，找到OAuth2.0网页授权，
//点击右侧的修改授权回调域名配置规范为全域名并且不带http
//用户点击的url地址"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcd983566d32442bc&redirect_uri=http://192.168.1.1/weixin/weixin.do?action=viewtest&response_type=code&scope=snsapi_base&state=1#wechat_redirect" 
//2、在回调地址redirect_url中得到code 的值
//3、通过code获取用户的openid
//4、请求上面的https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openID.'&lang=zh_CN';即可获得用户的信息
}