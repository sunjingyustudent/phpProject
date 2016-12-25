<?php
namespace Home\Controller;
use Think\Controller;
class LiveController extends BaseController {
    function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /*
     * 2016-09-05 网页直播
     * 直播详细
     * */
    public function index(){
        vendor("Pili.Pili");


        $roomid=$_GET["roomid"];//获取传过来的房间id
        $userinfo = session('userinfo');//获取用户信息

        if($roomid){
            $roominfo=M("rooms")->where("roomid=%d",$roomid)->find();//获取房间信息
            if($roominfo)
            {
                $this->assign('roominfo',$roominfo);
                //根据roomid去差用户表的用户id
                $users = M("userinfos")->field('userid,nickname,gradeid')->where(array("roomid"=>$roomid))->find();//获取房主用户id，和昵称,等级
                //查询直播大厅表
                $rh = M("livehall")->field('speaker')->where(array("roomid"=>$roomid))->find();
//                $tmp=$roominfo["style"]==""?"Live":$roominfo["style"];//获取直播室页面模板
                $this->assign('users',$users);
                $this->assign('livehall',$rh);
                $this->assign('userinfo',$userinfo);
                $this->assign('list',$roomid);
                $this->assign("roominfo",$roominfo);
//                $this->display($tmp.":index");
                $this->display("Live:index");
            }
            else
            {
                $this->display("error该房间号不存在");
            }
        }else{
            $this->display("Live:index");
        }
    }


    public function indexz(){
        vendor("Pili.Pili");
        $roomid=$_GET["roomid"];//获取传过来的房间id
        // p($roomid);
        $userinfo = session('userinfo');//获取用户信息
        if($roomid){
            $roominfo=M("rooms")->where("roomid=%d",$roomid)->find();//获取房间信息
            if($roominfo)
            {
               //礼物排行榜

                $webcastid=$_GET["webcastid"];

               /* var_dump($webcastid);
                exit();*/






                //广告展示位show

                $showarea=M('showarea')
                        ->where(array('roomid'=>$roomid))
                        ->field('saname,saurl')
                        ->select();
                          // p($showarea);die;



                $date=date('Y-m-d');//获取当前日期
                $month=date('m-d');
                $week=date('w',strtotime($date));
                $weekayyay=array();
                for($i=1;$i<=6;$i++){
                   //如果当前星期小于
                   if($i<$week){
                       $weekayyay[$i]=date("m-d",(strtotime($date) - ($week-$i)*3600*24));
                    }

                    if($i==$week){

                        $weekayyay[$i]=date('m-d');
                    }

                    if($i>$week){
                        $chai=$i-$week;
                        $weekayyay[$i]=date("m-d",(strtotime($date) + $chai*3600*24));
                    }
                }



                $isprograme=$roominfo["isprograme"];
               //判断房间是否有节目单
                if($roominfo["isprograme"]==1){
                    $where["roomid"]=$roomid;
                        $where["weekday"]=1;
                        $programlist1=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();

                    $where["roomid"]=$roomid;
                    $where["weekday"]=2;
                    $programlist2=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();

                    $where["roomid"]=$roomid;
                    $where["weekday"]=3;
                    $programlist3=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();
                    $where["roomid"]=$roomid;
                    $where["weekday"]=4;
                    $programlist4=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();
                    $where["roomid"]=$roomid;
                    $where["weekday"]=5;
                    $programlist5=M("programmenu")
                        ->where($where)
                        ->order('sort asc')
                        ->select();

                    $where["roomid"]=$roomid;
                    $where["weekday"]=6;
                    $programlist6=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();
                }

                $this->assign('isprograme',$isprograme);//把房间是否需要加载节目列表，传到前台

                $this->assign('programlist1',$programlist1);//把节目单绑定到前台,周一节目单
                $this->assign('programlist2',$programlist2);//周二节目单
                $this->assign('programlist3',$programlist3);
                $this->assign('programlist4',$programlist4);
                $this->assign('programlist5',$programlist5);
                $this->assign('programlist6',$programlist6);

                $this->assign('weekayyay',$weekayyay);


                $this->assign('roominfo',$roominfo);
                //根据roomid查询用户表的用户信息
                $users = M("userinfos")->field('userid,nickname,gradeid')->where(array("roomid"=>$roomid))->find();//获取房主用户id，和昵称,等级
                //查询直播大厅表返回直播室名称,和主讲
                $rh = M("livehall")->field('speaker,livename')->where(array("roomid"=>$roomid))->find();

//                $tmp=$roominfo["style"]==""?"Live":$roominfo["style"];//获取直播室页面模板
                //获取用户是否关注过次房间
                $count=M("userrooms")->field('urid')->where(array("userid"=>$userinfo['userid'],"roomid"=>$roomid))->count();
                //当前房间被关注的总数
                $gzcount=M("userrooms")->field('urid')->where(array("roomid"=>$roomid))->count();
                $map['roomid'] = array('neq',$roomid);
                $livehall = M('livehall')->field('liveid,livename,speaker,thumbnail,hits,liveurl,roomid')->where($map)->limit('7')->select();
                $vadio = M('videos')->Field('title,source,thumb,description,vurl,clickcount,nid')->order('clickcount desc')->limit(8)->select();
                $this->assign('videos',$vadio);
                // p($livehall);die;
                $this->assign('data',$livehall);
                $this->assign('focus',$count);
                $this->assign('focuscount',$gzcount);
                $this->assign('users',$users);
                $this->assign('livehall',$rh);
                $this->assign('userinfo1',$userinfo);
                $this->assign('userinfo',$userinfo['nickname']);
                $this->assign('list',$roomid);
                $this->assign("roominfo",$roominfo);
                $this->assign('showarea',$showarea);
                //获取直播室的页面风格
                if($roominfo["style"]=="Livestyle1"){
                    //判断当前房间的类型是机构还是个人决定跳转到那个页面
                    if($roominfo["roomtype"]==1){
                        $this->display("Live/Livestyle1:indexz");//机构
                    }else{
//                        var_dump($roominfo["roomtype"]);
                        $this->display("Live:cindex");//个人
                    }
                }else{
                    //判断当前房间的类型是机构还是个人决定跳转到那个页面
                    if($roominfo["roomtype"]==1){
                        $this->display("Live:indexz");//个人
                    }else{
                        $this->display("Live:cindex");//个人
                    }
                }
            }
            else
            {
                $this->display("error该房间号不存在");
            }
        }else{
            $this->display("Live:indexz");
        }
    }






