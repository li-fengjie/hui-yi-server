<?php
namespace app\admin\controller;
use \think\Db;
use \app\admin\controller\Base;
class User extends Base{
    public function userList(){
            $userData=Db::table("user")
            ->alias("u")
            ->join("business b","b.bid=u.bid")
            ->field("uid,uname,uphoto,user_type,bname,uphone,u.isuse")
            ->select();
            $this->assign("vo",["uid"=>0]);
            $count=count($userData);
            $this->assign("data",['userData'=>$userData,'count'=>$count]);
            return $this->fetch("user-list");
    }
    function userIsUse(){
        $id=input("post.uid");
        $isUse=input("post.isUse");

        $res=Db::table("user")->where("uid",$id)->update(["isuse"=>$isUse]);
        if($res){

            if($isUse==='1'){
                    echo json_encode('success  use');
            }
            else{

                echo json_encode('sucess is not  use');
             }
         }else{
                    echo json_encode('fail');
         }
    }
    function searchUser(){
      $infor=input("post.infor");
      $userData=Db::table("user")
      ->alias("u")
      ->join("business b","b.bid=u.bid")
      ->field("uid,uname,uphoto,user_type,bname,uphone,u.isuse")
      ->whereOr("uphone",$infor)
      ->whereOr("uname",$infor)
      ->whereOr("uid",$infor)
      ->select();
      $this->assign("vo",["uid"=>0]);
      $count=count($userData);
      $this->assign("data",['userData'=>$userData,'count'=>$count]);
      return $this->fetch("user-list");
    }
 }
