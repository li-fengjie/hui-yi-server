<?php

namespace app\api\controller;

use \think\Db;

use \think\Validate;

use \think\Loader;



use \app\admin\model\Meet as Meets;

class Meet extends \think\Controller
{

    /**

     * 会议的创��?
     * @Author    ruozsp

     * @DateTime  2018-01-30

     * @copyright [copyright]

     * @license   [license]

     * @version   [version]

     * @return    json 返回操作的结果和信息

     */

    public function meetCreate()
    {
        $mid=$this->meetIdGenerate();
        $mstartime=strtotime(input("mstarttime"));
        $endtime=strtotime(input("mendtime"));
        if($mstartime>=$endtime)
          return JSON(["code"=>"10033","msg starttime and endtime have problem"]);
        $meetInfo=[

            "mid"=>$mid,

            "mtitle"=>input("mtitle"),

            "mcontent" => input("mcontent"),

            "uid" => input("uid"),
            "maddress"=>input("maddress"),

            "mstartime" => strtotime(input("mstarttime")),

            "mendtime" => strtotime(input("mendtime")),

            "wlanmac" =>input("wlanmac"),

            "bluetoothmac" => input("bluetoothmac")

        ];
        $uidSet=explode(",", input("uidset"));


        $res=Db::table("meet_create")->insert($meetInfo);

        if ($res) {
            $meetMember=count($uidSet);

            if ($meetMember!==1) {
                for ($i=0;$i<$meetMember;$i++) {
                    if ($uidSet[$i]) {
                        $res=DB::table("user_sign")->insert(["uid"=>$uidSet[$i],"mid"=>$mid]);
                    }

                    if ($res) {
                        continue;
                    } else {
                        return JSON(["code"=>"10014","msg"=>"Select the meeting member to fail."]);
                    }
                }
            } else {
                return JSON(["code"=>"10015","msg"=>"Please select the members of the meeting."]);
            }

            return JSON(["code"=>"20030","msg"=>"Create a meeting","mid"=>$mid]);
        } else {
            return JSON(["code"=>"10030","msg"=>"create fail "]);
        }
    }

    /**

     * 选择会员

     * @Author    ruozsp

     * @DateTime  2018-01-30

     * @copyright [copyright]

     * @license   [license]

     * @version   [version]

     * @return    json {uid:xxxxxxx,uname,uphone:xxxxx,uphoto}

     */

    public function selectMember()
    {
        $uid=input("uid");

        $mid=input("mid");



        $res=Db::table("user")->where("uid", $uid)->field("bid")->find();

        if ($res) {
            $userData=Db::table("user")->where("bid", $res["bid"])->where("uid", "<>", $uid)->field("uid,uname,uphone,uphoto")->select();

            if ($userData) {
                if (!$mid) {
                    return JSON(["code"=>"20005","msg"=>"Get the enterprise members to succeed.","result"=>$userData]);
                } else {
                    $selectedUser=Db::table("user_sign")->where("mid", $mid)->field("uid")->find();

                    if ($selectUser) {
                        foreach ($userData as $key => $value) {
                            if (in_array($userData['uid'], $selectedUser)) {
                                $value["slected"]=true;
                            }
                        }

                        return JSON(["code"=>"20008","msg"=>"The selected member data has been selected successfully.","result"=>$userData]);
                    } else {
                        return JSON(["code"=>"10020","msg"=>"Invalid meeting ID"]);
                    }
                }
            } else {
                return JSON(["code"=>"10017","msg"=>"Get the enterprise members to fail."]);
            }
        } else {
            return JSON(["code"=>"10016","msg"=>"You have no work unit, please improve the information."]);
        }

        /*}else{

           //该成员已选诶;

           }

        }*/
    }

    /**

     * 会议ID生成

     * @Author    ruozsp

     * @DateTime  2018-01-30

     * @copyright [copyright]

     * @license   [license]

     * @version   [version]

     * @return    $mid 字符��?
     */

    public function meetIdGenerate()
    {
        $mid='';

        $start=10000000000000;

        $mid.=mt_rand($start, $start*10-1);

        $res=Db::table("meet_create")->where("mid", $mid)->find();

        if (!$res) {
            return $mid;
        } else {
            meetIdGenerate();
        }
    }

    /**

     * 会议列表

     * @Author    ruozsp

     * @DateTime  2018-01-30

     * @copyright [copyright]

     * @license   [license]

     * @version   [version]

     * @return    json {mid:xxxx,mtitle:xxxxx,maddress:xxxxx,mstartime:xxxxxx,mendstartime:xxxxxx}

     */

