<?php
namespace Home\Controller;
use Think\Controller;
/**
* 
*/
class AdvertController extends BaseController
{
	
	public function index()
	{
		$userid = session('userinfo.userid');
		// 根据用户id 查询他有没有申请主播认证
		$roomid = M('userinfos')->where(array('userid'=>$userid))->getField('roomid');
		// p($roomid);die;
		if ($roomid) {
			// 查询广告数量
			$advert = M('showarea')->where(array('roomid'=>$roomid))->select();
			// p($advert);die;
			$this->assign('list',$advert);
			$this->display('Advert/index');
		}else{
			$this->error('您没有权限添加广告');
		}
	}

	public function add()
	{
		$this->display('Advert/add');
	}
	// 添加广告
	public function add1()
	{
		$userid = session('userinfo.userid');
		// 根据用户id 查询他有没有申请主播认证
		$roomid = M('userinfos')->where(array('userid'=>$userid))->getField('roomid');
		if ($roomid) {
			// 查看是否有添加三个广告的权限
			$show = M('rooms')->where(array('roomid'=>$roomid))->getField('isshowarea');
			if ($show) {
				// 统计广告的数量
				$count = M('showarea')->where(array('roomid'=>$roomid))->count('said');
				if ($count >= 3) {
					$this->ajaxReturn('1');
				}else{
					$this->ajaxReturn('2');
				}
			}else{
				$count = M('showarea')->where(array('roomid'=>$roomid))->count('said');
				if ($count) {
					$this->ajaxReturn('1');
				}else{
					$this->ajaxReturn('2');
				}
			}
		}else{
			$this->error('您没有权限添加广告');
		}
	}
	// 添加广告操作
	public function adddo()
	{
		$userid = session('userinfo.userid');
		// 根据用户id 查询他有没有申请主播认证
		$data['roomid'] = M('userinfos')->where(array('userid'=>$userid))->getField('roomid');

		$data['saname'] = I('post.saname');
		$data['saurl'] = I('post.saurl');
		// 执行添加操作
		$id = M('showarea')->data($data)->add();
		// p($id);die;
		if ($id) {
			$this->ajaxReturn('1');
		}else{
			$this->ajaxReturn('2');
		}
	}
	// 重新提交审核
	public function edit()
	{
		$said = I('get.said');
		if($said){
			// 查询广告信息
			$list = M('showarea')->where(array('said'=>$said))->find();
			$this->assign('list',$list);
			$this->display('Advert/edit');
		}else{
			$this->error('找不到页面');
		}
	}
	public function editdo()
	{
		if (!empty($_POST)) {
			// p($_POST);die;
			$data['said']=I('post.said');
			$data['saname']=I('post.saname');
			$data['saurl']=I('post.saurl');
			$data['status'] = 3;
			$result = M('showarea')->data($data)->save();
			if ($result) {
				$this->ajaxReturn('1');
			}else{
				$this->ajaxReturn('2');
			}
		}
	}
}

