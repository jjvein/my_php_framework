<?php
	//TWT Framework STD User Session Useful Functions
	//天外天账号登录帮助函数库
	//在本框架的common.php文件应有以下代码对当前用户的天外天账号登录状态进行检查
	/*
		load_model('user.session.func');
		user_session_init();

		通过此操作。可以自动地从cookie获取天外天账号的登录信息
		并填充以下session值用以之后的代码调用

		$_SESSION['twt_uid']
		$_SESSION['twt_account']
		$_SESSION['twt_authkey']
		$_SESSION['realname']

		有以下方法用以进行登录登出操作

		function user_login($username,$password,$ishashed=0)
		function user_logout()

		有以下方法对用户登录状态进行判断

		function user_islogin()

		有以下方法用以配置天外天API参数
		
		function user_getTWTAPI()

		*天外天API帮助类 TWTAPIHelper应于config/config.inc.php的
		 addons中自动载入
		 若提示TWAPIHelper类未找到，需要在配置的addons域中加入
		 'addons'	=>	true
		 或者于user_getTWTAPI方法的第一行解除以下代码的注释
		 base_load_addons('twtapi')

		请于站点管理员处索取自己项目的TWTAPI分配域与api_key并修改
		user_getTWTAPI方法的对应参数，或者使用twtpublic的api域
		该域只有基本的登录登出账号许可
	*/
	@session_start();
	function user_getTWTAPI()
	{
		//base_load_addons('twtapi')
		$TWTAPI=new TWTAPIHelper(array(
			'url'		=>	'http://www.twt.edu.cn/api/',//默认api接入点
			'domain'	=>	'twtparty',//在此填入所分配的api域名称
			'api_key'	=>	'0b51d6d4309648ff3d77ca34249d51'//在此填入所分配的api密匙
		));
		return $TWTAPI;
	}

	function user_session_init()
	{
		if(isset($_GET['twt_account'])&&isset($_GET['twt_authkey']))
		{
			$_COOKIE['twt_account']=$_GET['twt_account'];
			$_COOKIE['twt_authkey']=$_GET['twt_authkey'];
		}

		if(isset($_POST['twt_account'])&&isset($_POST['twt_authkey']))
		{
			$_COOKIE['twt_account']=$_POST['twt_account'];
			$_COOKIE['twt_authkey']=$_POST['twt_authkey'];
		}

		if(!(base_nulltest($_COOKIE['twt_account'])!=""&&base_nulltest($_COOKIE['twt_authkey'])!=""))
		{
			user_clearCookie();
			return;
		}
		if(user_islogin())
		{
			user_touch_session();
			// echo "islogined";
			return;
		}
		
		if(Database::isConnected())
		{
			global $db;
			$db->close();
		}
		$twtapi=user_getTWTAPI();
		$result=$twtapi->query("twt.islogin",array(
			'username'	=>	$_COOKIE['twt_account'],
			'auth_key'	=>	$_COOKIE['twt_authkey']
		));

		if(!$result)
		{
			user_clearCookie();
			return;
		}
		
		user_touch($result->twtname,$result->auth_key,$result->realname,$result->uid);
		if($db)
		{
			$db->conn();
			load_model('user.func');
			user_APISync($result);
		}

	}

	function user_islogin(){

		if(base_nulltest($_COOKIE['twt_account'])!=""
			&&base_nulltest($_COOKIE['twt_authkey'])!=""
			&&$_COOKIE['twt_account']==$_SESSION['twt_account']
			&&$_COOKIE['twt_authkey']==$_SESSION['twt_authkey']
			&&base_nulltest($_SESSION['twt_uid'])>0
			&&isset($_SESSION['realname']))
			return true;
		// global $repo;
		// if(!is_array($repo))
		// 	$repo=array();
		// $repo[]=base_nulltest($_COOKIE['twt_account']);
		// $repo[]=base_nulltest($_COOKIE['twt_authkey']);
		// $repo[]=$_SESSION['twt_account'];
		// $repo[]=$_COOKIE['twt_account']==$_SESSION['twt_account'];
		// $repo[]=$_COOKIE['twt_authkey']==$_SESSION['twt_authkey'];
		// $repo[]=base_nulltest($_SESSION['twt_uid'])>0;
		// $repo[]=isset($_SESSION['realname']);
		// print_r($repo);
		return false;
	}

	function user_now(){
		if(user_islogin())
		{
			return $_SESSION['realname'];
		}
		return "GUEST(".getIp().")";
	}

	function user_logout(){
		$twtapi=user_getTWTAPI();
		$result=$twtapi->query("twt.logout",array(
			'username'	=>	$_COOKIE['twt_account'],
			'auth_key'	=>	$_COOKIE['twt_authkey']
		));
		user_clearCookie();
		return $result;
	}

	function user_login($username,$password,$ishashed=0)
	{
		
		if(Database::isConnected())
		{
			global $db;
			$db->close();
		}

		$twtapi=user_getTWTAPI();
		$result=$twtapi->query('twt.login',array(
			'username'	=>	$username,
			'password'	=>	$password,
			'ishashed'	=>	$ishashed
		));
		if($result)
		{
			user_touch($result->twtname,$result->auth_key,$result->realname,$result->uid);
		
			if($db)
			{
				global $db;
				$db->conn();
				load_model('user.func');
				user_APISync($result);
			}

		}
		return $result;
	}

	function user_clearCookie()
	{
		@session_destroy();
		setcookie("twt_authkey",0,time()-3600,'/','twt.edu.cn');
		setcookie("twt_authkey",0,time()-3600,'/');
		setcookie("twt_account",0,time()-3600,'/');
		setcookie("twt_account",0,time()-3600,'/','twt.edu.cn');
		// echo "clear cookie";
		unset($_COOKIE['twt_authkey']);
	}

	function user_touch($twtname,$auth_key,$realname,$uid)
	{
		// setcookie("twt_account",$twtname,time()+3600*24*7,'/');
		// setcookie("twt_authkey",$auth_key,time()+3600*24,'/');
		setcookie("twt_account",$twtname,time()+3600*24*7,'/',"twt.edu.cn");
		setcookie("twt_authkey",$auth_key,time()+3600*24,'/',"twt.edu.cn");
		$_SESSION['realname']=$realname;
		$_SESSION['twt_account']=$twtname;
		$_SESSION['twt_authkey']=$auth_key;
		$_SESSION['twt_uid']=$uid;
	}

	function user_touch_session()
	{
		$_SESSION['realname']=$_SESSION['realname'];
		$_SESSION['twt_account']=$_SESSION['twt_account'];
		$_SESSION['twt_authkey']=$_SESSION['twt_authkey'];
		$_SESSION['twt_uid']=$_SESSION['twt_uid'];
	}