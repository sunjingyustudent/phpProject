<?php
namespace Home\Controller;
use Think\Controller;
class RecordsOfConsumptionController extends BaseController {
    function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        if(session("userinfo")==null){
            echo "<script>alert('请先登录！')</script>";
            $this->display("Index:index");
        }
    }

    /*
     *获取用户消费记录
     *2016-08-31 sjy
     *
  */
      public function index(){

          $user=session("userinfo");
          $this->assign("userinfo",$user);

          $userid  = session('userinfo.userid');
          // p($userid);die;
          $recharge = M('recharge');
          $result=$recharge
              ->where(array('userid'=>$userid))
              ->select();

          // p($result);die;
          if($result>0){
              $this->assign("lists", $result);
              $this->display("RecordsOfConsumption:index");
          }else{
              $this->error("失败");
          }

      }


    //发送礼物id，我发送给谁
    /*
   *发送礼物id，我发送给谁
   *2016-08-31 sjy
   *
*/
public function gift(){
    $userid  = session('userinfo.userid');//获取当前用户id
    $where["frmuserid"]=$userid;//测试用userid
    $usergift = M('usergift');
    $result=$usergift
        ->where($where)
        ->join("left join wht_userinfos as u on u.userid = wht_usergift.frmuserid")
        ->field('u.nickname,wht_usergift.*')
        ->order('wht_usergift.createtime DESC')
        ->select();

    if($result>0){
        $this->assign("lists", $result);
        $this->display("RecordsOfConsumption:gift");
    }else{
        $this->error("失败");
    }
}

/*
 *
 * 红包发送记录  sjy 2016-08-31 发送红包
 * */
public function redpacket(){

    $userid  = session('userinfo.userid');//获取当前用户id
    // $where["frmuserid"]=$userid;
    $where["userid"]=$userid;//测试用userid
    $where["isdelete"]=0;
    $usergift = M('redpackets');
    $result=$usergift
        ->where($where)
        ->order('createtime DESC')
        ->select();

    if($result>0){
        $this->assign("lists", $result);
        $this->display("RecordsOfConsumption:redpacket");
    }else{
        $this->error("失败");
    }



}

    /*
     *
     * 用户抢红包交易记录 sjy 2016-08-31
     *
     * */
    public function red(){

         $userid  = session('userinfo.userid');//获取当前用户id
        // $where["frmuserid"]=$userid;
        $where["touserid"]=$userid;//测试用userid
        $where["isdelete"]=0;
        $recpacketrecord = M('recpacketrecord');
        $result=$recpacketrecord
            ->where($where)
            ->order('createtime DESC')
            ->select();

        if($result>0){
            $this->assign("lists", $result);
            $this->display("RecordsOfConsumption:red");
        }else{
            $this->error("失败");
        }



    }




}