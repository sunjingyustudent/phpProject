<?php
namespace Home\Controller;
use Think\Controller;
class VideoSchoolController extends BaseController {

  public function _initialize(){
        $nid = I('get.nid');
        if ($nid) {
          $title = M('videos')->where(array('nid'=>$nid))->field('title,keywords,description')->find();
		  $result = array(
            "title"=>$title[title].'-9度财经直播',
            "keywords"=>$title[keywords],
            "description"=>$title[description],
        );
          $this->assign('title',$result);
        }
//        else{
//          $title = M('category')->where(array('catid'=>3))->field('title,keywords,description')->find();
//          $this->assign('title',$title);
//        }
               
    }

  /*
   *获取头条信息，和视学堂首页信息
   *2016-08-25 sjy
   *
*/
      public function headerpage(){
          //获取头部分类
          $category = M("category");
          $videosheader=$category
                        ->field("catname")
                        ->where('parentid=3')
                        ->order('sort desc')
                        ->select();
          $this->assign("lists", $videosheader);

          //获取精彩视频
          $Video = M("videos");

          $Wonderful=$Video
              ->where("catid=6")
              ->order('sort desc')
              ->limit(9)
              ->select();
         // var_dump($Wonderful);
          $Wonderful2=null;
          $Wonderful1=$Wonderful[0];
           for($i=1;$i<9;$i++){
               $Wonderful2[$i-1]=$Wonderful[$i];

           }
          //var_dump($Wonderful1);
         // var_dump("54635");
         // var_dump($Wonderful2);
          $this->assign("Wonderful1", $Wonderful1);
          $this->assign("Wonderful2", $Wonderful2);


          //获取数据专区
          $Dataspacial=$Video
              ->where("catid=7")
              ->order('sort desc')
              ->limit(9)
              ->select();
         // var_dump($Dataspacial);
          $Dataspacial1=$Dataspacial[0];
          $Dataspacial2=null;

          for($i=1;$i<9;$i++){
              $Dataspacial2[$i-1]=$Dataspacial[$i];

          }


          $this->assign("Dataspacial1", $Dataspacial1);
          $this->assign("Dataspacial2", $Dataspacial2);



          //实战教学
          $RealTeach=$Video
              ->where("catid=8")
              ->order('sort desc')
              ->limit(9)
              ->select();
         // var_dump($RealTeach);
          $RealTeach1=$RealTeach[0];
          $RealTeach2=null;

          for($i=1;$i<9;$i++){
              $RealTeach2[$i-1]=$RealTeach[$i];

          }
          $this->assign("RealTeach1", $RealTeach1);
          $this->assign("RealTeach2", $RealTeach2);


          //基础教学
          $BaseTeach=$Video
              ->where("catid=9")
              ->order('sort desc')
              ->limit(9)
              ->select();
         // var_dump($BaseTeach);
          $BaseTeach1=$BaseTeach[0];
          $BaseTeach2=null;

          for($i=1;$i<9;$i++){
              $BaseTeach2[$i-1]=$BaseTeach[$i];

          }
          $this->assign("BaseTeach1", $BaseTeach1);
          $this->assign("BaseTeach2", $BaseTeach2);


          $this->display('VideoSchool/headerpage');

      }


