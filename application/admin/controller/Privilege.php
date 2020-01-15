<?php
namespace app\admin\controller;
use \think\Controller;
use \think\Db;
use \think\Request;
use \app\admin\controller\Base;
class Privilege extends Base{
    function permissionList(){
      $privilegeData = Db::table("privilege")->select();
      $count=count($privilegeData);
      $this->assign("data",['privilegeData'=>$privilegeData,'count'=>$count]);
      return $this->fetch("admin-permission");
    }
    function permissionType(){
        return $this->fetch("permission-type");
    }
    function permissionTypeList(){
      $data=Db::table("privilege")->field("pid,pname,parentid")->select();
      if($data){
        return JSON($data);
      }else{
        return json(['data'=>'load data fail']);
      }
    }
    function permissionTypeAdd(){
      if(!input("post."))
          return $this->fetch("permission-type-add");
      else{
          $privilegeData=[
            "pname"=>input("post.pname"),
            "mname"=>input("post.mname"),
            "cname"=>input("post.cname"),
            "aname"=>input("post.aname"),
            "parentid"=>input("post.parentid")
          ];
          $res=Db::table("privilege")->insert($privilegeData);
          if($res){
              echo json_encode("add success");
          }else{
              echo json_encode("add fail");
          }
      }
    }
    function permissionDelete(){
      if (Request::instance()->isPost()) {
          $put= file_get_contents('php://input');

          $put=json_decode($put, true);

          if (count($put)>1) {

              $arr=implode(",", $put);

              $res=Db::table("privilege")->where('pid', 'in', $arr)->delete();
              if ($res) {
                  return JSON(["msg"=>"delete success"]);
              } else {
                  return JSON(["msg"=>"delete fail"]);
              }
          } elseif (count($put)===1) {

              $data=$put[0];
              $res=Db::table("privilege")->where('pid', intval($data))->delete();
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
    function permissionEdit($pid=null){
        if(!input("post.pname")){
          $privilegeData=Db::table("privilege")
          ->where("pid",$pid)->find();
          $parentName=Db::table("privilege")->where("pid",$privilegeData["parentid"])->field("pname")->find();
          if($parentName===null)
              $privilegeData["parentname"]="顶级权限";
          else {
            $privilegeData["parentname"]=$parentName["pname"];
          }
          $this->assign("privilegeData",$privilegeData);
          return $this->fetch("permission-type-edit");
        }else{
          $privilegeData=[
            "pname"=>input("post.pname"),
            "mname"=>input("post.mname"),
            "cname"=>input("post.cname"),
            "aname"=>input("post.aname"),
            "parentid"=>input("post.parentid")
          ];
          $res=Db::table("privilege")->where("pid",input("pid"))->update($privilegeData);
          if($res){
              echo json_encode("edit success");
          }else{
              echo json_encode("edit fail");
          }
        }
    }
    function serachPermission(){
      $privilegeData=Db::table("privilege")->where("pname","like","%".input("post.pname")."%")
      ->select();
      $count=count($privileData);
      $this->assign("data",['privilegeData'=>$privilegeData,'count'=>$count]);
      return $this->fetch("admin-permission");
    }
}
