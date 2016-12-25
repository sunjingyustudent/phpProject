<?php
namespace Home\Controller;
use Think\Controller;
class AboutusController extends Controller {
    public function index(){
        $this->display();
    }

    /*
     * 2016-08-27 lwx
     * 免责声明  动态获取
     * http://localhost:8080/jiudcjweb/Home/aboutus/disclaimer.html
     * */
    public function disclaimer(){
        $disclaimer=M('company')->field('disclaimer')->find();
        if(!empty($disclaimer)){
            $this->assign("detail",$disclaimer);
        }else{
            $this->assign("detail","免责声明稍后告知！");
        }
        $title = M('category')->where(array('catid'=>21))->field('title,keywords,description')->find();
        $this->assign('title',$title);
        $this->display();
    }

    /*
     * 2016-09-13 lwx
     * 用户协议  动态获取  Live tutorial
     * http://localhost:8080/jiudcjweb/Home/aboutus/disclaimer.html
     * */
    public function useragreement(){
        $disclaimer=M('company')->field('useragreement')->find();
        if(!empty($disclaimer)){
            $this->assign("detail",$disclaimer);
        }else{
            $this->assign("detail","用户协议稍后告知！");
        }
        $title = M('category')->where(array('catid'=>27))->field('title,keywords,description')->find();
        $this->assign('title',$title);
        $this->display();
    }

    /*
     * 2016-09-21 lwx
     * 直播教程  静态页
     * http://localhost:8080/jiudcjweb/Home/aboutus/livetutorial.html
     * */
    public function livetutorial(){
        $title = M('category')->where(array('catid'=>23))->field('title,keywords,description')->find();
        $this->assign('title',$title);
        $this->display();
    }

    /*
     *2016-09-27 lwx
     * 关于我们
     * */
    public function introduction(){
        $title = M('category')->where(array('catid'=>20))->field('title,keywords,description')->find();
        $this->assign('title',$title);
        $this->display();
    }


    /*
     * 2016-08-27 lwx
     * 网站地图 静态页
     * http://localhost:8080/jiudcjweb/Home/aboutus/sitemap.html
     * */
    public function sitemap(){
        $title = M('category')->where(array('catid'=>22))->field('title,keywords,description')->find();
        $this->assign('title',$title);
        $this->display();
    }

    /*
     * 2016-08-27 lwx
     * 网站地图 静态页
     * http://localhost:8080/jiudcjweb/Home/aboutus/sitemap.html
     * */
    public function feinong(){
        //获取最新视频前八条
        $hotLive=M()->query("select speaker,hits,roomtitle,headimage,liveurl,status,roomid from wht_rooms where roomid in(1,5,6,2) and isrecommend=1 and isdelete=0");
        shuffle($hotLive);
        $title = M('category')->where(array('catid'=>15))->field('title,keywords,description')->find();
        $this->assign("hotLive",$hotLive);
        $this->assign('title',$title);
        $this->display();
    }
}