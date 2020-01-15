<?php
namespace app\admin\Controller;
use think\Controller;
use \think\Db;
use think\Session;
class Base extends Controller{

  public function __construct(){
      //调用父类方法好继承
        parent::__construct();
        //获取当前的管理员
        $admin=Session::get('aid');
        if(!$admin)
            $this->redirect('admin/index/login');
        $module = request()->module();
        $controller = request()->controller();
        $action = request()->action();
        $url = $module.DS.$controller.DS.$action;
        $where =['cname' =>$controller,'mname'=>$module,'aname'=>$module];
        if ($controller = 'Base')
            return TRUE;
        $result = null;
        if ($admin ===1){
           $result = DB::table('privilege')->where($where)->count();
        }else{
           $result = DB::table('privilege_role')
           ->alias('pr')
           ->join('privilege p ','p.pid=pr.pid')
           ->join('admin_role ar','ar.rid=pr.rid')->where('aid',$admin)->find('pr.rid')->count();
        }
        if($result < 1)
          $this->error('无权访问!');
      }
      function index() {
        $admin = Session::get('aid');
          if($admin ==1)
            $pri = DB::table('privilege')->select();
          else
            $pri=DB::table('privilege_role')
            ->alias('pr')
            ->join('privilege p ','p.pid=pr.pid')
            ->join('admin_role ar','ar.rid=pr.rid')->where('aid',$admin) ->field('p.*')->select();
          $btn = [];
          foreach ($pri as $k => $v)
              {

                if($v['parentid'] == 0)
                {

                  foreach ($pri as $k1 => $v1)
                  {
                    if($v1['parentid'] == $v['pid'])
                    {
                      $v1["relurl"]=$v1["mname"].'/'.$v1['cname'].'/'.$v1['aname'];
                      $v['children'][] = $v1;
                    }
                  }
                  $btn[] = $v;
                }
              }
        $adminInfo=Db::table("admin")->alias("a")->join("admin_role ar","ar.aid=a.aid")->join("role r","r.rid=ar.rid")->where("a.aid",Session::get('aid'))->find();
        $this->assign("adminInfo",$adminInfo);
        $style=[1=>"&#xe616;",2=>"&#xe620;",3=>"&#xe622;",4=>"&#xe60d;",5=>"&#xe62d;"];
        $this->assign("style",$style);
        $this->assign('btn', $btn);
        return $this->fetch("index");
      }
      function welcome() {
        $adminInfo=Db::table("admin")->where("aid",Session::get('aid'))->find();
        $this->assign("adminInfo",$adminInfo);
        return $this->fetch("welcome");
      }
      function sendIndex(){
        return $this->fetch("base/send-email");
      }
      function sendEmail(){
        $result=\app\common\Helper\PHPMailerHelper::sendMail(input("post.email"),input("post.subject"),input("post.content"));
        return JSON(["msg"=>$result]);
      }
      function emailIsRead(){

      }

}