    public function meetList()
    {
        $uid=input("uid");
        $pageNo=input("pageNo");
        $data=Db::table("user")->where("uid", $uid)->field("user_type")->find();

        if ($data["user_type"]===0) {
            if ($uid) {
           $meetData=DB::table("meet_create")
            ->where("uid", $uid)
            ->field("mid,mtitle,mimage,maddress,mstartime,mendtime")
            ->where("mendtime", ">=", time())
            ->limit($pageNo*15, 15)
            ->order("mstartime desc")
            ->select();
              if ($meetData) {
                    foreach ($meetData as $key => $value) {
                        $meetData[$key]["mlstartime"]=date("Y-m-d H:i", $value["mstartime"]);
                        $meetData[$key]["mlendtime"]=date("Y-m-d H:i", $value["mendtime"]);
                    }

                    return JSON(["code"=>"20006","msg"=>"Get the list of meetings to be successful.","result"=>$meetData]);
                } else {
                    return JSON(["code"=>"10019","msg"=>"The database is not available."]);
                }
            } else {
                return JSON(["code"=>"10018","msg"=>"Get the list of meeting data failed."]);
            }
        } else {

            $userMeetData=Db::table("user_sign")

                ->alias("us")

                ->join("meet_create mc", "mc.mid=us.mid")

                ->join("user u", "u.uid=mc.uid")

                ->where("us.uid", $uid)
            ->where("mendtime", ">=", time())
            ->limit($pageNo*15, 15)
            ->order("mstartime desc")
            ->field("mc.mid,mtitle,mstartime,mendtime,mimage,maddress,wlanmac,bluetoothmac,uname")

            ->select();

            var_dump($userMeetData);
            if ($userMeetData) {
                foreach ($userMeetData as $key => $value) {
                    $userMeetData[$key]["mlstartime"]=date("Y-m-d H:i", $value["mstartime"]);
                    $userMeetData[$key]["mlendtime"]=date("Y-m-d H:i", $value["mendtime"]);
                }
                return JSON(["code"=>"20013","msg"=>"Get the user conference list successfully.","result"=>$userMeetData]);
            } else {
                return JSON(["code"=>"10024","msg"=>"Gets the user conference list failed."]);
            }
        }
    }


    /*

    删除会议

     */

    public function meetDelete()
    {
        $mid=input("mid");

        $res=Db::table("user_sign")->where("mid", $mid)->delete();

        if ($res) {
            $res1=Db::table("meet_create")->where("mid", $mid)->delete();

            if ($res1) {
                return JSON(["code"=>"20007","msg"=>"Delete the success"]);
            } else {
                return JSON(['code'=>"10019","msg"=>"Delete failed"]);
            }
        } else {
            return JSON(['code'=>"10019","msg"=>"Delete failed"]);
        }
    }

    /*

    修改会议信息

     */

    public function meetUpdate()
    {
        $mid=input("mid");

        $res=Db::table("user_sign")->where("mid", $mid)->delete();

        if (!$res) {
            return JSON(["code"=>"10021","msg"=>"Invalid meeting ID"]);

            return ;
        }

        $meetInfo=[

            "mtitlte"=>input("mtitle"),

            "mcontent" => input("content"),

            "uid" => input("uid"),

            "mstartime" => strtotime(input("mstartime")),

            "mendtime" => strtotime(input("mendtime")),

            "wlanmac" =>input("wlanmac"),

            "bluetoothmac" => input("bluetoothmac")

        ];

        $uidSet=implode(",", input("uidset"));

        $meetTable =new Meets;

        $meetTable->data($meetInfo);

        /* $validate=Loader::validate("Meet");

         if($validate->check($meetTable)){*/

        $meetMember=count($uidset);

        if ($meetMember) {
            for ($i=0;$i<$meetMember;$i++) {
                $res=DB::table("user_sign")->insert(["uid"=>$uidset[$i],"mid"=>$mid]);

                if ($res) {
                    continue;
                } else {
                    return JSON(["code"=>"10014","msg"=>"Select the meeting member to fail."]);

                    break;
                }
            }
        } else {
            return JSON(["code"=>"10015","msg"=>"Please select the members of the meeting."]);
        }

    }

    /*

    会议内容

     */

