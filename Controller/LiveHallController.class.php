<?php
namespace Home\Controller;
use Think\Controller;
class LiveHallController extends BaseController {
    function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $cate = M('classify')->field('classifyname,classifyid')->select();
        $this->assign('cate',$cate);
    }

    public function LiveList(){
        //获取最新视频前八条
        $hotLive=M("rooms")->where("isdelete=0")->select();
        $hotLive1=M("rooms")->where(array('isdelete'=>0,'speaker'=>'白银、珍珠'))->limit(4)->select();
        // p($hotLive);die;
        //获取股市直播列表
        $stock_market=M("rooms")->where("isdelete=0 and classifyid=1 and status=1")->select();


        //获取期货直播列表
        $futures=M("rooms")->where("isdelete=0 and classifyid=2 and status=1")->select();

        //获取贵金属直播列表
        $precious_metal=M("rooms")->where("isdelete=0 and classifyid=3 and status=1")->select();
        //传参
        //前八条最新
        $this->assign("hotLive",$hotLive);
        $this->assign("hotLive1",$hotLive1);
        //股市直播传参
        $this->assign("stock_market",$stock_market);
        //期货直播而列表
        $this->assign("futures",$futures);
        //贵金属直播传参
        $this->assign("precious_metal",$precious_metal);
        //跳转页面
        $this->display("LiveHall:LiveList");
    }


    public function apply(){
        //判断是否是post提交
        if(IS_POST){
            //判断直播室名称和联系人姓名是否为空
            if(empty($_POST['livename'])){
               $this->error('直播室名称不能为空');
            }
            if(empty($_POST['contacter'])){
                $this->error('联系人姓名不能为空');
            }
            $data = I('post.');
            //实例化
            $application = M('applicationforms');
            //执行插入操作
            $appid = $application->data($data)->add();
            if($appid){
                $this->success('添加成功',U('Home/LiveHall/LiveList'));
            }else{
                $this->error('添加失败');
            }
        }
    }

    //点击更多
    public function getMore(){
        $catid=$_GET["catid"];

        $cat=M("category")->where("catid=$catid")->find();
        $lives=M("videos")->where("catid=$catid")->select();

        $this->assign("category",$cat);
        $this->assign("lives",$lives);
        $this->display("LiveHall:getMore");
    }


