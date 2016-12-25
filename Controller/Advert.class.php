<?php
namespace Home\Controller;
use Think\Controller;
/**
* 
*/
class Advert extends Controller
{
	
	public function index()
	{
		$this->display('Advert/index');
	}
}