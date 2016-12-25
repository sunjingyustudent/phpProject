<?php
namespace Home\Controller;
use Think\Controller;
class PhonePayController extends BaseController {
    public function index(){
        $this->display();
    }


    /*
     * 支付页面 android版暂时支付h5页面
     * http://localhost:8080/jiuducaijingwebapi/index.php/Home/Index/alipay/accesstoken/1
     * */
    public function alipay(){

        \Predis\Autoloader::register();
        $redis = new \Predis\Client();
        $token=$_GET["accesstoken"];
        if($redis->exists($token))
        {
            $re=$redis ->hgetall($token);//添加到redis缓存中
            if(!empty($re)){
                if($re['uid']>0){
                    $count= M('userinfos')->where(array("userid"=>$re['uid']))->count();
                    if($count<=0){
                        $this->error("用户不存在！");
                    }
                    //把用户id返回页面上
                    $this->assign('userid', $re['uid']);// 赋值数据   集
                    $this->display();
                }else{
                    $this->error("您当前没有登录，不是本站会员用户！");
                }
            }
            else{
                $this->error("您当前没有登录，不是本站会员用户！");
            }
        }
        else{
            $this->error("您当前没有登录，不是本站会员用户！");
        }
    }
}