<?php
namespace app\admin\controller;

use \think\Db;
use \think\Request;
use \app\admin\controller\Base;
class Meet extends Base
{
  /**
   * [meetList description]
   * @param
   * @return [type] [description]
   */
    public function meetList()
    {
        $data=Db::table("meet_create")
        ->alias("mc")
        ->join("user u", "u.uid=mc.uid")
        ->field("u.uid,mid,mtitle,maddress,mstartime,mendtime,uname,uphoto,wlanmac,bluetoothmac")
        ->select();
        $count=count($data);
        $meetData=array();
        $this->assign("data", ["meetData"=>$data,"count"=>$count,"currenttime"=>time()]);
        return $this->fetch("meet-list");
    }
    public function searchMeet()
    {
        $where=array();
        $mtitle=input("post.mtitle");
        $mstartime=strtotime(input("post.mstartime"));
        $mendtime=strtotime(input("post.mendtime"));
        //  var_dump($mendtime);
        if ($mstartime&&$mendtime) {
            $where["mstarttime"]=array("between",[$mstartime,$mendtime]);
        } elseif ($mstartime){
            $where["mstartime"]=array("egt",$mstartime);
        } elseif ($mendtime) {
            $where["mstartime"]=array("elt",$mendtime);
        }
        //  var_dump($mtitle);
        if ($mtitle) {
            $where["mtitle"]=array("like",'%'.$mtitle.'%');
        }
        $meetData=Db::table("meet_create")
            ->alias("mc")
            ->join("user u", "u.uid=mc.uid")
            ->field("u.uid,mid,mtitle,maddress,mstartime,mendtime,uname,uphoto,wlanmac,bluetoothmac")
            ->where($where)
            ->select();
        $count=count($meetData);
        $this->assign("data", ["meetData"=>$meetData,"count"=>$count,'currenttime'=>time()]);
        return $this->fetch("meet-list");
    }
    public function meetDelete()
    {

        if (Request::instance()->isPost()) {
            $put= file_get_contents('php://input');

            $put=json_decode($put, true);

            if (count($put)>1) {
                //多个文章删除
                $arr=implode(",", $put);
                Db::table("user_sign")->where('mid', 'in', $arr)->delete();
                $res=Db::table("meet_create")->where('mid', 'in', $arr)->delete();
                if ($res) {
                    return JSON(["msg"=>"delete success"]);
                } else {
                    return JSON(["msg"=>"delete fail"]);
                }
            } elseif (count($put)===1) {
                //单个文章删除
                $data=$put[0];
                Db::table("user_sign")->where('mid', intval($data))->delete();
                $res=Db::table("meet_create")->where('mid', intval($data))->delete();
                if ($res) {
                    return JSON(["msg"=>"delete success"]);
                } else {
                    return JSON(["msg"=>"delete fail"]);
                }
            } else {
                return JSON(["msg"=>"delete fail"]);
            }
        }
    }
}
