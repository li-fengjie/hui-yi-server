<?php
namespace app\admin\controller;
use \think\Db;
use think\Session;
use \app\common\Helper\encryptionToDecryptHelper;
class Index extends \think\Controller {
	function login() {
		if(input("post.user") && input("post.password")) {
			$user = input("post.user");
			$password = encryptionToDecryptHelper::encrypt(input("post.password"),"E");
			$admin = Db::table("admin")->where("aname",$user)->find();
			if(!$admin){
				$this->error("账号不存在");
			}
			$verify=input("post.verify");
			if(!\app\common\Helper\verifyHelper::check($verify))
				$this->error("验证码有误");
			if($admin['is_use']!==1)
					$this->error("你已被禁用");
			if($admin['apassword'] == $password){
				Session::set("aid",$admin['aid']);
				DB::table("admin")->where("aid",Session::get("aid"))->update(["time"=>time()]);
				$this->success('登录成功!','admin/base/index');
			}else{
				$this->error("登录失败!");
			}
		}
		else {
			return $this->fetch("login");
		}
	}
	function loginOut(){

		 Session::delete('aid');
		 Session::delete('verify_code');
		 $this->redirect('admin/index/login');
	}
	function index() {
		 if(!Session::get('aid'))
	   		$this->redirect('admin/index/login');
		return $this->fetch("index");
	}
    function verify(){
     \app\common\Helper\verifyHelper::verify();
    }
		function welcome() {
			return $this->fetch("welcome");
		}
}