    public function indexs(){
        vendor("Pili.Pili");


        $roomid=$_GET["roomid"];//获取传过来的房间id
        $userinfo = session('userinfo');//获取用户信息
        if($roomid){
            $roominfo=M("rooms")->where("roomid=%d",$roomid)->find();//获取房间信息
            if($roominfo)
            {

                $date=date('Y-m-d');//获取当前日期
                $month=date('m-d');
                $week=date('w',strtotime($date));

                $weekayyay=array();
                for($i=1;$i<=6;$i++){
                    //如果当前星期小于
                    if($i<$week){
                        $weekayyay[$i]=date("Y-m-d",(strtotime($date) - $i*3600*24));
                    }

                    if($i==$week){

                        $weekayyay[$i]=date('Y-m-d');
                    }

                    if($i>$week){
                        $chai=$i-$week;
                        $weekayyay[$i]=date("Y-m-d",(strtotime($date) + $chai*3600*24));
                    }


                }

                /*var_dump($weekayyay);*/

                $isprograme=$roominfo["isprograme"];
                //判断房间是否有节目单
                if($roominfo["isprograme"]==1){
                    $where["roomid"]=$roomid;
                    $where["weekday"]=1;
                    $programlist1=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();

                    $where["roomid"]=$roomid;
                    $where["weekday"]=2;
                    $programlist2=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();

                    $where["roomid"]=$roomid;
                    $where["weekday"]=3;
                    $programlist3=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();
                    $where["roomid"]=$roomid;
                    $where["weekday"]=4;
                    $programlist4=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();
                    $where["roomid"]=$roomid;
                    $where["weekday"]=5;
                    $programlist5=M("programmenu")
                        ->where($where)
                        ->order('sort asc')
                        ->select();

                    $where["roomid"]=$roomid;
                    $where["weekday"]=6;
                    $programlist6=M("programmenu")
                        ->where($where)
                        ->order('sort desc')
                        ->select();
                }



                $this->assign('isprograme',$isprograme);//把房间是否需要加载节目列表，传到前台

                $this->assign('programlist1',$programlist1);//把节目单绑定到前台,周一节目单
                $this->assign('programlist2',$programlist2);//周二节目单
                $this->assign('programlist3',$programlist3);
                $this->assign('programlist4',$programlist4);
                $this->assign('programlist5',$programlist5);
                $this->assign('programlist6',$programlist6);

                $this->assign('weekayyay',$weekayyay);


                $this->assign('roominfo',$roominfo);
                //根据roomid查询用户表的用户信息
                $users = M("userinfos")->field('userid,nickname,gradeid')->where(array("roomid"=>$roomid))->find();//获取房主用户id，和昵称,等级
                //查询直播大厅表返回直播室名称,和主讲
                $rh = M("livehall")->field('speaker,livename')->where(array("roomid"=>$roomid))->find();
//                $tmp=$roominfo["style"]==""?"Live":$roominfo["style"];//获取直播室页面模板
                //获取用户是否关注过次房间
                $count=M("userrooms")->field('urid')->where(array("userid"=>$userinfo['userid'],"roomid"=>$roomid))->count();
                //当前房间被关注的总数
                $gzcount=M("userrooms")->field('urid')->where(array("roomid"=>$roomid))->count();
                $map['roomid'] = array('neq',$roomid);
                $livehall = M('livehall')->field('liveid,livename,speaker,thumbnail,hits,liveurl,roomid')->where($map)->limit('7')->select();
                $vadio = M('videos')->Field('title,source,thumb,description,vurl,clickcount,nid')->order('clickcount desc')->limit(8)->select();
                $this->assign('videos',$vadio);
                // p($livehall);die;
                $this->assign('data',$livehall);
                $this->assign('focus',$count);
                $this->assign('focuscount',$gzcount);
                $this->assign('users',$users);
                $this->assign('livehall',$rh);
                $this->assign('userinfo1',$userinfo);
                $this->assign('userinfo',$userinfo['nickname']);
                $this->assign('list',$roomid);
                $this->assign("roominfo",$roominfo);
                //获取直播室的页面风格
                if($roominfo["style"]=="Livestyle1"){
                    $this->display("Live/Livestyle1:indexz");
                }else{
                    $this->display("Live:indexz");
                }
            }
            else
            {
                $this->display("error该房间号不存在");
            }
        }else{
            $this->display("Live:index");
        }

    }
    /*
     * 关注
     * */
    public function concern()
    {
        $roomid = I('post.roomid');
        $userid = session('userinfo.userid');
        $userrooms = M('userrooms');
        if (!empty($roomid)) {
            if (empty($userid)) {
                $this->ajaxReturn(false);
            } else {
                //判断是否关注过s
//                $count=M("userrooms")->field('urid')->where(array("userid"=>$userinfo['userid'],"roomid"=>$roomid))->count();
//                if($count){
//                    $this->ajaxReturn("已经关注过此房间");
//                }
                $data1['userid'] = $userid;
                $data1['roomid'] = $roomid;
                $data1['isenable'] = 1;
                $data = $userrooms->data($data1)->add();
                $this->ajaxReturn($data);
            }
        }
    }
    /*
     * 取消关注
     * */
     public function concel()
    {
        $roomid = I('post.roomid');
        $rtype = I('get.type');
        $userid = session('userinfo.userid');

        $userrooms = M('userrooms');
        if (!empty($roomid)) {
            if (empty($userid)) {
                $this->ajaxReturn(false);
            } else {
                $isenable = $userrooms->where(array('roomid'=>$roomid, 'userid'=>$userid))->field('isenable')->find();
                if ($isenable) {
                    
                $data = $userrooms->where(array('roomid'=>$roomid, 'userid'=>$userid))->delete();
                if($rtype==2){
                    $this->redirect("Subscribe/subscribe");
                }else{
                    $this->ajaxReturn($data);
                }

                }
            }
        }
    }



//用户举报处理 2016-09-20 sjy
    public function report(){

        $roomid=$_GET["roomid"];//获取传过来的房间id*/

        $this->assign("roomid",$roomid);
        $this->display("Live:report");

    }



