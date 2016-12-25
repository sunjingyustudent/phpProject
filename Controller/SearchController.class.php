<?php
namespace Home\Controller;
use Think\Controller;
class SearchController extends BaseController {

	public function index()
	{
		 $searchname = I('post.search');
         $key=I('get.key');
         // dump($searchkey);exit();
        if (empty($searchname)&&empty($key)) {
         $this->error('搜索条件不能为空');
        }
        //相关视频
        $searchvideo = M('videos');
        if($searchname){
            $wher['title|source|description'] = array('like',"%$searchname%");
        }
        if($key){
            $wher['title|source|description'] = array('like',"%$key%");
        }
        $data = $searchvideo->where($wher)->limit(12)->where(array('post_status'=>1))->select();
        // //相关主播
        // $userinfos = M('userinfos');
        // $whe['username'] = array('like',"%$searchname%");
        // $whe['nickname'] = array('like',"%$searchname%");
        // $whe['_logic'] = 'OR';
        // $data = $userinfos->where($whe)->where(array('usertype'=>2))->select();
        //相关直播
        $rooms = M('rooms');
        if($searchname){
        $wh['roomtitle|roomcode|rdescription'] = array('like',"%$searchname%");
        }
        if($key){
        $wh['roomtitle|roomcode|rdescription'] = array('like',"%$key%");   
        }
        $searchkey=M('searchkey')->order('sort asc')->select();
        //$web = $rooms->field('wht_webcast.webcoverurl,wht_webcast.webstatus,wht_rooms.*')->join('wht_webcast on wht_webcast.roomid=wht_rooms.roomid')->where($wh)->limit(12)->select();
		$web = $rooms->where($wh)->limit(12)->select();
        $this->assign('web',$web);
        $this->assign('searchkey',$searchkey);
        $this->assign('data',$data);
        $this->display('Search/index');

    }
}