<?php
namespace Home\Controller;
use Think\Controller;

/**
 * Base基类控制器
 */
class BaseController extends Controller{
    /**
     * 初始化方法
     */
    public function _initialize(){
        $userinfo=session("userinfo");
        if($userinfo!=null && $userinfo["userid"]>0){
            if($userinfo["nickname"]!=null){
                $this->assign("userinfo",$userinfo["nickname"]);
            }else{
                $this->assign("userinfo",$userinfo["username"]);
            }

        }
        
    }

    //提交图片方法
    public function upload(){
        $userinfo=session("userinfo");

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Upload/'; // 设置附件上传根目录
        $upload->subName  = $userinfo["username"] ; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            return $info;
        }
    }

    //添加购买信息
    /*
     * 用户充值表
     * $totalprice:订单总金额
     * $paymentby:支付方式：1：支付宝 2：微信 3: 银行卡 4：苹果内购
     * $orderdate:支付完成时间
     * $realprice:订单支付金额
     *
     *交易信息表
     * $paytype：支付来源：1、网页版支付 2、手机版支付
     * $payby:支付品牌: 1：支付宝 2：易宝 3：微信 4：银联 5：苹果内购
     * $sellerid:商品编号，商户号
     * $outtradeno:商户订单号
     * $buyerid：买家用户号
     * $buyer:买家支付账号
     * $tradeno:系统交易流水号
     * $totalfee:交易金额
     * $tradestatus:交易状态
     * $dealtime:成交时间
     * $banktype:银行通道类型
     * $bankorderid:银行订单号
     * $tradefee:商户手续费
     */
    public function buyInfo($totalprice,$paymentby,$realprice,$orderdate,
                            $paytype,$payby,$sellerid,$outtradeno,$buyerid,$buyer,$tradeno,$totalfee,$tradestatus,$dealtime,$banktype=null,$bankorderid=null,$tradefee=null){
        $user=session("userinfo");
        $userid=$user["userid"];
        if(empty($userid)&&$userid<=0){
            //取android支付的userid
            $userid = session("phonepay");
            if(empty($userid)&&$userid<=0){
                return;
            }
        }
        //插入用户充值表
        $data["userid"]=$userid;
        $data["totalprice"]=$totalprice;
        $data["paymentby"]=$paymentby;
        $data["realprice"]=$realprice;
        $data["orderdate"]=$orderdate;
        $data["paystatus"]=1;
        $data["specialdes"]="购买".(floatval($totalprice)*6)."个钻石";
        $data["isdelete"]=0;
        $result=M("recharge")->add($data);

        if($result){
            $array["paytype"]=$paytype;
            $array["payby"]=$payby;
            $array["rechargeid"]=$result;
            $array["sellerid"]=$sellerid;
            $array["outtradeno"]=$outtradeno;
            $array["buyerid"]=$buyerid;
            $array["buyer"]=$buyer;
            $array["tradeno"]=$tradeno;
            $array["totalfee"]=$totalfee;
            $array["tradestatus"]=$tradestatus;
            $array["dealtime"]=$dealtime;
            $array["banktype"]=$banktype;
            $array["bankorderid"]=$bankorderid;
            $array["tradefee"]=$tradefee;
            $result=M("payment")->add($array);
        }

        $usernine=M("userinfos")->where("userid=$userid")->getfield("ninemoney");
        //只能变量和变量加
        $ap = $totalprice*6;
        $jg =$usernine+$ap;
        $result=M("userinfos")->where("userid=$userid")->setField("ninemoney",$jg);
    }

}
