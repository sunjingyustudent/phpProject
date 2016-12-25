<?php
namespace Home\Controller;
use Think\Controller;
class PiliBackUrlController extends Controller {
    public function getBackUrl(){
        $log=new \Think\Log();

        $infomation=file_get_contents("php://input");
//        $log->write("记录当前运行日志,接收到的使用 php://input接收信息:".$infomation);

        $delegateJson=json_decode($infomation,true);
//        $log->write("记录当前运行日志,接收到的POST请求:".$delegateJson);


//        $message=$delegateJson["message"];
//        $log->write("记录当前运行日志,接收到的参数信息Message:".$message);

        $updatedAt=$delegateJson["updatedAt"];
        $log->write("记录当前运行日志,接收到的参数信息UpdateAt:".$updatedAt);

        $id=$delegateJson["data"]["id"];
        $log->write("记录当前运行日志,接收到的参数信息ID:".$id);
        $url=$delegateJson["data"]["url"];
        $log->write("记录当前运行日志,接收到的参数信息URL:".$url);
        $status=$delegateJson["data"]["status"];
        $log->write("记录当前运行日志,接收到的参数信息Status:".$status);

        $roomstatus=M("rooms");
        if($status=="disconnected"){
            $result=$roomstatus->where("webcastkeyid='$id'")->setField("status",2);
            $log->write("记录当前运行日志,推流已结束");

        }else{
            $result=$roomstatus->where("webcastkeyid='$id'")->setField("status",1);
            $log->write("记录当前运行日志,正在推流中");
        }

    }
}