<?php
/*
 * 财经头条控制器
 *
 */
namespace Home\Controller;
use Think\Controller;
class DataController extends BaseController {
    function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function data(){
        $this->display();
       }
   public function calendar(){
       $this->display();

   }
    public function market(){
        $this->display();
    }
}