/*
 * 显示机构申请页面
 *
 * 2016-09-14 sjy
 * */
    public function organization(){
        if (!session('userinfo.userid')) {
            $this->ajaxReturn('-1');//用户未登录
        }
        $userid = session('userinfo.userid');
        $shenhe = M('userinfos')->where(array('userid'=>$userid))->getField('accredid');
        $shenhea = M('applicationforms')->where(array('uid'=>$userid))->getField('aid');
        if ($shenhea) {
            $this->ajaxReturn('-3');//用户重复提交
        }
        if ($shenhe) {
            $this->ajaxReturn('-2');//用户重复提交
        }
       $this->display("LiveHall:organization");
    }

    public function organDo()
    {
        if(!IS_POST){
            $this->error('找不到页面');
        }
        // p($_POST);die;
        // p($_FILES);die;

        $user=session("userinfo");
        $userid=$user["userid"];
//        $result=$this->upload();
//        $savepath[1]="/Upload/".$user['username'].'/'.$result[0]["savename"];
//        $savepath[2]="/Upload/".$user['username'].'/'.$result[1]["savename"];
//        $savepath[3]="/Upload/".$user['username'].'/'.$result[2]["savename"];
//        $savepath[4]="/Upload/".$user['username'].'/'.$result[3]["savename"];


        $setting=C('UPLOAD_SITEIMG_OSS');
        $Upload = new \Think\Upload($setting);

        if(!empty($_FILES['pic']['tmp_name']))
        {
            $info1 = $Upload->upload($_FILES);
            $imgurl = OSS.$info1[pic][savepath].$info1[pic][savename];
            $savepath1 = $imgurl;
        }
        if(!empty($_FILES['pic1']['tmp_name']))
        {
            $info = $Upload->upload($_FILES);
            $imgurl3 = OSS.$info[pic1][savepath].$info[pic1][savename];
            $savepath2 = $imgurl3;
        }
        if(!empty($_FILES['pic2']['tmp_name']))
        {
            $info4 = $Upload->upload($_FILES);
            $imgurl4 = OSS.$info4[pic2][savepath].$info4[pic2][savename];
            $savepath3 = $imgurl4;
//            var_dump($info4);
//            var_dump($savepath3);
        }
        if(!empty($_FILES['pic3']['tmp_name']))
        {
            $info3 = $Upload->upload($_FILES);
            $imgurl1 = OSS.$info3[pic3][savepath].$info3[pic3][savename];
            $savepath4 = $imgurl1;
        }
        //  p($result);
        // p($savepath);
        //  p($_POST);die;
//        var_dump($info3);
//        var_dump($info2);
//        var_dump($info1);
//        var_dump($info);
        $data['yypic'] = $savepath1;
        $data['jrpic'] = $savepath2;
        $data['idpic'] = $savepath3;
        $data['idfpic'] = $savepath4;
        $data['applyuser'] = $_POST['applyuser'];
        $data['branchesname'] = $_POST['branchesname'];
        $data['contacter'] = $_POST['contacter'];
        $data['branchestype'] = $_POST['branchestype'];
        $data['idbnumber'] = $_POST['idnumber'];
        $data['aphone'] = $_POST['aphone'];
        $data["uid"]=$userid;
        $id = M('applicationforms')->data($data)->add();
        M('userinfos')->where(array('userid'=>$userid))->setField('usertype',2);
        if ($id) {
            $this->display('LiveHall/shenhec');
        } else{
            $this->error('提交错误');
        }
    }

  /*
 * 显示个人申请页面
 *
 * 2016-09-14 sjy
 * */
    public function person(){
        if (!session('userinfo.userid')) {
            $this->ajaxReturn('-1');//用户未登录
        }
        $userid = session('userinfo.userid');
        $type = 1;
        $shenhep = M('userinfos')->where(array('userid'=>$userid))->getField('accredid');
        $shenhe = M('applicationforms')->where(array('uid'=>$userid))->getField('aid');
        if ($shenhep) {
            $this->ajaxReturn('-2');//用户提交重复
        }
        if ($shenhe) {
            $this->ajaxReturn('-3');
        }
        
        // p(session());die;
       $this->assign('type',$type);
       $this->display("LiveHall:person");
    }
  

    public function personDo()
    {
        if(!IS_POST){
            $this->error('找不到页面');
        }
        $user=session("userinfo");
        $userid=$user["userid"];


        if(!empty($_FILES['upload']['tmp_name']))
        {
            $setting=C('UPLOAD_SITEIMG_OSS');
            $Upload = new \Think\Upload($setting);
            $info1 = $Upload->upload($_FILES);
            $imgurl = OSS.$info1[upload][savepath].$info1[upload][savename];
            $savepath[1] = $imgurl;
        }
        if(!empty($_FILES['upload1']['tmp_name']))
        {
            $setting=C('UPLOAD_SITEIMG_OSS');
            $Upload = new \Think\Upload($setting);
            $info = $Upload->upload($_FILES);
            $imgurl1 = OSS.$info[upload1][savepath].$info[upload1][savename];
            $savepath[2] = $imgurl1;
        }
//        $result=$this->upload();
//        $savepath[1]="/Upload/".$user['username'].'/'.$result[0]["savename"];
//        $savepath[2]="/Upload/".$user['username'].'/'.$result[1]["savename"];
        //  p($result);
        // p($savepath);
        //  p($_POST);die;
        $data['idcardimg'] = $savepath[1];
        $data['idcardinvimg'] = $savepath[2];
        $data['realname'] = $_POST['name'];
        $data['phone'] = $_POST['phone'];
        $data['idcard'] = $_POST['idcard'];
        $data['trproducts'] = $_POST['trproducts'];
        if ($info&&$info1) {
            $id = M('accred')->data($data)->add();
            $accred = M('userinfos')->where(array('userid'=>$userid))->setField("accredid",$id);
            M('userinfos')->where(array('userid'=>$userid))->setField('usertype',2);
            if ($accred) {
                $this->display('LiveHall/shenhe');
            } else{
                $this->error('提交错误');
            }
        } else {
            $this->error('照片上传失败');
        }
    }

    // 修改认证信息
    public function organizationedit()
    {
        // p($_GET);die;
        $userid = I('get.userid');
        if (IS_POST) {
            $user=session("userinfo");
        $userid=$user["userid"];
//        $result=$this->upload();
//        $savepath[1]="/Upload/".$user['username'].'/'.$result[0]["savename"];
//        $savepath[2]="/Upload/".$user['username'].'/'.$result[1]["savename"];
//        $savepath[3]="/Upload/".$user['username'].'/'.$result[2]["savename"];
//        $savepath[4]="/Upload/".$user['username'].'/'.$result[3]["savename"];


        $setting=C('UPLOAD_SITEIMG_OSS');
        $Upload = new \Think\Upload($setting);

        if(!empty($_FILES['pic']['tmp_name']))
        {
            $info1 = $Upload->upload($_FILES);
            $imgurl = OSS.$info1[pic][savepath].$info1[pic][savename];
            $savepath1 = $imgurl;
        }
        if(!empty($_FILES['pic1']['tmp_name']))
        {
            $info = $Upload->upload($_FILES);
            $imgurl3 = OSS.$info[pic1][savepath].$info[pic1][savename];
            $savepath2 = $imgurl3;
        }
        if(!empty($_FILES['pic2']['tmp_name']))
        {
            $info4 = $Upload->upload($_FILES);
            $imgurl4 = OSS.$info4[pic2][savepath].$info4[pic2][savename];
            $savepath3 = $imgurl4;
//            var_dump($info4);
//            var_dump($savepath3);
        }
        if(!empty($_FILES['pic3']['tmp_name']))
        {
            $info3 = $Upload->upload($_FILES);
            $imgurl1 = OSS.$info3[pic3][savepath].$info3[pic3][savename];
            $savepath4 = $imgurl1;
        }
        //  p($result);
        // p($savepath);
        //  p($_POST);die;
//        var_dump($info3);
//        var_dump($info2);
//        var_dump($info1);
//        var_dump($info);
        $data['yypic'] = $savepath1;
        $data['jrpic'] = $savepath2;
        $data['idpic'] = $savepath3;
        $data['idfpic'] = $savepath4;
        $data['applyuser'] = $_POST['applyuser'];
        $data['branchesname'] = $_POST['branchesname'];
        $data['contacter'] = $_POST['contacter'];
        $data['branchestype'] = $_POST['branchestype'];
        $data['idbnumber'] = $_POST['idnumber'];
        $data['aphone'] = $_POST['aphone'];
        // $data["uid"]=$userid;
        $id = M('applicationforms')->data($data)->where(array('uid'=>$userid))->save();
        M('userinfos')->where(array('userid'=>$userid))->setField('usertype',2);
        // p($id);die;
        if ($id) {
            $this->display('LiveHall/shenhec');
        } else{
            $this->error('提交错误');
        }
        } else {
            $list = M('applicationforms')->where(array('uid'=>$userid))->field('branchesname,applyuser,aphone,contacter,idbnumber')->find();
            // p($list);die;
            $this->assign('list',$list);
            $this->display('LiveHall/organizationedit');
        }
    }
    // 个人审核编辑
    public function personedit()
    {   
        $userid = I('get.userid');
        if (IS_POST) {
            if(!empty($_FILES['upload']['tmp_name']))
            {
                $setting=C('UPLOAD_SITEIMG_OSS');
                $Upload = new \Think\Upload($setting);
                $info1 = $Upload->upload($_FILES);
                $imgurl = OSS.$info1[upload][savepath].$info1[upload][savename];
                $savepath[1] = $imgurl;
            }
            if(!empty($_FILES['upload1']['tmp_name']))
            {
                $setting=C('UPLOAD_SITEIMG_OSS');
                $Upload = new \Think\Upload($setting);
                $info = $Upload->upload($_FILES);
                $imgurl1 = OSS.$info[upload1][savepath].$info[upload1][savename];
                $savepath[2] = $imgurl1;
            }
    //        $result=$this->upload();
    //        $savepath[1]="/Upload/".$user['username'].'/'.$result[0]["savename"];
    //        $savepath[2]="/Upload/".$user['username'].'/'.$result[1]["savename"];
            //  p($result);
            // p($savepath);
            //  p($_POST);die;
            $data['idcardimg'] = $savepath[1];
            $data['idcardinvimg'] = $savepath[2];
            $data['realname'] = $_POST['name'];
            $data['phone'] = $_POST['phone'];
            $data['idcard'] = $_POST['idcard'];
            $data['trproducts'] = $_POST['trproducts'];
            $accredid = I('post.accredid');
            if ($info&&$info1) {
                $id = M('accred')->data($data)->where(array('accredid'=>$accredid))->save();
                M('userinfos')->where(array('userid'=>$userid))->setField('usertype',2);
                // p($id);die;
                // $accred = M('userinfos')->where(array('userid'=>$userid))->setField("accredid",$id);
                if ($id) {
                    $this->display('LiveHall/shenhe');
                } else{
                    $this->error('提交错误');
                }
            } else {
                $this->error('照片上传失败');
            }
        } else {
            // $accredid = M('userinfos')->where(array('uid'=>$userid))->getField('accredid');
            $list = M('userinfos')->join('wht_accred ON wht_userinfos.accredid=wht_accred.accredid')->where(array('userid'=>$userid))->field('realname
                ,idcard,wht_accred.phone,trproducts,wht_accred.accredid')->find();

            // p($list);die;
            $this->assign('list',$list);
            $this->display('LiveHall/personedit');
        }
    }
    
}