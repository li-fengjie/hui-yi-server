<?php
namespace app\admin\controller;
use \app\common\Helper\encryptionToDecryptHelper;
use \app\admin\controller\Base;
use think\Db;
use \think\Validate;
use \think\Loader;
use \think\Request;
use app\admin\model\Admin as Admins;
class Admin extends Base{
  public static function adminIdGenerate()
  {
      $aid='';
      $start=1000;
      $aid.=mt_rand($start, $start*10-1);
      $res=Db::table("admin")->where("aid",$aid)->find();
      if (!$res) {
          return $aid;
      } else {
          adminIdGenerate();
      }
  }
    function adminList(){
      $adminData = Db::table("admin")
      ->alias("a")
      ->join("admin_role ar","ar.aid=a.aid")
      ->join("role r","r.rid=ar.rid")
      ->field("a.*,r.rname")
      ->select();
      $count=count($adminData);
      $this->assign("vo",["aid"=>0]);
      $this->assign("data",["count"=>$count,"adminData"=>$adminData]);
      return $this->fetch('admin-list');
    }
    function addIndex(){
      $result= Db::table("role")->select();
      $this->assign("roleData",$result);
    return $this->fetch('admin-add');
    }
    function adminAdd(){
        $aid =Admin::adminIdGenerate();
        $adminInfo=[
          'aid'=>$aid,
          'aname'=>input('post.adminName'),
          'apassword'=>encryptionToDecryptHelper::encrypt(input("post.password"),"E"),
          'asex' => input('post.sex'),
          'aphone' => input('post.phone'),
          'emailCode'=>encryptionToDecryptHelper::encrypt(input("post.Code"),"E"),
          'aemail' =>input('post.aemail'),
          'note' => input('post.note')
        ];
        $admin = new Admins;
        $admin->data($adminInfo);
        $validate=Loader::validate("Admin");
        if($validate->check($adminInfo)){
          $result=Db::table("admin")->insert($adminInfo);
          Db::table("admin_role")->insert(["rid"=>input("post.rid"),"aid"=>$aid]);
          if($result){
            return JSON(["msg"=>"add success"]);
          }else{
            return JSON(["msg"=>"add fail"]);
          }
        }else{
          return JSON(["msg"=>$validate->getError()]);
      }

  }
    function adminPasswordEdit(){
      return $this->fetch('admin-password-edit');
    }
    function editIndex($aid){
      $roleData= Db::table("role")->select();
      $adminData=Db::table("admin")
      ->alias("a")
      ->join("admin_role ar","ar.aid=a.aid")
      ->join("role r","ar.rid=r.rid")
      ->where("a.aid",$aid)->find();
      $adminData["emailCode"]=encryptionToDecryptHelper::encrypt($adminData["emailCode"],"D");
      $adminData["apassword"]=encryptionToDecryptHelper::encrypt($adminData["apassword"],"D");
      $this->assign("roleData",$roleData);
      $this->assign("adminData",$adminData);

      return $this->fetch('admin-edit');
    }
    function adminEdit(){
          $adminInfo=[
            'aname'=>input('post.adminName'),
            'apassword'=>encryptionToDecryptHelper::encrypt(input("post.password"),"E"),
            'asex' => input('post.sex'),
            'aphone' => input('post.phone'),
            'aemail' =>input('post.aemail'),
            'emailCode'=>encryptionToDecryptHelper::encrypt(input("post.Code"),"E"),
            'note' => input('post.note')
          ];
          $admin = new Admins;
          $admin->data($adminInfo);
          $validate=Loader::validate("Admin");
          if($validate->check($admin)){
            $result = Db::table("admin")->where('aid',input("post.aid"))->update($adminInfo);
            Db::table("admin_role")->update(["rid"=>input("post.rid"),"aid"=>input("post.aid")]);
            if($result){
              return JSON(["msg"=>"edit success"]);
            }else{
              return JSON(["msg"=>"edit fail"]);
            }
            }else{
              return JSON(["msg"=>$validate->getError()]);
            }
      }
      function adminIsUse(){
        $id=input("post.aid");
        $isUse=input("post.isUse");
        $res=Db::table("admin")->where("aid",$id)->update(["is_use"=>$isUse]);
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
      function adminDelete(){
            $put= file_get_contents('php://input');
            $put=json_decode($put, true);
            if (count($put)>1) {
                $arr=implode(",",$put);
                $res=Db::table("admin")->where('aid', 'in', $arr)->delete();
                Db::table("admin_role")->where("aid","in",$arr)->delete();
                if ($res) {
                    return JSON(["msg"=>"delete success"]);
                } else {
                    return JSON(["msg"=>"delete fail"]);
                }
            } elseif (count($put)===1) {
                $data=$put[0];
                $res=Db::table("admin")->where('aid', intval($data))->delete();
                Db::table("admin_role")->where("aid",intval($data))->delete();
                if ($res) {
                    return JSON(["msg"=>"delete success"]);
                } else {
                    return JSON(["msg"=>"delete fail"]);
                }
            } else {
                return JSON(["msg"=>"delete fail"]);
            }
        }
        function serachAdmin(){
          $where=array();
          $pname=input("post.pname");
          $startime=strtotime(input("post.stime"));
          $endtime=strtotime(input("post.etime"));

          if ($startime&&$endtime) {
              $where["time"]=array("between",[$startime,$endtime]);
          } elseif ($startime){
              $where["time"]=array("egt",$mstartime);
          } elseif ($endtime) {
              $where["time"]=array("elt",$endtime);
          }
          //  var_dump($mtitle);
          if ($pname) {
              $where["pname"]=array("like",'%'.$pname.'%');
          }
          $adminData=Db::table("admin")->where($where)->select();
          $count=count($adminData);
          $this->assign("vo",["aid"=>0]);
          $this->assign("data",["count"=>$count,"adminData"=>$adminData]);
          return $this->fetch('admin-list');
        }

}
