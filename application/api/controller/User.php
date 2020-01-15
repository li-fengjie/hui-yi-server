<?php
namespace app\api\controller;

use \think\Db;
use \think\Validate;
use \think\Loader;
use \think\Request;
use app\admin\model\User as Users;
use think\Image;
class User
{
    /**
     * 注册用户和管理员
     * @Author    ruozsp
     * @DateTime  2018-01-30
     * @copyright [copyright]
     * @license   [license]
     * @version   [version]
     * @return   json 返回操作结果代号和描述的信息
     */
    public function register()
    {
        $pcode=input("pcode");
        $bname=input("bname");
        $have=Db::table("user")->where("uid", input("uid"))->field("isuse")->find();

        if ($have) {
            return JSON(["code"=>"10000","msg"=>"The account has been registered."]);
        }

        if ($pcode) {
            //管理员创��?
            $data=Db::table("business")->where("pcode", $pcode)->field("bid")->find();
            if ($data) {
                //$userTable = new Users;
                $mangerReg=[
                'uid'=>input ('uid'),
                'uname'=>input('uname'),
                'pwd'=>md5(md5(input('pwd'))),
                'uphone' => input("uphone"),
                'bid'=>$data["bid"],
                'user_type'=>0
                ];
                // $userTable->data($mangerReg);
                /*$validate=Loader::validate("User");
                if($validate->check($userTable)){*/
                $res=Db::table("user")->insert($mangerReg);
                if ($res) {
                    return JSON(['code'=>'20001','msg'=>'Administrator account creation successful.']);
                } else {
                    return JSON(["code"=>"10001","msg"=>"Administrator account creation failed."]);
                }
                /*}else{
                     return JSON(['code'=>'10002','msg'=>$validate->getError()]);
                 }*/
            } else {
                return JSON(['code'=>'10003','msg'=>'code invalid']);
            }
        } elseif ($bname) {
            //用户创建
            $data=Db::table("business")->where("bname", $bname)->field("bid")->find();
            if ($data) {
                //$userTable=new Users;
                $userReg=[
                    'uid'=>input("uid"),
                    'uname'=>input("uname"),
                    'pwd'=>md5(md5(input("pwd"))),
                    "pid"=>input("upid"),
                    "bid"=>$data["bid"],
                    "uphone"=>input("uphone"),
                    "user_type"=>1
                ];
                //$userTable->data($userReg);
                /*$validate=Loader::validate("User");
                 if($validate->check($userTable)){*/
                // $res=$userTable->save();
                $res=Db::table("user")->insert($userReg);

                if ($res) {
                    return JSON(["code"=>"20002","msg"=>"User creation success"]);
                } else {
                    return JSON(["code"=>"10007","msg"=>"User creation failed"]);
                }
                /* }else{
                     return JSON(["code"=>"10006","msg"=>$validate->getError]);
                 }*/
            } else {
                return JSON(["code"=>"10004","msg"=>"Invalid enterprise name"]);
            }
        } else {
            return JSON(["code"=>"10005","msg"=>"Authorization code or enterprise name must be."]);
        }
    }
    /**
     * 用户和管理员登录,通过有无验证码来判断
     * @Author    ruozsp
     * @DateTime  2018-01-30
     * @copyright [copyright]
     * @license   [license]
     * @version   [version]
     * @return    Json 返回操作的结果的代号和秒速信��?
     */
    public function login()
    {
        $pcode=input("pcode");
        $uid=input("uid");
        $pwd=md5(md5(input("pwd")));
        $pid=input("pid");
        $have=Db::table("user")->where("uid", input("uid"))->field("isuse")->find();
        if($have['isuse']===0)
          $pcode.='36274639748dufgydgsyhdg';
        if ($pcode) {
            $data=Db::table("business")->where("pcode", $pcode)->field("bid")->find();
            if ($data) {
                $user=Db::table("user")->where("uid", $uid)->field("pwd,bid")->find();

                if($data["bid"]!==$user["bid"])
                  return JSON(['code'=>'10003','msg'=>'code invalid']);
                if ($user) {
                    if ($user["pwd"]===$pwd) {
                        Db::table("user")->where("uid", $uid)->update(["user_type"=>0]);
                        return JSON(["code"=>"20003","msg"=>"The administrator logged in successfully"]);
                    } else {
                        return JSON(["code"=>"10009","msg"=>"There are errors in the password."]);
                    }
                } else {
                    return JSON(["code"=>"10008","msg"=>"Invalid account"]);
                }
            } else {
                return JSON(['code'=>'10003','msg'=>'code invalid']);
            }
        } else {
            if ($pid) {
                $pidData=Db::table("user")->where("pid", $pid)->field("uid")->find();
                if ($pidData["uid"]==$uid) {
                    Db::table("user")->where("uid", $uid)->update(["user_type"=>1]);
                    //echo Db::table("user")->getLastSql();
                    return JSON(["code"=>"20004","msg"=>"The user logged in successfully"]);
                } else {
                    return JSON(['code'=>"10010","msg"=>"Fingerprint error"]);
                }
            } elseif ($pwd&&$pwd!=="74be16979710d4c4e7c6647856088456") {
                $pwdData=Db::table("user")->where("uid", $uid)->field("pwd")->find();

                if ($pwdData["pwd"]===$pwd) {
                    Db::table("user")->where("uid", $uid)->update(["user_type"=>1]);


                    return JSON(["code"=>"20004","msg"=>"The user logged in successfully"]);
                } else {
                    return JSON(['code'=>"10009","msg"=>"There are errors in the password."]);
                }
            } else {
                return JSON(["code"=>"10011","msg"=>"The input information is by mistake."]);
            }
        }
    }
    /*
    用户信息显示
     */
    public function inforShow()
    {
        $uid=input("uid");

        $userData=Db::table("user")->alias("u")->join("business b", "b.bid=u.bid")->where("uid", $uid)->field("user_type,uname,uid,uphoto,bname")->find();
        if ($userData["user_type"]===1) {
            $meetNum=Db::table("user_sign")->where("uid", $uid)->count();
            //echo  Db::table("user_sign")-->where("uid",$uid)>getLastSql();
            //var_dump($meetNum);
            $issign=Db::table("user_sign")->where("uid", $uid)->where("issign", 1)->count();
            $userData["meetnum"]=$issign.'/'.$meetNum;
        //return JSON([""]);
        } elseif ($userData["user_type"]===0) {
            $meetNum=Db::table("meet_create")->where("uid", $uid)->field("mid")->count();
            $userData["meetnum"]=$meetNum;
        } else {
            return JSON(["code"=>"10024","msg"=>"Invalid uid"]);
            return ;
        }
        return JSON(["code"=>"20013","msg"=>"Success in obtaining information","result"=>$userData]);
    }
    public function feedback()
    {
        $data=[
            "uid"=>input("uid"),
            "feedback_content"=>input("feedbackContent")
        ];
        $res=Db::table("feedback")->insert($data);
        if ($res) {
            return JSON(["code"=>"20014","msg"=>"Feedback is successful."]);
        } else {
            return JSON(["code"=>"10025","msg"=>"fail"]);
        }
    }
    public function camera(){
      return $this->fetch("camera");
    }
    public function pictureToBase64(){
      $uphoto=request()->file("uphoto");
      $info = $uphoto->move(ROOT_PATH . 'public' . DS . 'upload');
      $data = ROOT_PATH . 'public' .DS . 'upload' . DS . $info->getSaveName();
      $fp = fopen($data,"rb", 0);
      $gambar = fread($fp,filesize($data));
      fclose($fp);
      $base64 = chunk_split(base64_encode($gambar));

      return JSON(["base64"=>$base64]);
    }
    public function uploadPicture()
    {
      $uid=input("uid");
      if(input("uphoto"))
          $base64=input("uphoto");
      else
          $base64=input("mimage");
    $base64=User::urlsafe_b64decode($base64);
    $img = base64_decode($base64);
    $url=ROOT_PATH.'public'.DS."upload";
    $name=time().'.png';
    $Pictureurl=$url.DS.$name;
    $a=file_put_contents($Pictureurl, $img);
    $url='http://123.207.120.57/huiyi/'.'public'.DS."upload".DS.$name;
    if($uid){
      $res=Db::table("user")->where("uid", $uid)->update(["uphoto"=>$url]);
    }else{
      $mid=input("mid");
      $res=Db::table("meet_create")->where("mid",$mid)->update(["mimage"=>$url]);
    }
    if ($res!==false) {
        return JSON(["code"=>"20020","msg"=>"upload success"]);
    }else {
        return JSON(["code"=>"10026","msg"=>"upload fail"]);
    }
    }
    public static function urlsafe_b64encode($string) {

        $data = base64_encode($string);

         $data = str_replace(array('+','/','='),array('-','_',''),$data);

         return $data;

     }
    public static function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-','_'), array('+','/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}
