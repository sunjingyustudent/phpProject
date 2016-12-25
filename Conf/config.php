<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE'=>'mysql',//配置数据库类型
    'DB_HOST'=>'rdsm614a6mt50f295feoo.mysql.rds.aliyuncs.com',//服务器地址
    'DB_NAME'=>'haoyuezhibo',//数据库名称
    'DB_USER'=>'haoyuezhibo',//用户名
    'DB_PWD'=>'AbCd1234',//用户密码
    'DB_PORT'=>'3306',//数据库端口
    'DB_PREFIX'=>'wht_',//表前缀

    //日志记录类型，默认为文件类型
    'LOG_TYPE'    =>'File',
//    //开启日志
    'LOG_RECORD'  =>true,

    //SESSION设置
    'SESSION_AUTO_START' => true, // 是否自动开启Session
    'SESSION_OPTIONS'    => array(), // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_TYPE'       => '', // session hander类型 默认无需设置 除非扩展了session hander驱动
    'SESSION_PREFIX'     => '', // session 前缀

    'DEFAULT_MODULE'     => 'Home', // 默认模块
    'DEFAULT_CONTROLLER' =>  'Index', // 默认控制器名称
    'DEFAULT_ACTION'      =>  'index', // 默认操作名称
    'URL_HTML_SUFFIX'       => '',  // URL伪静态后缀设置
);
define('OSS', 'http://*.oss-cn-hangzhou.aliyuncs.com/'); //把*替换成对应的Bucket 由于经常用到该链接，所以定义成常量
return array(
    'UPLOAD_SITEIMG_OSS' => array (
                'maxSize' => 5 * 1024 * 1024,//文件大小
                'rootPath' => './',
                'saveName' => array ('uniqid', ''),
                'savePath' => 'aliyun/',    //保存路径
                'driver' => 'Aliyun',
                'driverConfig' => array (
                        'AccessKeyId' => 'qz2BRTXV7piA6zcK',    //AccessKeyId
                        'AccessKeySecret' => 'iwEFsvsYphgl0xJKOYoxbKPRvm4EAN',//AccessKeySecret
                        'domain' => OSS,        //bucket所在域名
                        'Bucket' => 'jdcjapp',         //Bucket名字
                        'Endpoint' => 'http://oss-cn-hangzhou.aliyuncs.com',
                        //如果是杭州的服务器
                        //Endpoint设置成
                        //'Endpoint' => 'http://oss-cn-hangzhou.aliyuncs.com',
            ),
        ),
    );