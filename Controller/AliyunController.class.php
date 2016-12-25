<?php
/**
 * aliyun
 * @author 冯晓闯 (lanxinxichen@126.com)
 * @date    2014-06-13 14:26:23
 * @version 1.0
 */
namespace Home\Controller;
use Think\Controller;

class AliyunController extends BaseController {

  /**
   * 文件上传demo
   */
  public function index(){
    $this->display();
  }

  /**
   * 标准测试上传表单
   */
  public function getfile(){
    // echo "11";
    // p($_FILES);
    // die;
    $info = $this->upload();
    // p($info);die;
    if ($info) {
      $this->success('success添加文件信息成功');
    }else{
      $this->error('error添加文件信息失败');
    }
  }

  /**
   * 标准js测试上传
   */
  public function getjsfile(){
    $info = $this->upload();
    if ($info) {
      echo json_encode($info);
    }else{
      echo json_encode(array('error'=>'文件添加失败'));
    }
  }

  /**
   * 提供调用的公共上传
   * @return array 文件信息
   */
  public function upload(){
    $setting=C('UPLOAD_SITEIMG_OSS');
    $Upload = new \Think\Upload($setting);
    $info = $Upload->upload($_FILES);
    if (!$info) {
      return false;
    }else{
      return $info['file'];
    }
  }


// $info 具体信息
//   ["file"] => array(9) {
//     ["name"] => string(23) "t0154de56066574d856.jpg"
//     ["type"] => string(10) "image/jpeg"
//     ["size"] => int(37218)
//     ["key"] => string(4) "file"
//     ["ext"] => string(3) "jpg"
//     ["md5"] => string(32) "2ea392a4519e3ea7792b4628c5ba50e7"
//     ["sha1"] => string(40) "bae95b1faada8be51caaa3fcf52c8b5632faadb5"
//     ["savename"] => string(17) "53c4a7b96c193.jpg"
//     ["savepath"] => string(18) "aliyun/2014-07-15/"
//   }


}