    /*
     * 2016-09-26 lwx
     * 页面分类 数据专区等
     * */
    public function videolisttype(){
        $type = I('get.id');
        $Video = M("videos");
        $count      = $Video->where(array("catid"=>$type))->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,C('PageCounts'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $Dataspacial = $Video->where(array("catid"=>$type))->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $title = M('category')->where(array('catid'=>$type))->field('title,keywords,description')->find();
        $this->assign('title',$title);
        $this->assign("Dataspacial", $Dataspacial);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('type',$type);
        $this->display('VideoSchool/videolisttype');
    }


    /*
   *获取精彩视频
   *2016-08-25 sjy
   *
*/
    public function WonderfulVideo(){
        //获取精彩视频
        $Video = M("videos");
        // $Wonderful=$Video
        //     ->where("catid=6")
        //     ->order('sort desc')
        //     ->select();
        
        $count      = $Video->where('catid=6')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $Wonderful = $Video->where('catid=6')->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('Wonderful',$Wonderful);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('VideoSchool/WonderfulVideo');

    }


    /*
   *获取数据专区
   *2016-08-25 sjy
   *
*/
    public function Dataspacial(){
        //获取数据专区
        $Video = M("videos");
        $count      = $Video->where('catid=7')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $Dataspacial = $Video->where('catid=7')->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("Dataspacial", $Dataspacial);
        $this->assign('page',$show);// 赋值分页输出
        $this->display('VideoSchool/Dataspacial');
    }

    /*
  *获取实战教学
  *2016-08-25 sjy
  *
*/
    public function RealTeach(){
        $Video = M("videos");
        // $RealTeach=$Video
        //     ->where("catid=8")
        //     ->order('sort desc')
        //     ->select();


        $count      = $Video->where('catid=8')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $RealTeach = $Video->where('catid=8')->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign("RealTeach", $RealTeach);
        $this->assign('page',$show);// 赋值分页输出
        $this->display('VideoSchool/RealTeach');
    }



    /*
   *获取基础教学
   *2016-08-25 sjy
   *
*/
    public function BaseTeach(){
        $Video = M("videos");
        // $BaseTeach=$Video
        //     ->where("catid=9")
        //     ->order('sort desc')
        //     ->select();

          $count      = $Video->where('catid=9')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $BaseTeach = $Video->where('catid=9')->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$show);// 赋值分页输出
        $this->assign("BaseTeach", $BaseTeach);
        $this->display('VideoSchool/BaseTeach');
    }

   public function detailvideo(){
        $userinfo = session('userinfo.nickname');
        $id=$_GET["nid"];//获取传过来的视频id

        if($id){
            $videoinfo=M("videos")->where(array('nid'=>$id))->find();//得到视频的信息
        }
        if($videoinfo){
          $where["keywords"]=array('like',array('%'.$videoinfo['keywords'].'%','%'.$videoinfo['keywords']),'OR');
        }

       $clicknum=$videoinfo["clickcount"];


       //获取用户的ip
      /* $ip=get_client_ip();
       var_dump($ip);
       exit();*/

      /* //$user_ip = get_client_ips();
     $ip=get_client_ip();
       var_dump($ip);
       exit();*/

       $nowclick=$clicknum+1;
$data["clickcount"]=$nowclick;

       $result=M("videos")
           ->where(array('nid'=>$id))
           ->data($data)
           ->save();



        //相关视频
        $correlation_video=M('videos')->where($where)->limit(10)->select();
         foreach ($correlation_video as $key => $value) {
          $correlation_video[$key]['createtime']=substr($value['createtime'],5,5);
          if($value===$videoinfo){
                unset($correlation_video[$key]);
              }        
        }
        //视频推荐
        $recommend_video=M('videos')->where(array('isrecommed'=>1))->order('createtime desc')->limit(10)->select();
        foreach ($recommend_video as $key => $value) {
          $recommend_video[$key]['createtime']=substr($value['createtime'],5,5);
           if($value===$videoinfo){
                unset($recommend_video[$key]);
              }
        }
        //视频排行榜
        $ranking_video=M('videos')->limit(5)->order('clickcount desc')->select();
        // p($ranking_video);die();
        $this->assign('userinfo',$userinfo);
        $this->assign('videoinfo',$videoinfo);
        $this->assign('recommend_video',$recommend_video);
        $this->assign('correlation_video',$correlation_video);
        $this->assign('ranking_video',$ranking_video);
        $this->display('VideoSchool/Detailvideo');
}




//获取用户id
    function get_client_ips() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        return $ip;
    }
}