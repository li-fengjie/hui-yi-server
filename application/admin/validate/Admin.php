<?php
namespace app\admin\Validate;
use think\Validate;
class Admin extends Validate{
    protected $rule=[
      "aname" => "require|chsAlpha|unique:admin",
      "apassword" => "require|length:8,32",
      "aphone"=>"require|length:11|unique:admin",
      "aemail"=>"require|email|unique:admin",
      "note"=>"length:0,255",
    ];
    protected $message=[
      "aname.require"=>"管理员名字必须输入",
      "aname.unique" => "管理员名字不能重复",
      "apassword.require"=>"密码必须输入",
      "aphone.require" =>"手机号码必须输入",
      "aemail.require" =>"email地址必须输入",
      "note.length"=>"备注不能超过255个字",
      "aname.chsAlpha" =>"管理员名字只能是字母或汉字",
      "apassword.length" =>"密码不能超过20个字符，不能低于8个字符",
      "aphone.length" =>"亲!号码是十一位的!",
      "aphone.unique"=>"手机号码不能重复注册",
      "aemail.unique" =>"email不能重复注册",
      "aemail.email"=>"请输入正确email"
    ];
}
