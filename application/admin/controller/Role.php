<?php
namespace app\admin\controller;
use \think\Controller;
use \think\Db;
use \think\Request;
use \app\admin\controller\Base;
class Role extends Base{
  public static  function roleIdGenerate()
  {
      $rid='';

      $start=1000;

      $rid.=mt_rand($start, $start*10-1);

      $res=Db::table("role")->where("rid", $rid)->find();

      if (!$res) {
          return $rid;
      } else {
          roleIdGenerate();
      }
  }
    function roleList(){

      $roleData=Db::table("role")->select();
      $adminRoleData=Db::table("admin_role")->select();
      $adminData=Db::table("admin")->field("aid,aname")->select();
      foreach($adminData as $key =>$value){
          foreach($adminRoleData as $key1 =>$value1){
            if($value1["aid"]===$value["aid"]){
              $adminRoleData[$key1]["aname"]=$value["aname"];
            }
          }
      }
      $rid=[];
      foreach($adminRoleData as $key =>$value){

        if(!isset($value["aname"]))
          $value["aname"]="";
        if(!isset($rid[$value["rid"]]))
          $rid[$value["rid"]]="";
        $rid[$value["rid"]].=$value["aname"].",";
      }
      foreach($rid as $key => $value ){
        $rid[$key]=substr($value,0,strLen($value)-1);
      }


      foreach($roleData as $key =>$value){
        $roleData[$key]["aname"]=isset($rid[$value["rid"]])?$rid[$value["rid"]]:'';
      }

      $this->assign("roleData",$roleData);
      return $this->fetch('admin-role');
    }
    function addIndex(){
      $privilegeData=Db::table("privilege")->select();
      $this->assign("privilegeData",$privilegeData);

      return $this->fetch('admin-role-add');
    }
    function roleAdd(){

        $roleData=input("post.");
        $rid=Role::roleIdGenerate();
        $res1=null;
        foreach($roleData as $key => $value){
          if(substr($key,0,3)==="pid"){
            $res1=Db::table("privilege_role")->insert(["rid"=>$rid,"pid"=>$value]);
            if($res1)continue;

          }
        }
        if($res1){
          $res=Db::table("role")->insert(["rid"=>$rid,"rname"=>$roleData["rname"],"note"=>$roleData["note"]]);
          if(!$res){
            return JSON(["msg"=>"add fail"]);
          }
          return JSON(["msg"=>"add success"]);
        }else{
          return JSON(["msg"=>"add fail"]);
        }
      
    }
     function roleEdit($rid=null){
       if(!input("post.isEdit")){

            $privilegeRole=Db::table("privilege_role")->where("rid",$rid)->select();
            $privilegeData=Db::table("privilege")->select();
            $isHasPri=[];
            foreach ($privilegeRole as $key => $value) {
              $isHasPri[$value["pid"]]=true;
            }
            $roleData=Db::table("role")->where("rid",$rid)->find();
            $this->assign("privilegeData",$privilegeData);
            $this->assign("roleData",$roleData);
            $this->assign("isHasPri",$isHasPri);
            return $this->fetch("admin-role-edit");
         }else{
              $roleData=input("post.");
              $rid=$roleData["rid"];
              $res1=null;
              $res=Db::table("privilege_role")->where("rid",$rid)->delete();
              if(!$res){
                return JSON(["msg"=>"edit fail"]);
              }
              foreach($roleData as $key => $value){
                if(substr($key,0,3)==="pid"){
                  $res1=Db::table("privilege_role")->insert(["rid"=>$rid,"pid"=>$value]);
                  if($res1)continue;
                }
              }
              if($res1){
                $res=Db::table("role")->where("rid",$rid)->update(["rname"=>$roleData["rname"],"note"=>$roleData["note"]]);
                if(!$res){
                  return JSON(["msg"=>"edit fail"]);
                }
                return JSON(["msg"=>"edit success"]);
              }else{
                return JSON(["msg"=>"edit fail"]);
              }
         }
     }
     function roleDelete(){
       if (Request::instance()->isPost()) {
           $put= file_get_contents('php://input');

           $put=json_decode($put, true);

           if (count($put)>1) {

               $arr=implode(",", $put);

               $res=Db::table("role")->where('rid','in', $arr)->delete();
               Db::table("privilege_role")->where("rid","in",$arr)->delete();
               if ($res) {
                   return JSON(["msg"=>"delete success"]);
               } else {
                   return JSON(["msg"=>"delete fail"]);
               }
           } elseif (count($put)===1) {

               $data=$put[0];
               $res=Db::table("role")->where('rid', intval($data))->delete();
               Db::table("privilege_role")->where("rid",intval($data))->delete();
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
