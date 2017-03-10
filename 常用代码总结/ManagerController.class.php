<?php
namespace Home\Controller;
use Think\Controller;
class ManagerController extends Controller {
	//登录
    public function login(){
	    if(IS_AJAX){
	           $post = I('post.');
	           !empty($post['name']) ? $name = trim($post['name']) : $err = '用户名格式不正确';
	           !empty($post['pwd']) ? $pwd = trim($post['pwd']) : $err = '密码格式不正确';
	           if($err){
	            echo json_encode(array('code' =>0,'msg' => $err));exit;
	           }
	           $data = array(
	                 'name' => $name,
	                 'pwd' => $pwd
	            );
	           $rst = M('user')->where($data)->find();
	           if($rst){
	            session('uid', $rst['id']);
	            session('uname', $rst['name']);
	            session('utype' , $rst['utype']);
	            echo json_encode(array('code' =>1,'msg' => '登录成功'));exit;
	           }else{
	            echo json_encode(array('code' =>0,'msg' => '用户名或密码不正确'));exit;
	           }
	    }else{
	      $this->display();
	    }
  }
	//退出按钮
	public function loginout(){
        session(null);
		$this->display('login');
	}
}
