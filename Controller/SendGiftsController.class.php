<?php
namespace Home\Controller;
use Think\Controller;
class SendGiftsController extends Controller {
    /*
     * 2016-09-01  sjy
     *
     * 发送礼物
     * */
    public function gift(){
        $gifts = M('gifts');
        $where["isdelete"]=0;
        $giftslist=$gifts
            ->where($where)
            ->select();
      $userinfo=session("userinfo");
       // var_dump($userinfo);

        if($giftslist>0){
            $this->assign("userinfo", $userinfo);
            $this->assign("lists", $giftslist);
            $this->display("SendGifts:gift");
        }else{
            $this->error("失败");
        }

    }


}