    public function candel(){

        $roomid=$_GET["roomid"];//获取传过来的房间id*/

        /*  $roomid=1;*/
        $userinfo = session('userinfo');//获取用户信息
        $userid=$userinfo["userid"];
        $usertel=$_POST["usertel"];
        $reason=$_POST["reason"];


        if (empty($usertel)) {

            $this->error("用户联系电话不能为空");
        }

        if (empty($reason)) {

            $this->error("举报原因不能为空");
        }

        $where["userid"]=$userid;
        $where["roomid"]=$roomid;
        $where["usertel"]=$usertel;
        $where["reason"]=$reason;

        $result=M("report")
            ->data($where)
            ->add();

        if($result){
            $this->display("Live:shsuccess");

            //举报成功后进行页面跳转
        }else{
            $this->error("举报失败");
        }





    }



public function gift(){

   $webcastid=$_POST["webcastid"];

   $giftpaihang=M('usergift')
       ->where('webcastid='.$webcastid)
       ->field('frmuserid')
       ->distinct ( true )
       ->select();
     $s=count($giftpaihang);




    $git=array();




    $paiming=array();

    $jieguo=array();
for($i=0;$i<$s;$i++){
   $where["frmuserid"]=$giftpaihang[$i]["frmuserid"];
    $where["webcastid"]=$webcastid;
    $git[$i]=M('usergift')
        ->where($where)
        ->field('giftprice')
        ->select();


    $count=0;
    $geshu=count($git[$i]);
 /*   var_dump($geshu);*/

    for($j=0;$j<$geshu;$j++){
        $count+=$git[$i][$j]["giftprice"];
    }
   /* var_dump($count);
    var_dump($git[$i]);*/

    $name=M('userinfos')
        ->field('nickname')
        ->where('userid='.$giftpaihang[$i]["frmuserid"])
        ->find();

    $paiming[$name["nickname"]]=$count;
    arsort($paiming);
    $jieguo[$i]=$name["nickname"]+"-"+$count;


}


    /*var_dump($paiming);*/

 /*   $s=json_encode($paiming);*/



/*var_dump($s);*/
/*exit();*/


    $this->ajaxReturn($paiming);


}


}