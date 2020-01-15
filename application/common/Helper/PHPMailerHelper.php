<?php
namespace app\common\helper;
use \app\common\PHPMailer\PHPMailer;
use \app\common\PHPMailer\SMTP;
use \app\common\Helper\encryptionToDecryptHelper;
use \think\Db;
use \think\Session;
class PHPMailerHelper{
  public static function sendMail($email,$subject,$content){

          if(!filter_var($email,FILTER_VALIDATE_EMAIL))
          {
            return "邮件地址不合法";
          }
          $res=Db::table("admin")->where("aemail",$email)->field("aname,emailnum")->find();
          if(!$res){
            return "这不是该系统的管理员";
          }
          Db::table("admin")->where("aemail",$email)->update(["emailnum"=>$res["emailnum"]]);
         $aemail=DB::table("admin")->where("aid",Session::get("aid"))->field("aemail,aname,emailCode")->find();

         $index=stripos($aemail['aemail'],"@");
         $emailtail=substr($aemail['aemail'],$index+1,strlen($aemail['aemail']));
         if($emailtail==='163.com'){
           $Host="smtp.163.com";
         }elseif($emailtail==='qq.com'){
           $Host="smtp.qq.com";
         }

         $mail = new PHPMailer(true);
         $mail->IsSMTP();
         $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
         $mail->SMTPAuth = true; //开启认证
         $mail->Port = 25; //端口请保持默认
         $mail->Host = $Host; //使用QQ邮箱发送
         $mail->Username = $aemail["aemail"]; //这个可以替换成自己的邮箱
         $mail->Password =encryptionToDecryptHelper::encrypt($aemail["emailCode"],"D");
         //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
         $mail->AddReplyTo($aemail["aemail"],"mckee");//回复地址
         $mail->From = $aemail["aemail"];
         $mail->FromName = $aemail["aname"];
         // $mail->SMTPDebug=true;
         $mail->AddAddress($aemail["aemail"]);
         $mail->Subject =$subject;
         $mail->Body = $content;
         $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
         $mail->WordWrap = 80; // 设置每行字符串的长度
         //$mail->AddAttachment("f:/test.png"); //可以添加附件
         $mail->IsHTML(true);
         if($mail->Send())
              return "邮件已发送";
         else{
              return "邮件发送失败：".$mail->ErrorInfo;
         }




      }
}
