<?PHP
	load_model('common.func');//我要引用common.func里面的查询函数....
	load_model('user.func');//这里需要用到里面的函数进行查询
	@session_start();

//下面是判断用户是否登陆...
	function user_islogin(){
		if(isset($_SESSION['realname'])&&isset($_SESSION['twtname'])&&isset($_SESSION['usernumb'])&&isset($_SESSION['is_teacher'])&&isset($_SESSION['partybranch_id'])){
			return true;
		} 				
		return false;
	}

	function user_sustain_session()
	{	
		$_SESSION['twtname']=$_SESSION['twtname'];
		$_SESSION['realname']=$_SESSION['realname'];
		$_SESSION['usernumb']=$_SESSION['usernumb'];//这个表示的是学号或者是工资号.....
		$_SESSION['is_gangbu']=$_SESSION['is_gangbu'];
		$_SESSION['is_teacher']=$_SESSION['is_teacher'];
		$_SESSION['partybranch_id']=$_SESSION['partybranch_id'];
	}

	function user_clearCookie()
	{
		@session_destroy();
		setcookie("twtname",0,time()-3600,'/');
		setcookie("twtname",0,time()-3600,'/','twt.edu.cn');
		setcookie("realname",0,time()-3600,'/');
		setcookie("realname",0,time()-3600,'/','twt.edu.cn');			
		setcookie("usernumb",0,time()-3600,'/');		
		setcookie("usernumb",0,time()-3600,'/','twt.edu.cn');
		setcookie("is_gangbu",0,time()-3600,'/');
		setcookie("is_gangbu",0,time()-3600,'/','twt.edu.cn');
		setcookie("is_teacher",0,time()-3600,'/');
		setcookie("is_teacher",0,time()-3600,'/','twt.edu.cn');
		setcookie("partybranch_id",0,time()-3600,'/');
		setcookie("partybranch_id",0,time()-3600,'/','twt.edu.cn');
	}

//该函数是将session内容继续保持下...
	function user_touch($twtname,$realname,$usernumb,$is_gangbu,$is_teacher,$partybranch_id)
	{
			setcookie("twtname",$twtname,time()+3600*24*7,'/',"twt.edu.cn");
			setcookie("realname",$realname,time()+3600*24*7,'/',"twt.edu.cn");
			setcookie("usernumb",$usernumb,time()+3600*24,'/',"twt.edu.cn");
			setcookie("is_gangbu",$is_gangbu,time()+3600*24,'/',"twt.edu.cn"); 	
			setcookie("is_teacher",$is_teacher,time()+3600*24,'/',"twt.edu.cn"); 	
			setcookie("partybranch_id",$partybranch_id,time()+3600*24,'/',"twt.edu.cn");

		$_SESSION['realname']=$realname;
		$_SESSION['twtname']=$twtname;
		$_SESSION['usernumb']=$usernumb;
		$_SESSION['is_gangbu']=$is_gangbu;//是不是干部
		$_SESSION['is_teacher']=$is_teacher;//是不是老师
		$_SESSION['partybranch_id']=$partybranch_id;//支部id...
		print_R($_SESSION);
		

	}
	function user_login($username,$password,$ishashed=0)
	{

		$twtapi=user_getTWTAPI();
		//print_R($twtapi);
		$result=$twtapi->query('twt.login',array(
			'username'	=>	$username,
			'password'	=>	$password,
			'ishashed'	=>	$ishashed
		));
		print_R($result);

		if($result)
		{
			//这里表示的是该用户确实存在....下面我们要判断该用户是老师还是学生...
			$usernumb = $result->usernumb;
		
				//这里表示的是老师...但是有的老师确实是支部的干部,,这里还是得确认...
				$query = " where partybranch_secretary ='".$usernumb."' or partybranch_organizer ='".$usernumb."' or partybranch_propagator ='".$usernumb."'";
			$query_r = getByWhere('partybranch',$query);
			if($query_r){
				$is_gangbu = 1;
				$partybranch_id = $query_r['partybranch_id'];
				print_R($partybranch_id);
				print_R('<br>');
				}
			else {
				$is_gangbu = 0;
				//这里要注意了:如果这里的人是老师的话,他不是支部委员,也不在info表中,那么这个人将没有
				//支部信息....也就是没有支部id....和那些没有支部的人登陆是一样的,,无法看到支部成员的//的内容.但是如果他有个人发展流程图,和上传资料还是可以看到的.....
				$query1 = " where sno ='".$usernumb."'";
				$query1_r = getByWhere('student_info',$query1);
				if($query1_r['partybranch_id']){
				//表示有内容...
				$partybranch_id = $query1_r['partybranch_id'];
					}
				else {
				$partybranch_id = 0;
					}//end of else..
				}//end of else...
//下面判断的非常多...当老师和同学进入的时候,看到的是不同的页面.....
			if(strlen($usernumb)==6){
				$is_teacher = 1;//是干部
			}elseif(strlen($usernumb)==10){
				$is_teacher = 0;//不是干部...
			}

//下面判断该用户是属于哪个支部的.....

			user_touch($result->twtname,$result->realname,$result->usernumb,$is_gangbu,$is_teacher,$partybranch_id);


		}//end of if result.....
		//print_R($manager);
		return $result;
	}


function user_need_login()
	{	
		global $_TPL,$tpl;
		if(!user_islogin()){
		//就是目前没有用户session状态,,直接进入的情况....
			//include $tpl->prepare('login');
			header('Location:./?page=user&do=login');
			echo "<script>alert('请先登录!');location.href='./?page=user&do=login'</script>";
			//include $tpl->prepare('manager_login');
			exit;
		}else {
			//就是有session状态下...
				user_sustain_session();
		}//end of else;..
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
?>