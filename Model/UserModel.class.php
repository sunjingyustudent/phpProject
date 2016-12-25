<?php
namespace App\Model;
use Think\Model;
class UserModel extends Model {
	/*字段映射*/
	protected $_map = array(
		'userid' =>'userid',
	);
	/* 自动完成:文档地址(http://document.thinkphp.cn/manual_3_2.html#auto_operate)
	* self::MODEL_INSERT或者1 	新增数据的时候处理（默认）
	* self::MODEL_UPDATE或者2 	更新数据的时候处理
	* self::MODEL_BOTH或者3 	所有情况都进行处理
	*/
	protected $_auto = array (
			array('createtime', 'time', 1, 'function'),
//			array('regip', 'get_client_ip', 1, 'function'),
			array('userpwd','md5',1,'function') ,
	);
	/* 自动验证:文档地址(http://document.thinkphp.cn/manual_3_2.html#auto_validate)
	* self::EXISTS_VALIDATE 或者0 存在字段就验证（默认）
	* self::MUST_VALIDATE 或者1 必须验证
	* self::VALUE_VALIDATE或者2 值不为空的时候验证
	*验证时间（可选）
	* self::MODEL_INSERT或者1新增数据时候验证
	*  self::MODEL_UPDATE或者2编辑数据时候验证
	*  self::MODEL_BOTH或者3全部情况下验证（默认）
	*/
	protected $_validate = array(
		array('verification_code','check_verification_code','短信验证码不正确！',1,'function',4),  // 只在登录时候验证
		array('phone','require','手机号码不能为空！'),
		array('phone','','手机已经存在！',0,'unique',1),
		array('password','require','密码不能为空！'),
		array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
    );
	public function check_verification_code(){
		if(I('verification_code')!=session('verification_code')){
			return false;
		}else{
			return true;
		}
	}
	// 是否自动检测数据表字段信息
	protected $autoCheckFields   =   true;
	// 是否批处理验证
	protected $patchValidate   =  false;
}