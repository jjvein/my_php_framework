<?PHP
	
	load_model('common.func');//我要引用common.func里面的查询函数....
	load_model('user.func');//我要引用common.func里面的查询函数....
	@session_start();
 	function manager_islogin(){
		//下面是判断用户是否登陆...
		if(isset($_SESSION['realname'])&&isset($_SESSION['twtname'])&&isset($_SESSION['authority'])){
			return true;
		} 				
		return false;
	}

	function manager_sustain_session()
	{
		$_SESSION['realname']=$_SESSION['realname'];
		$_SESSION['twtname']=$_SESSION['twtname'];
		$_SESSION['usernumb']=$_SESSION['usernumb'];
		$_SESSION['is_gangbu']=$_SESSION['is_gangbu'];
		$_SESSION['is_teacher']=$_SESSION['is_teacher'];
		$_SESSION['partybranch_id']=$_SESSION['partybranch_id'];
		$_SESSION['authority']=$_SESSION['authority'];
	}

	function manager_clearCookie()
	{
		@session_destroy();//这里就首先将所有的session删除了..
		setcookie("twtname",0,time()-3600,'/','twt.edu.cn');
		setcookie("twtname",0,time()-3600,'/');
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
		setcookie("authority",0,time()-3600,'/');
		setcookie("authority",0,time()-3600,'/','twt.edu.cn');
	}
//该函数是将session内容继续保持下...
	function manager_touch($twtname,$realname,$usernumb,$is_gangbu,$is_teacher,$partybranch_id,$authority)
	{
		setcookie("twtname",$twtname,time()+3600*24*7,'/',"twt.edu.cn");
		setcookie("realname",$realname,time()+3600*24,'/',"twt.edu.cn");
		setcookie("usernumb",$usernumb,time()+3600*24,'/',"twt.edu.cn");
		setcookie("is_gangbu",$is_gangbu,time()+3600*24,'/',"twt.edu.cn");
		setcookie("is_teacher",$is_teacher,time()+3600*24,'/',"twt.edu.cn");
		setcookie("partybranch_id",$partybranch_id,time()+3600*24,'/',"twt.edu.cn");
		setcookie("authority",$authority,time()+3600*24,'/',"twt.edu.cn");

		$_SESSION['twtname']=$twtname;
		$_SESSION['realname']=$realname;	
		$_SESSION['usernumb']=$usernumb;
		$_SESSION['is_gangbu']=$is_gangbu;
		$_SESSION['is_teacher']=$is_teacher;
		$_SESSION['partybranch_id']=$partybranch_id;
		$_SESSION['authority']=$authority;
 
	}
	function manager_login($username,$password,$ishashed=0)
	{

		$twtapi=user_getTWTAPI();
		$result=$twtapi->query('twt.login',array(
			'username'	=>	$username,
			'password'	=>	$password,
			'ishashed'	=>	$ishashed
		));
		//print_R($result);
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
				//print_R($partybranch_id);
				//print_R('<br>');
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

			}//end of if..result..

		if($result)
		{
			$where = " where manager_name = '".$result->twtname."' and manager_status = 0 and manager_isdeleted = 0 ";
				//print_R($where);
				$manager = getByWhere('manager',$where);
			//print_R($manager);
			//这里表示通过在数据库中查找发现确实该用户是管理员..
			if($manager){
				$authority = $manager['manager_type'];//权限.
				manager_touch($result->twtname,$result->realname,$result->usernumb,$is_gangbu,$is_teacher,$partybranch_id,$authority);
			}//end of if....
		}//end of if result.....
		//print_R($manager);
		return $manager;
	}


function manager_need_login()
	{	
		global $_TPL,$tpl;
		if(!manager_islogin()){
		//就是目前没有用户session状态,,直接进入的情况....
			//include $tpl->prepare('login');
			header('Location:./?page=manager&do=login');
			//include $tpl->prepare('manager_login');
			exit;
		}else {
			//就是有session状态下...
			//print_r($_SESSION['twt_account']);
			$where = " where manager_name = '".$_SESSION['twtname']."' and  manager_status = 0 and manager_isdeleted = 0 ";
			$man = getByWhere('manager',$where);
			//print_R($man);
			if($man){
				//表示通过检测..该用户确实也是一名管理员......
				//print_R('aaa');
				$rs = getCollegeid_s();
				//print_R($rs);
				if(!$rs['collegeid']&&$man['manager_type']==120){
					manager_clearCookie();
					header('Location:./?page=manager&do=login');
					exit;
				}
				manager_sustain_session();				
			}else{
				//没有通过...
				manager_clearCookie();
				header('Location:./?page=manager&do=login');
				exit;
	 
				
			}	
		}
	}

	function manager_logout(){
		manager_clearCookie();
		return $result;
	}
?>