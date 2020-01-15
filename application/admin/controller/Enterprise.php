<?php
namespace app\admin\controller;
use \think\Db;
use \think\Validate;
use \think\Loader;
use \think\Request;
use \app\admin\controller\Base;
use app\admin\model\Enterprise as Enterprises;
class Enterprise extends Base{
    ///生成授权码

    private static function generateCode(){
       $str="1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeLength=strlen($str);
        $code='';
        for($i=0;$i<32;$i++){
            $index=mt_rand(0,$codeLength-1);
            $code.=$str[$index];
        }
        $res=Db::table("business")->where("pcode",$code)->select();
        if(!$res){
            //Db::table("business")->where("bid",bid)->insert(["pcode"=>$code]);
            return $code;
        }else{
            generateCode();
        }
    }
    public function getCode(){
        if(!input("post.bid")){
            $code=Enterprise::generateCode();

            return JSON(['code'=>$code]);
        }else{
            $code=Enterprise::generateCode();
            Db::table("business")->where("bid",input("post.bid"))->update(["pcode"=>$code]);
            return JSON(['code'=>$code]);
        }
    }
    public function enterpriseList(){
        $data=Db::table("business")->select();
        $count=count($data);
        $this->assign("data",["enterpriseData"=>$data,"count"=>$count]);
        return $this->fetch("Enterprise-list");
    }
    public function enterpriseEdit(){
            $enterprise= new Enterprises;
            $businessData=[
                'bname'=>input('post.bname'),
                'pcode'=>input('post.pcode')
            ];
            $enterprise->data($businessData);
            $validate=Loader::validate("Enterprise");

            if($validate->scene("EnterpriseEdit")->check($enterprise)){

                $res=Db::table("business")->where("bid",input("post.bid"))->update($businessData);
                if($res){
                    return JSON(["msg"=>"update success"]);
                }else{
                    return JSON(["msg"=>"update fail"]);
                }
            }else{
                return JSON(["msg"=>$validate->getError()]);
            }
          }




    public function editIndex($bid){
            $res=Db::table("business")->where("bid",$bid)->field("bid,bname,pcode")->find();
            $this->assign("enterData",$res);
            return $this->fetch("Enterprise-Edit");
    }
    public function enterpriseAdd(){
        if(input("post.")){

            $enterprise=new Enterprises;
            $businessData=[
                'bname'=>input('post.bname'),
                'pcode'=>input('post.pcode')
            ];

            $enterprise->data($businessData);
            $validate=Loader::validate("Enterprise");

            if($validate->scene("EnterpriseAdd")->check($enterprise)){

               $res=Db::table("business")->insert($businessData);
               if($res){
                    return JSON(['msg'=>"add success"]);
                }else{
                    return JSON(["msg"=>"add fail"]);
                }
            }else{

                return  JSON(['msg'=>$validate->getError()]);
            }
        }else{
            return $this->fetch("Enterprise-add");
        }
    }
    public function enterpriseDelete(){

         if (Request::instance()->isPost()){
          $put= file_get_contents('php://input');

          $put=json_decode($put,true);

      if(count($put)>1){
        //多个文章删除
        $arr=implode(",",$put);
        $userUid=Db::table("user")->where('bid','in',$arr)->field("uid")->select();

        $res=Db::table("business")->where('bid','in',$arr)->delete();


      }else if(count($put)===1){
        //单个文章删除
         $data=$put[0];
         $userUid=Db::table("user")->where('bid',intval($data))->field("uid")->select();

        $res=Db::table("business")->where('bid',intval($data))->delete();

      }else {

            return JSON(["msg"=>"delete fail"]);
      }

          foreach ($userUid as $key => $value) {
            $userMid=Db::table("meet_create")->where('uid',$value["uid"])->field("mid")->select();
            foreach ($userMid as $key1 => $value1) {
              Db::table("user_sign")->where('mid',$userMid[$key1]['mid'])->delete();
            }
            Db::table('meet_create')->where('uid',$value['uid'])->delete();
          }
          if($res){
              return JSON(["msg"=>"delete success"]);
          }else{
              return JSON(["msg"=>"delete fail"]);
          }
      }
    }
    public function serachEnterprise(){
        $key=input("post.key");

       $res=Db::table("business")->where("bname",'like','%'.$key.'%')->select();
       $count=count($res);
       if($res){
            $this->assign("data",["enterpriseData"=>$res,"count"=>$count]);
            return $this->fetch("Enterprise-list");
       }

    }

}
