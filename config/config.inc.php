<?php
	$IVPConfig= array (
		'version'		=>	'1.1',
		//数据库配置信息.
		'database'		=>	array(
			'host'	 =>	'localhost',
			'dbname'	=>	'lichao',
			'dbuser'	=>	'root',
			'dbpassword'=>	'root',
			'charset'	=>	'UTF8'
		),
		'cookie'		=>	array(
			'prefix'	=>	'F_',
			'hashfix'	=>	'F@2012',
			'duration'	=>	24*3600
		),
		'basedatabase'	=>	array(
			'log'		=>	'_log',
			'record'	=>	'_record',
			'lock'		=>	'_lock'
		),
		'basedbconfig'	=>	array(
			'lockdomain'=>	"2012FRAMEWORK"
		),
		'addons'		=>	array(
			'json'		=>	false,
			'httpdown'	=>	false,
			'id3tagreader'	=>	false,
			'pagehelper'	=>	false,
			'twtapi'	=>	true,
		),
		'cacheswitch'	=>	false,
	);
?>