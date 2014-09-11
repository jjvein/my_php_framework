<?php 
 

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

	function get_Userinfo($sno){
		$result1 = array();
		$twtapi=user_getTWTAPI();
		$result=$twtapi->query("student.info",array(
			'keyword'	=>	$sno 
		));
		//print_R($result);
		$result1['uid'] = $result->db->uid;
		$result1['twtname'] = $result->db->twtname;
		$result1['realname'] = $result ->db ->realname;
		$result1['studentid'] = $result ->db -> studentid;
		$result1['collegeid'] = $result ->db -> collegeid;
		$result1['grade'] = $result ->db -> grade;
		$result1['college'] = $result ->db -> college;
		$result1['partybranchid'] = $result ->db -> partybranchid;	
		$result1['partybranchname'] = $result ->db -> partybranchname;
		$result1['major'] = $result ->db -> major;
		//$result1['class'] = $result->db -> `class`;

		return $result1;
	}
//获取老师的个人信息.... 
	function get_Teacherinfo($tno){
		$result1 = array();
		$twtapi=user_getTWTAPI();
		$result=$twtapi->query("teachers.info",array(
			'keyword'	=>	$tno 
		));
		//print_R($result);
		$result1['uid'] = $result->teachers[0]->uid;
		$result1['twtname'] = $result->teachers[0]->twtname;
		$result1['realname'] = $result ->teachers[0] ->realname;
		$result1['teacherid'] = $result ->teachers[0] -> teacherid;
		$result1['collegeid'] = $result ->teachers[0] -> collegeid;		 
		$result1['college'] = $result ->teachers[0] -> college;	 	 
		return $result1;
	}


	function getInfo_from_twtname($twtname){
		$result1 = array();
		$twtapi=user_getTWTAPI();
		$result=$twtapi->query("user.info",array(
			'keyword'	=>	$twtname 
		));
		//print_R($result);
		$result1['uid'] = $result->uid;
		$result1['twtname'] = $result->twtname;
		$result1['realname'] = $result ->realname;
		$result1['usernumb'] = $result ->usernumb;
		$result1['isteacher'] = $result ->isteacher;  	 
		return $result1;		
	
	}
function get_Partybranchmember($partybranchid){
		$twtapi=user_getTWTAPI();
		$result=$twtapi->query("party.getmembers",array(
	
			'partybranchid'	=>	$partybranchid		
			));

		return $result;
}
function get_Partybranchinfo($partybranchid){
	$result1 = array();
		$twtapi=user_getTWTAPI();
		$result=$twtapi->query("party.info",array(
			'keyword'	=>	$partybranchid		
			));
		$result1['partybranchid'] = $result -> partybranch[0] ->partybranchid;
		$result1['partybranchname'] = $result -> partybranch[0] -> partybranchname;
		$result1['collegeid'] = $result -> partybranch[0] -> collegeid;
		$result1['shuji'] = $result -> partybranch[0] ->commander->p1_shuji;
		$result1['zuzhi'] = $result -> partybranch[0] ->commander->p1_zuzhi;
		$result1['xuanchuan'] = $result -> partybranch[0] ->commander->p1_xuanchuan;
		return $result1;
}

	function get_MemberbyClass($classid){
		$result1 = array();
		$twtapi=user_getTWTAPI();
		$result=$twtapi->query("class.getstudents",array(
			'classid'	=>	$classid		
			));
		$result1 = $result->students;
		//print_R($result1);
		return $result1;		
	}


	function get_TS_info($sno){
		$len = strlen($sno);
		//print_R($len);
		if($len==6)
			$info = get_Teacherinfo($sno);
		elseif($len==10)
			$info = get_Userinfo($sno);
		else return false;
		// print_R($info);
		if($info)
			return $info;

		return false;
	}
 