    public function meetContent()
    {
        $mid=input("mid");

        $res=DB::table("meet_create")

        ->alias("mc")

        ->join("user u", "u.uid=mc.uid")

        ->where("mid", $mid)

        ->field("mtitle,mcontent,mimage,mstartime,mendtime,wlanmac,bluetoothmac,uname,maddress,uphoto")

        ->find();

        $res["mstartime"]=date("Y-m-d H:i", $res["mstartime"]);
        $res["mendtime"]=date("Y-m-d H:i", $res["mendtime"]);

        if(input("equipment")==="wexin"){
          $this->assign("meetContent",$res);
          return $this->fetch("meet-content");
        }
        if ($res) {
            return JSON(["code"=>"20011","msg"=>"The meeting data was successful.","result"=>$res]);
        } else {
            return JSON(["code"=>"10022","msg"=>"The meeting data failed."]);
        }
    }

    /*

    会议参加列表

     */

    public function meetSignList()
    {
        $uid=input("uid");
        $data=Db::table("user")->where("uid", $uid)->field("user_type")->find();
        if ($data["user_type"]===0) {
            $meetData=DB::table("meet_create")
            ->alias("mc")
            ->join("user u", "u.uid=mc.uid")
            ->where("mc.uid", $uid)
            ->field("mid,mtitle,maddress,uphoto,mstartime,mendtime,wlanmac,bluetoothmac,uname")
            ->where("mendtime", ">=", time())
            ->where("mstartime", "<=", time()+60*60)
            ->order("mstartime asc")
            ->select();
            foreach ($meetData as $key => $value) {
                $mtonumber=Db::table("user_sign")->where("mid", $value["mid"])->count();
                $marnumber=Db::table("user_sign")->where("mid", $value["mid"])->where("issign", 1)->count();
                $meetData[$key]["mlstartime"]=date("Y-m-d H:i", $value["mstartime"]);
                $meetData[$key]["mlendtime"]=date("Y-m-d H:i", $value["mendtime"]);
                $value["mtonumber"]=$mtonumber;
                $value["marnumber"]=$marnumber;
            }
            if ($meetData) {
                return JSON(["code"=>"20006","msg"=>"Get the list of meetings to be successful.","result"=>$meetData]);
            } else {
                return JSON(["code"=>"10019","The database is not available."]);
            }
        } else {
            $userMeetData=Db::table("user_sign")
                ->alias("us")
                ->join("meet_create mc", "mc.mid=us.mid")
                ->join("user u","u.uid=mc.uid")
                ->where("us.uid", $uid)
                ->where("mendtime", ">=", time())
                ->where("mstartime", "<=", time()+60*60)
                ->order("mstartime asc")
                ->field("mc.mid,mtitle,mstartime,mendtime,maddress,uphoto,wlanmac,bluetoothmac,uname,issign,sign_id")
                ->select();

            if ($userMeetData!==false) {
                foreach ($userMeetData as $key => $value) {
                    $userMeetData[$key]["mlstartime"]=date("Y-m-d H:i", $value["mstartime"]);
                    $userMeetData[$key]["mlendtime"]=date("Y-m-d H:i", $value["mendtime"]);
                }
                return JSON(["code"=>"20006","msg"=>"Get the user conference list successfully.","result"=>$userMeetData]);
            } else {
                return JSON(["code"=>"10024","msg"=>"Gets the user conference list failed."]);
            }
        }
    }
    /*
    会议签到详细
     */
    public function meetSignDetail()
    {
        $mid=input("mid");

        $isok=Db::table("user_sign")

        ->alias("us")

        ->join("user u", "u.uid=us.uid")

        ->where("us.mid", $mid)

        ->where("us.issign", 1)

        ->field("u.uid,us.isfingerprint,us.rssi,us.utime,u.uname,u.uphoto")

        ->select();
        foreach ($isok as $key => $value) {
            $isok[$key]["utime"]=date("Y-m-d H:i", $value["utime"]);
            $isok[$key]["utime"]=date("Y-m-d H:i", $value["utime"]);
        }

        $nook=Db::table("user_sign")

         ->alias("us")

        ->join("user u", "u.uid=us.uid")

        ->where("mid", $mid)

        ->where("issign", 0)

        ->field("u.uid,uname,uphoto,uname,uphone")

        ->select();

        if ($isok||$nook) {
            return JSON(["code"=>"20012","msg"=>"Get the check-in data to be successful.","isok"=>$isok,"notok"=>$nook]);
        } else {
            return JSON(["code"=>"10023","msg"=>"There is a problem with meeting ID"]);
        }
    }

