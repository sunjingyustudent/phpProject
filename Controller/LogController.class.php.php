<?php
namespace Home\Controller;
use Think\Controller;
class LogController extends Controller {
    public function add_log($message,$source,$status,$type,$id,$key=0,$value=null){
        $data['message']=$message;
        $data['source']=$source;
        $data['status']=$status;
        $data['type']=$type;
        $data['id']=$id;
        $data['create_date']=time();
        $data['value']=$value;
        $data['key']=$key;
        $data['ip']=$_SERVER['REMOTE_ADDR'];
        M('log')->add($data);
    }
}