<?php
namespace Home\Controller;
use Think\Controller;
//抽奖支付控制器
class IndexController extends Controller {
	//回调页面
	public function redirects(){
		if($_GET['code']){
            $wechat = new \Tools\wechat(); 
		    $data = $wechat->getAccesstoken($_GET['code']);
		    session('userinfo', $data);
		    $this->redirect('index');
		}
	}
	  //显示活动详情页
    public function index(){
    	if($_GET['id']){
			$id=$_GET['id'];
			session('id', $id);	
    	}
    	$data = session('userinfo');
    	if(empty($data['openid'])){
    		$url = "http://166xj71935.51mypc.cn/index.php/Home/Index/redirects";
    		$wechat = new \Tools\wechat();
	        $wechat->get_code_by_authorize($url);exit;
    	}
	    $rst = M('action')->where(array('status' => '1','dizhi' => session('id')))->find();
	    if($rst){
		    $cinfo = M('cinfo')->join('tp_action on tp_cinfo.aid=tp_action.id')->where(array('openid' => $data['openid'],'status' =>1,'dizhi' => session('id')))->find();
		    if($cinfo){
		    	$this->assign('ycan', 1);
		    	$this->assign('action', $rst);
		    	$this->display();exit;
		    }
			if($rst['sumperson'] == $rst['maxperson'] || $rst['etime'] < time()){
				$this->assign('maxperson', 2);
				$this->assign('action', $rst);
				$this->display();exit;
			}
			$config = array(
	    		'appid' => C('appid'),
	            'mchid' => C('mchid'),
	            'key' => C('key'),
	            'secret' => C('secret')
	    		);
		    $rst['payment_config'] = $config;
		    $rst['order_sn'] = makeSn(); 
	    	$order_info['subject'] = '幸运抽奖活动支付'.$rst['order_sn'];
	        $order_info['order_type'] = 'choujiang';
	        $order_info['pay_sn'] = $rst['order_sn'];
	        $order_info['api_pay_amount'] = $rst['money'];
		    Vendor('wx.example.wxpay');
	        $payment_api = new \wxpay($rst,$order_info);//使用构造方法初始化一些变量
	        $jsApiParameters = $payment_api->getPay();
	        $this->assign('jsApiParameters', $jsApiParameters);
	    	$this->assign('action', $rst);
		    $this->display();
		}else{
			$rst = M('action')->where(array('status' => '0','dizhi' => session('id')))->find();
			if($rst){
				$this->assign('maxperson', 2);
	    	    $this->assign('action', $rst);
	    	    $this->display();exit;
			}else{
				$this->display('error');exit;
			}
			
		}
}
	    //如果支付成功，显示个人中心页面
        public function member(){
        	if($_GET['code']){
        	   $wechat = new \Tools\wechat(); 
		       $data = $wechat->getAccesstoken($_GET['code']);
		       session('userinfo', $data);
		       $member = M('cinfo')->join('tp_action on tp_cinfo.aid = tp_action.id')->where(array('tp_cinfo.openid' => $data['openid']))->select();
			   $this->assign('member' ,$member);
			   $this->display('member');exit;
        	}else{
        		if($data = session('userinfo')){
        			$member = M('cinfo')->join('tp_action on tp_cinfo.aid = tp_action.id')->where(array('tp_cinfo.openid' => $data['openid']))->select();
			        $this->assign('member' ,$member);
			        $this->display('member');exit;
        		}
        	 	$url = "http://166xj71935.51mypc.cn/index.php/Home/Index/member";
        	 	$wechat = new \Tools\wechat();
	            $wechat->get_code_by_authorize($url);exit;
        	 }
		}
		//更改用户的信息
	    public function saveinfo(){
		   $data = session('userinfo');
			//同步回调
			$tp_action = M('action');
			$action = $tp_action->where(array('status' => 1,'dizhi' => session('id')))->find();
			$tp_action->where(array('id' => $action['id']))->setInc('sumperson',1);
			$tp_action->where(array('id' => $action['id']))->setInc('summoney',$action['money']);
			$payinfo = array(
		            'openid' => $data['openid'],
		            'money' => $action['money'],
		            'time' => time()
		        	);
		    if(M('pay')->add($payinfo)){
				if($action['code'] == 4){
					$number = rand(1000,9999);
				}else{
					$number = rand(100,999);
				}
				$cinfo = array(
					'aid' => $action['id'],
					'openid' => $data['openid'],
					'cname' => $data['nickname'],
					'picture' => $data['headimgurl'],
					'number' => $number,
					'ctime' => time()
					);
				if(M('cinfo')->add($cinfo)){
					echo json_encode(array('code' => 1));exit;
		         }else{
		         	echo json_encode(array('code' => 0));
		         }
             }
    }
    //幸运数字页面
    public function number(){
    	$data = session('userinfo');
    	$cinfo = M('cinfo')->join('tp_action on tp_cinfo.aid=tp_action.id')->where(array('tp_action.dizhi' => session('id'),'openid'=>$data['openid']))->find();
    	$this->assign('number', $cinfo['number']);
    	$this->display();
    }
}