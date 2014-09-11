<?php

	//会调用user.session.func.php中的user_islogin函数对页面的登录状况进行检查
	function need_login()
	{
		if(!user_islogin())
		{
			global $_TPL;
			$_TPL['hidemenu']=true;
			message('您所访问的页面需要登录','登陆提醒','http://www.twt.edu.cn/account/');
		}
	}

	function need_AdminLogin(){
		
	
	}
	//message函数会调用message模板输出提示信息，并阻断页面的执行(exit)
	function message($title='提示信息',$message,$href=false)
	{
		global $_TPL,$tpl,$inajax;
		$auth = $_SESSION['authority'];
		// if(!DEFINED('IN_MNG'))
		// 	$_TPL['css']=array('bootstrap','common','downloadpage','icons');
		if(!$href)
			$href='javascript:history.go(-1)';
		include $tpl->prepare('message');
		exit;
	}

	//message函数会调用message1模板输出提示信息，并阻断页面的执行(exit)
	function message1($title='提示信息',$message,$href=false)
	{
		global $_TPL,$tpl,$inajax;
		// if(!DEFINED('IN_MNG'))
		// 	$_TPL['css']=array('bootstrap','common','downloadpage','icons');
		if(!$href)
			$href='javascript:history.go(-1)';
		include $tpl->prepare('message1');
		exit;
	}
	//table函数会依据config/config.main.php文件中的fingerprint变量
	//对传入的表名称进行加前缀操作
	function table($name)
	{
		//调用 table('admin') -> 2012twt_admin
		global $Config;
		
		return $Config['fingerprint'].$name;
	}

	//补全地址加载模块。
	function load_model($modelname)
	{
		//想载入sample.func.php内的函数，调用：
		//load_model('sample.func');
		@include_once(dirname(__FILE__)."/".$modelname.".php");
	}
	
	function prettysize($kb)
	{
		if($kb<768)
			return number_format($kb,2).'KB';
		else if($kb<768*1024)
			return number_format($kb/1024.0,2).'MB';
		else 
			return number_format($kb/1024.0/1024.0,2).'GB';

	}
	//添加数据
	function inserttable($tablename, $insertsqlarr, $returnid=false, $replace = false) {
		global $db;
	
		$insertkeysql = $insertvaluesql = $comma = '';
		foreach ($insertsqlarr as $insert_key => $insert_value) {
			$insertkeysql .= $comma.'`'.$insert_key.'`';
			$insertvaluesql .= $comma.'\''.$insert_value.'\'';
			$comma = ', ';
		}
		$method = $replace?'REPLACE':'INSERT';
		$db->sql($method.' INTO '.table($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')');
			$msg = $method.' INTO '.table($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')';
			//print_r($msg);
		if(!$returnid && !$replace) {
			//echo 'ok';
			//echo mysql_error();
			return $db->getLastInsertId();
		}
		return $returnid;	
	}
	
	//更新数据
	function updatetable($tablename, $setsqlarr, $wheresqlarr,$autoprotect=true) {
		global $db;
	
		$setsql = $comma = '';
		foreach ($setsqlarr as $set_key => $set_value) {//fix
			$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
			$comma = ', ';
		}
		$where = $comma = '';
		if(empty($wheresqlarr)) {
			$where = '1';
		} elseif(is_array($wheresqlarr)) {
			foreach ($wheresqlarr as $key => $value) {
				if($autoprotect)
					$value=base_protect($value);
				$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
				$comma = ' AND ';
			}
		} else {
			$where = $wheresqlarr;
		}
		$query = 'UPDATE '.table($tablename).' SET '.$setsql.' WHERE '.$where;
		//print_R("<br/>");
		 //print_R($query);
		// echo 'UPDATE '.table($tablename).' SET '.$setsql.' WHERE '.$where;
		$flag=$db->sql('UPDATE '.table($tablename).' SET '.$setsql.' WHERE '.$where);
		// echo 'flag='.$flag;
		return $flag;
	}