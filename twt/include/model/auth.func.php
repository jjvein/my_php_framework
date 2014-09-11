<?php
	@session_start();
	// print_r($_SESSION);

	function auth_add($ownertype,
		$ownerid,$domain,$did,$auth,$level,$iscancel)
	{
		$arr=array(
				'ownertype'	=>	base_protect($ownertype),
				'ownerid'	=>	(int)$ownerid,
				'domain'	=>	base_protect($domain),
				'did'		=>	(int)$did,
				'auth'		=>	base_protect($auth),
				'level'		=>	(int)$level,
				'iscancel'	=>	(int)$iscancel
			);
		$arr['grantat']=date('Y-m-d H:i:s');
		$arr['grantby']=$_SESSION['twt_uid'];
		$flag=inserttable('authmap',$arr);
		return $flag;
	}

	function auth_update($set,$where)
	{
		$set=base_protect($set);
		$where=base_protect($where);
		$flag=updatetable('authmap',$set,$where);
		return $flag;
	}

	function auth_delete($where)
	{
		global $db;
		$where=base_protect($where);
		$query='DELETE FROM '.table('authmap').' WHERE ';
		$c=0;
		foreach($where as $i=>$v)
		{
			$c++;
			if($c!=1)
				$query.=' AND';
			$query.=' `'.$i.'`="'.$v.'"';
		}
		$db->sql($query);
		return $db->getAffectedRow();
	}

	function auth_query($auth=false,$domain=false,$did=false,$page=0,$pagelimit=20)
	{
		if($domain&&$did===false)
			return false;
		global $db;	
		if($auth)
			$auth=base_protect($auth);
		if($domain)
			$domain=base_protect($domain);
		if($did!==false)
			$did=(int)$did;
		$page=(int)$page;
		$pagelimit=(int)$pagelimit;
		$query='SELECT * FROM '.table('authmap');
		$where=' WHERE 1=1';
		if($auth)
			$where.=' AND `auth`="'.$auth.'"';
		if($domain)
			$where.=" AND `domain`='$domain' AND `did`=$did";
		$where.=' ORDER BY `iscancel` ASC,`grantat` DESC';
		$query.=$where.' LIMIT '.($page*$pagelimit).','.$pagelimit;
		$result=$db->sql($query);
		$arr=array();
		while($row=$db->getRow($result))
			$arr[]=$row;
		return $arr;
	}

	function auth_checkTF($domain,$did,$auth,$level=1)
	{
		return auth_check($domain,$did,$auth,$level)>0;
	}
	
	function auth_check($domain,$did,$auth,$level=1)
	{
		if(!user_isLogin())
			return false;

		$auth=base_protect($auth);
		$domain=base_protect($domain);
		$did=(int)$did;
		$level=(int)$level;

		if(auth_checkSession($domain,$did,$auth,$level))
		{
			return true;
		}
		load_model('user.func');
		$user=user_getById($_SESSION['twt_uid']);
		if(!$user)
			return false;
		$query='SELECT * FROM '.table('authmap')
			.' WHERE '
			.'((`ownertype`="group" AND `ownerid`="'.$user['gid'].'")'
			.' OR '
			.'(`ownertype`="user" AND `ownerid`="'.$user['uid'].'"))'
			.' AND `domain`="'.$domain.'"'
			.' AND `did`="'.$did.'"'
			.' AND `auth`="'.$auth.'"'
			//.' AND `level`>='.$level
			//.' AND `iscancel`=0'
			.' ORDER BY `iscancel` DESC LIMIT 1';
		// echo $query;
		global $db;
		$result=$db->sql($query);
		$row=$db->getRow($result);
		if(!$row)
			return -1;
		if($row['iscancel']!='0')
			return -2;
		if($row['level']<$level)
			return -3;
		if($row['bindtype']=='group')
			return 2;
		auth_setSession($row['domain'],$row['did'],$row['auth'],$row['level']);
		return 1;
	}

	function auth_need($domain,$did,$auth,$level=1)
	{
		$flag=auth_check($domain,$did,$auth,$level);
		if($flag>0)
			return true;
		global $_TPL;
		$_TPL['hidemenu']=true;
		switch($flag)
		{
			case -1:
				message('并不拥有指定权限'.$auth,'警告');
				break;
			case -2:
				message('并不拥有指定权限'.$auth.'，该权限已经被取消','警告');
				break;
			case -3:
				message('并不拥有指定权限'.$auth.'，授权等级不足','警告');
				break;
			case 0:
				message('授权操作被拒绝','警告');
				break;
		}
	}

	function auth_genSessionKey($domain,$did,$auth,$level)
	{
		$prefix='twtdisk_auth@';
		return $prefix.$domain.'.'.$did.'.'.$auth;
	}

	function auth_checkSession($domain,$did,$auth,$level=1)
	{
		global $_SESSION;
		// echo "<br/>GET".auth_genSessionKey($domain,$did,$auth,$level);
		$key=auth_genSessionKey($domain,$did,$auth,$level);
		if(!isset($_SESSION[$key]))
		{
			// echo 'nosession';
			return false;
		}
		if($_SESSION[$key]>=$level)
			return true;
		return false;
	}

	function auth_setSession($domain,$did,$auth,$level=1)
	{
		global $_SESSION;
		// echo "<br/>SET".auth_genSessionKey($domain,$did,$auth,$level);
		$_SESSION[auth_genSessionKey($domain,$did,$auth,$level)]=$level;
		// print_r($_SESSION);
	}