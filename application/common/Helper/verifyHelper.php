<?php

namespace app\common\helper;

use \app\common\Captcha\CaptchaBuilder;

use \think\Session;

class verifyHelper{

    public static function verify()

    {

        $builder = new CaptchaBuilder;

        $builder->build()->output();

        Session::set('verify_code', $builder->getPhrase());

    }

    public static function check($code)

    {

        return ($code == Session::get('verify_code') && $code != '') ? true : false;

    }

}
