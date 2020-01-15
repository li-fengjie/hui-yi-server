<?php
namespace app\admin\controller;
use \think\Db;
use \think\Request;
use \app\admin\controller\Base;
class Feedback extends Base{
    public function feedbackList(){
        $feedbackData=Db::table("feedback")->alias("fb")->join("user u","u.uid=fb.uid")->field("id,fb.uid,uname,feedback_content")->select();

        $dataCount=count($feedbackData);
        $this->assign("Data",$feedbackData);
        $this->assign("count",$dataCount);
        return $this->fetch("feedback-list");
    }
    function feedbackDelete(){
      if (Request::instance()->isPost()) {
          $put= file_get_contents('php://input');

          $put=json_decode($put, true);

          if (count($put)>1) {
              //多个文章删除
              $arr=implode(",", $put);

              $res=Db::table("feedback")->where('id', 'in', $arr)->delete();
              if ($res) {
                  return JSON(["msg"=>"delete success"]);
              } else {
                  return JSON(["msg"=>"delete fail"]);
              }
          } elseif (count($put)===1) {
              //单个文章删除
              $data=$put[0];
              $res=Db::table("feedback")->where('id', intval($data))->delete();
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
