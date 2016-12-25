<?php 
namespace Home\Controller;
use Think\Controller;
class AccountController extends Controller {
    public function index(){
        $this->display();
    }

    /*
     * 2016-08-25 lwx
     * 【登录】用户名（手机号）、邮箱都可登录
     * */
    public function login(){
//        $verify = new \Think\Verify ();
//        if (! $verify->check ( I('verification_code'))) {
//            $this->ajaxReturn(array('status' => 'no', 'tip' => '验证码不正确'));
//        }else{
            $password=md5(I('post.signin-password'));
            $user_name=I('post.signin-username');
                $where['username'] = $user_name;
               $where['email'] = $user_name;
               $where['phone'] = $user_name;
               $where['_logic'] = 'OR';
                $row = M("userinfos")->field("userid,userpwd,username,phone,email,nickname,usergrade,roomid")->where($where)->find();

                if ($row['userpwd'] == $password) {
//                    session('username', $user_name);
//                    session('phone', $row['phone']);
//                    session('email', $row['email']);
//                    session('userid', $row['userid']);
//                    session("nickname",$row["nickname"]);
                    session("userinfo",$row);
                    session('userinfo.userpwd',null);
                    // session(array('userinfo'=>$row,'expire'=>3600))
                    //记录log日志
//                    R("Log/add_log",array("登录账户:".$user_name,"登陆",1,2,$row['user_id'],5));
                    $row["result"]=1;
                    $this->ajaxReturn($row);
//                    echo "1";//登录成功！
                } else {
//                    R("Log/add_log",array("登录账户:".$user_name."错误密码:".I('password'),"登陆",2,2,$row['user_id'],0));
                    $row["result"]=-1;
                    $this->ajaxReturn($row);
//                    echo "-1";//帐号或者密码不正确！
                }
//        }
    }

    /*
     * 2016-08-25 lwx
     * 【注册】
     * */
    public function register(){
        $nickname=$_POST["signup-username"];
        $phone=$_POST["signup-mobile"];
        $password=md5($_POST["signup-password"]);
        $username = I('post.signup-username');
        // $where['nickname']=$nickname;
        $where["username"]=$username;
       
        if ($_SESSION['code'] != $_POST['signup-smscode']) {
            $this->ajaxReturn('-3');
        }
        $query_m = M('userinfos')->where($where)->find();//根据昵称查找
        //如果有此人昵称
        if($query_m){
           $this->ajaxReturn('-1');//此用户已存在！
        }else{
            $data["emcid"]=uniqid();
            $data["emcpwd"]='123456';
            $data["nickname"]=$nickname;
            $data["userpwd"]=$password;
            $data["phone"]=$phone;
            $data["username"]=$username;
            $result=M("userinfos")->add($data);
            if($result){
                $userinfo=M("userinfos")->field("userid,userpwd,username,phone,email,nickname")->where($where)->find();
                        session("userinfo",$userinfo);
                        //注册信息到环信上
                        vendor('emchat.Easemob');//调用环信的class类进行注册用户
                        $options['client_id']='YXA6Nt1u0HosEeaqHH3gPQ64JQ';
                        $options['client_secret']='YXA6zcifjTMaxc6_nIme-vJyanwhX6o';
                        $options['org_name']='haoontech888';
                        $options['app_name']='jiuducaijing';
                        $h=new \Easemob($options);
                        if(session("access_token")){
                            //token不为空。判读是当前时间戳和session里的进行对比否过期
                            $dd = session("expirestime");
                            $ee = time();
                            if($ee>$dd){
                                //过期了，需要重新调去token
                        //把过期时间保存到session里
                        //把当前的时间和过期秒数累计存到session里
                        $aa = $h->getToken();
                        if($aa){
                            $stime = time()+$aa['expires_in'];
                            session(array("expirestime"=>$stime,'expire'=>604800));
                            session(array('expire-in'=>$aa['expires_in'],'expire'=>604800));
                            session(array('access_token'=>$aa['access_token'],'expire'=>604800));
                            session(array('application'=>$aa['application'],'expire'=>604800));
                        }
                        $rresult = "Authorization:Bearer ".$aa['access_token'];
                    }else{
                        $rresult = "Authorization:Bearer ".session("access_token");
                    }
                }else{
                    //重新调去token()方法
                    $aa1 = $h->getToken();
                    if($aa1){
                        $stime = time()+$aa1['expires_in'];
                        session(array("expirestime"=>$stime,'expire'=>604800));
                        session(array('expire-in'=>$aa1['expires_in'],'expire'=>604800));
                        session(array('access_token'=>$aa1['access_token'],'expire'=>604800));
                        session(array('application'=>$aa1['application'],'expire'=>604800));
                    }
                    $rresult = "Authorization:Bearer ".$aa1['access_token'];
                }
                $arrResult = $h->createUser($data["emcid"],$data["emcpwd"],$rresult);
//                    var_dump($arrResult);
                //注册或删除失败时，uuid为空。根据uuid是否有值 判断操作是否成功
                $result = $arrResult['entities'][0]['uuid']?$arrResult['entities'][0]['uuid']:"";
                if($result){
                    $this->ajaxReturn('1');//注册成功！
                }else{
                    $this->ajaxReturn('1');//注册成功！
                }
            }else{
                $this->ajaxReturn('-2');//注册失败！
            }
        }
    }
    //短信接口
    public function send_sms($phone,$sms_count=10){
        $code = randNumber(4);	//验证码
        $result=1;
        $message="口令以短信的形式已发送到您的手机中，请及时接收";
        //用户账号
        $uid = C('SMS_USER_NAME');
        $pwd =  C('SMS_PASSWORD');
        $mobile	 = $phone;
        $content = '您的验证码是'.$code.'，请提交验证码完成验证。如果有问题请拔打电话：15221671837。【九度财经直播室】';
        $res = sendSMS($uid,$pwd,$mobile,$content);
        if($res['stat']=='100') {
            $result=1;
            $message="口令以短信的形式已发送到您的手机中，请及时接收";
            $_SESSION["code"] = $code;
        }
        else {
            $result=0;
            $message="验证码发送失败,请您联系客服".$res['stat'].'|'.$res['message'];

            //echo "发送失败! 状态：".$res['stat'].'|'.$res['message'];
        }
        echo $message;
    }

    /*
     *忘记密码*/
    public function forgetpwd(){
        if (IS_POST) {
            $where['username']=$_POST["name"];
            $where['phone']=$_POST["phone"];
            if ($_SESSION['code'] != $_POST['signup-smscode']) {
                $this->error('验证码错误');
            }
            $query_m = M('userinfos')->where($where)->find();//根据昵称查找
            // p($query_m);die;
            if ($query_m) {
                $this->assign('userid',$query_m['userid']);
                $this->display('Account/steppwd');
            }else{
                $this->error('用户名不存在');
            }
            //如果有此人昵称
        }else{

        $this->display('Account/forgetpwd');
        }
    }

    // 忘记密码操作
    public function forgetpwdDo()
    {
        if (IS_POST) {
            $userid = I('post.userid');
            $pwd = I('post.pwd');
            if (md5($_POST['pwd']) != md5($_POST['repwd'])) {
                $this->error('两次输入的密码不一致');
            }
            if ($userid) {
                $pwd = M('userinfos')->where(array('userid'=>$userid))->setField('userpwd',md5($pwd));
                if ($pwd) {
                    $this->success('修改成功',U('Index/index'));
                }
            }else{
                $this->error('修改失败');
            }
        }else{
            $this->error('找不到页面');
        }
    }

    // 退出1
    public function exitUser(){
        session("userinfo",null);
    }
}