    public function uMeetList()
    {
           $uid=input("uid");
        $pageNo=input("pageNo");
        $data=Db::table("user")->where("uid", $uid)->field("user_type")->find();
        if ($data["user_type"]===0) {
            if ($uid) {
                $meetData=DB::table("meet_create")

            ->where("uid", $uid)

            ->field("mid,mtitle,mimage,maddress,mstartime,mendtime")

            ->where("mendtime", ">=", time())
            ->limit($pageNo*10, 10)
            ->order("mstartime desc")

            ->select();



                if ($meetData) {
                    foreach ($meetData as $key => $value) {
                        $meetData[$key]["mlstartime"]=date("Y-m-d H:i", $value["mstartime"]);
                        $meetData[$key]["mlendtime"]=date("Y-m-d H:i", $value["mendtime"]);
                    }

                    return JSON(["code"=>"20006","msg"=>"Get the list of meetings to be successful.","result"=>$meetData]);
                } else {
                    return JSON(["code"=>"10019","msg"=>"The database is not available."]);
                }
            } else {
                return JSON(["code"=>"10018","msg"=>"Get the list of meeting data failed."]);
            }
        } else {
            $userMeetData=Db::table("user_sign")

                ->alias("us")

                ->join("meet_create mc", "mc.mid=us.mid")

                ->join("user u", "u.uid=mc.uid")

                ->where("us.uid", $uid)
            ->where("mendtime", ">=", time())
            ->limit($pageNo*10, 10)
            ->order("mstartime desc")
            ->field("mc.mid,mtitle,mstartime,mendtime,mimage,maddress,wlanmac,bluetoothmac,uname,uphoto")

            ->select();
            //echo Db::table("user")->getLastSql();
            if ($userMeetData) {
                foreach ($userMeetData as $key => $value) {
                    $userMeetData[$key]["mlstartime"]=date("Y-m-d H:i", $value["mstartime"]);
                    $userMeetData[$key]["mlendtime"]=date("Y-m-d H:i", $value["mendtime"]);
                }


                return JSON(["code"=>"20013","msg"=>"Get the user conference list successfully.","result"=>$userMeetData]);
            } else {
                return JSON(["code"=>"10024","msg"=>"Gets the user conference list failed."]);
            }
        }
    }
    public function uMeetSign()
    {
        $isfingerprint;
        $pid=input("pid");
        $uid=input("uid");
        $mid=input("mid");
        $data= Db::table("user")->where("uid",$uid)->field("pid,uname")->find();
        if($pid&&$data["pid"]!==$pid){

            return JSON(["code"=>"10032","msg"=>"pid error"]);
        }
        if ($pid) {
            $isfingerprint=1;
        } else {
            $isfingerprint=0;
        }
        $signData=[
            "utime"=>time(),
            "isfingerprint"=>$isfingerprint,
            "rssi"=>input("rssi"),
            "issign"=>1

        ];
        if($mid){
          $res=Db::table("user_sign")->where("uid",$uid)->where("mid",$mid)->update($signData);
        }else{
           $res=Db::table("user_sign")->where("sign_id",input("sign_id"))->update($signData);
        }
        if ($res!==false) {
            return JSON(["code"=>"20031","msg"=>"sign success","uname"=>$data["uname"]]);
        } else {
            return JSON(['code'=>"10031","msg"=>"sign fail "]);
        }
    }

    /*

     历史会议

     */

    public function historyMeet()
    {
      $uid=input("uid");
      $userType=Db::table("user")->where("uid",$uid)->find();
      if($userType["user_type"]===1){
        $userMeetData=Db::table("user_sign")
        ->alias("us")
        ->join("meet_create mc", "mc.mid=us.mid")
        ->join("user u", "u.uid=mc.uid")
        ->where("us.uid",$uid)
        ->where("mendtime", "<", time())
        ->order("mstartime asc")
        ->field("mc.mid,mtitle,mimage,mstartime,mendtime,uphoto,maddress,wlanmac,bluetoothmac,uname")
        ->select();

      }else{
        $userMeetData=Db::table("meet_create")->alias("mc")->join("user u","u.uid=mc.uid")->where("u.uid", $uid)
        ->where("mendtime", "<", time())
        ->order("mstartime asc")
        ->field("mc.mid,mtitle,mstartime,mimage,mendtime,maddress,wlanmac,bluetoothmac,uname")
        ->select();
      }
      foreach (  $userMeetData as $key => $value) {
            $userMeetData[$key]["mlstartime"]=date("Y-m-d H:i", $value["mstartime"]);
            $userMeetData[$key]["mlendtime"]=date("Y-m-d H:i", $value["mendtime"]);
      }
      if ($userMeetData!==false){
          return JSON(["code"=>"20013","msg"=>"Get the user conference list successfully.","result"=>$userMeetData]);
      } else {
          return JSON(["code"=>"10024","msg"=>"Gets the user conference list failed."]);
      }
    }
}
