<?php
namespace Home\Controller;
use Think\Controller;

class SynopsisController extends Controller
{
    public function index()
    {
    	$userinfo = session('userinfo.nickname');//获取用户信息
        $liveid = I('get.liveid');
        //获取简介详细信息
        $title =M('rooms')->where(array('roomid'=>$liveid))->find();

        $this->assign('userinfo',$userinfo);
        $this->assign('title',$title);
        $this->display('Synopsis/index');
    }
}
