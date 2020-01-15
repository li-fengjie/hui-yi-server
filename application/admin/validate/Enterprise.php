<?php
namespace app\admin\Validate;
use think\Validate;
class Enterprise extends Validate{
    protected $rule=[
    "bname"=>"require|chsAlpha|unique:business",
    'pcode'=>"require"
    ];
    protected $message=[
    "bname.require"=>"企业名必须输入",
    "bname.chsAlpha"=>"企业名只能为汉字或字母",
    "bname.unique"=>"此名称已注册",
    "pcode.require" =>"授权码不能为空",
    ];
    protected $scene=[
    "EnterpriseEdit" => ['pcode'],
    "EnterpriseAdd" =>['bname','pcode']
    ];
}