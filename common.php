<?php

	date_default_timezone_set("Asia/Shanghai"); //设置时区

	//设置是否是调试模式.
	!defined('ISDEBUG') && define('ISDEBUG', 1);  	
	//错误显示机制.
 	if ( defined("ISDEBUG") && ISDEBUG ) {
		ini_set ( 'display_errors', 1 );  //设置报错级别.
	 	error_reporting(E_ALL & ~E_NOTICE); #//不显示Notice 提示, 其他的全部显示..
 	} else {
 		ini_set("display_errors", 0);
 	}	

	//项目的绝对路径,相对于目录而言. 
 	!defined('ROOT_PATH') && define('ROOT_PATH', __DIR__);
 	!defined('INCLUDE_PATH') && define('INCLUDE_PATH', ROOT_PATH . '/include');
 	//项目在服务器根目录的绝对地址.
 	!defined('ROOT') && define('ROOT', '');
 	//项目公共文件夹地址.
 	if (!defined('PUBLIC_PATH')) {
 		define ("PUBLIC_PATH", ROOT . '/public');		
 	} 


 	require ( ROOT_PATH . '/include/base.inc.php' ); //加载公共函数.
 	require ( dirname(__FILE__) . '/libs/Autoloader.class.php' ); //自动加载类的载入..
	new Twt\Libs\Autoloader (); //注册自动加载类.

	$dispatcher = new Twt\Libs\Dispatcher ();
	$dispatcher->dispatch (); //进行路由转发.
	

	//这里我在考虑是不是应该将日志加入到这个地方来.
	//$tpl=new TemplateHelper(dirname(__FILE__).'/template',dirname(__FILE__).'/cache');

/*	if(defined('ISDEBUG')&&ISDEBUG)
	{
		//测试模式不自动加载模板缓存
		$tpl->nocache(true);
	}*/
	$_TPL=array(
		'title'	=>	'TWT site framework',
		'css'	=>	array('common'),
		'js'	=>	array('jquery','common')
	);


?>