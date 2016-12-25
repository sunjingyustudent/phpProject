<?php
/*
 * 财经头条控制器
 *
 */
namespace Home\Controller;
use Think\Controller;
class NewsController extends BaseController {


    public function index(){

		$postnews=M('posts');
        $wherelist['post_status']=1;//查询已审核的文章
      /*  $wherelist['post_status']=1;*/
       $list= $postnews->where($wherelist)->order('id desc')->limit(20)->select();
        //查询20条已审核的文章
        $where['recommended']=1;
        $where['post_status']=1;
        $recommend= $postnews->where($where)->order('id desc')->limit(10)->select();
        //查询十条热门推荐
        $this->assign("list",$list);
        $this->assign("recommend",$recommend);
		$this->display();
       }
    public function  content(){
        $getid=I('get.id');
        $postnews=M('posts');
        $where['id']=$getid;
        $content= $postnews->where($where)->find();
        //上一篇
        $front=$postnews->where("id<".$getid)->order('id desc')->limit('1')->find();
        $this->assign('front',$front);
        //下一篇
        $after=$postnews->where("id>".$getid)->order('id desc')->limit('1')->find();
        $where1['recommended']=1;
        $where1['post_status']=1;
        $recommend= $postnews->where($where1)->order('id desc')->limit(10)->select();
		$title = M('posts')->where(array('id'=>$getid))->field('post_title,post_keywords,post_excerpt')->find();
        $result = array(
            "title"=>$title[post_title].'-9度财经直播',
            "keywords"=>$title[post_keywords],
            "description"=>$title[post_excerpt],
        );
//        var_dump($result);
        $this->assign('title',$result);
        $this->assign("recommend",$recommend);
        $this->assign('after',$after);
        $this->assign('content',$content);
        $this->display();

    }




    public  function getmore(){
        $num=$_POST["num6"];

        $postnews=M('posts');

        $content= $postnews
            ->limit($num,5)
            ->select();


//          var_dump($content);
       $this->ajaxReturn($content);
    }

}