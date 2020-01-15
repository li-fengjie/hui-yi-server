<?php
namespace app\index\controller;
use \think\Db;
class Index
{
    function login() {
    	$request = \think\Request::instance();
    	$phone = $request->param("phone");
    	$password = md5(md5($request->param("password")));
    	if($phone && $password) {
    		$user = Db::table('user')->where('phone',$phone)->find();
    		if($user['password'] == $password)
    			echo "success";
    		else
    			echo "passError";
    	}
    	else 
    		echo "emptyError";
    }
    function register() {
    	$request = \think\Request::instance();
    	//dump($request);
    	$phone = $request->param("phone");
    	$password = $request->param("password");
    	$user_type = $request->param("user_type");
    	$inviter_phone = $request->param("inviter_phone");
    	if($phone && $password && $user_type) {
    		$password = md5(md5($password));
    		$data = [
    			'phone' => $phone,
    			'password' => $password,
    			'user_type' => $user_type,
    			'inviter_phone' => $inviter_phone
    		];
    		$result = Db::table('user')->insert($data);
    		if($result)
    			echo "success";
    		else
    			echo "insertError";
    	}
    	else
    		echo "emptyError";
    }
    function editUser() {
        // 个人基本信息
        $request = \think\Request::instance();
        $user_name = $request->param("user_name");
        $status = $request->param("status");
        $age = $request->param("age");

        //擅长工种以及目前所在地

        //找工作要求
        
    }
}
