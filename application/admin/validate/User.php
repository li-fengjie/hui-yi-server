<?php
namespace \app\admin\validate;
use \think\validate;
class User extends Validate{


    protected $rule=[
    'uid'=>"require|alphaNum|unique:user",
    "uname"=>"require|chaAlpha",
    "uphone"=>"length:11"
    ];
    protected $message=[
    "uid.require"=>"User accounts are required.",
    "uid.alphaNum"=>"User accounts can only be Numbers and letters.",
    "uid.unique"=>"User accounts cannot be duplicated.",
    "uname.require"=>"The username is required.",
    "uname.chaAlpha"=>"The user name can only be Chinese and Chinese.",
    "uphone.length"=>"The telephone number is eleven."
    ];
}
