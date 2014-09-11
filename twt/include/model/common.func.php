<?PHP
//这里需要说明一下这个文件的作用:
//主要是用来显示一些和分数据库没有关系的函数的作用.


//下面三个函数主要 用来获得学生的学院，入学年份，学生类型。
//这里需要注意的是:年级里面会有个0,所以要把这个0去掉才行的...
	function get_student_schoolyear(){
		global $db;
		$query = "select distinct grade as student_schoolyear from b_class where grade <>0 and grade >2005 order by student_schoolyear desc";
		$result = $db->sql($query);
		$arr = array();
        while ($row = $db->getRow($result)) {
        	$arr[] = $row;
        }
        return $arr;
	}
	//下面的函数是在支部列表中进行添加的...下拉菜单,,显示年级信息
 
	function get_student_schoolyear1(){
		global $db;
		$nowyear = date('Y');
		$query = "select distinct grade as student_schoolyear from b_class where  grade =".$nowyear." ";
		$result = $db->sql($query);
		$arr = array();
        while ($row = $db->getRow($result)) {
        	$arr[] = $row;
        }
        return $arr;
	}
	//下面的函数是在支部列表中进行添加的...下拉菜单,,显示年级信息
 

	function get_student_academy(){
		global $db;
		$query = "select `id` id,`shortname` student_academy from b_college where `id` <18 or `id` in('22','23','24')";
		$result = $db->sql($query);
			$arr = array();
        while ($row = $db->getRow($result)){
        	$arr[] = $row;
        }
        return $arr;
}

	function get_student_type(){
		global $db;
		$query = "select distinct student_type from twt_student";
		$result = $db->sql($query);
			$arr = array();
        while ($row = $db->getRow($result)) {
        	$arr[] = $row;
        }
        return $arr;
}

	function get_student_major(){
		global $db;
		$query = "select distinct student_major from twt_student";
		$result = $db->sql($query);
			$arr = array();
        while ($row = $db->getRow($result)) {
        	$arr[] = $row;
        }
        return $arr;
	
	}
//********************上面的三个位查找基本信息.....所有的页面里都一样.......
function insert($table,$insertarr){
		$insertarr=base_protect($insertarr);
		return inserttable($table, $insertarr);
	}

	function update($table,$setarr,$column, $index){
		$wherearr = array($column => $index);
		$setarr=base_protect($setarr);
		return updatetable($table, $setarr, $wherearr)?true:false;
	}

	function update_array($table,$setarr,$where){
		$wherearr = base_protect($where);
		$setarr=base_protect($setarr);
	return updatetable($table, $setarr, $wherearr)?true:false;
	}

	function getByWhere($table,$where,$col="*"){
		global $db;
		$query = "SELECT ".$col."  FROM  ".table($table).' '.$where;
		// print_r($query);
		 //print_R("<br>");
		$result = $db->sql($query);
		$row = $db->getRow($result);
	return $row;
	}

	function getByWhere1($table,$where,$col="*"){
		global $db;
		$query = "SELECT ".$col."  FROM  ".$table.' '.$where;
		// print_r($query);
		 //print_R("<br>");
		$result = $db->sql($query);
		$row = $db->getRow($result);
	return $row;
	}
	///操作社区的函数..
	function getByWhere2($table,$where,$col="*"){
		global $db1;
		$query = "SELECT ".$col."  FROM  ".$table.' '.$where;
		// print_r($query);
		 //print_R("<br>");
		$result = $db1->sql1($query);
		$row = $db1->getRow1($result);
		return $row;
	}	
	
	
	function get_Details($table,$where,$col="*"){
		global $db;	
        $query = 'SELECT '.$col.' FROM ' . table($table) .' '.$where;
		//die($query);
		 //  print_r($query);
		// print_R("<br>");
		$result = $db->sql($query);
		$arr = array();
        while ($row = $db->getRow($result)){
        	$arr[] = $row;
        }
        return $arr;
	}

	function get_Details1($table,$where,$col="*"){
		global $db;	
        $query = 'SELECT '.$col.' FROM ' .$table.' '.$where;
		//die($query);
		  // print_r($query);
		// print_R("<br>");
		$result = $db->sql($query);
		$arr = array();
        while ($row = $db->getRow($result)){
        	$arr[] = $row;
        }
        return $arr;
	}
	/////////操作社区的函数
	function get_Details2($table,$where,$col="*"){
		global $db1;	
        $query = 'SELECT '.$col.' FROM ' .$table.' '.$where;
		//die($query);
		  // print_r($query);
		// print_R("<br>");
		$result = $db1->sql1($query);
		$arr = array();
        while ($row = $db1->getRow1($result)){
        	$arr[] = $row;
        }
        return $arr;
	}	
	function get_Details_ByResult($result){
		global $db;	
		$arr = array();
        while ($row = $db->getRow($result)) {
        	$arr[] = $row;
        }
        return $arr;
	}


	function getCount($table,$where,$col){
		$arrs = array();//查询出来的二维数组.
		$arr_single = array();//组装成一维数组.
		$arrs=get_Details($table,$where,$col);
		for($i=0; $i<count($arrs);$i++){
			$arr_single[$i] = $arrs[$col];
		}//end of for
		return $arr_single;
	}

	function set_delete($table,$isdeleted,$value,$id_column,$id){
		global $db;
		$query = "update ".table($table)." set ".$isdeleted."=".$value." where ".$id_column."=".$id;
		//print_r($query);
		$flag = $db->sql($query);
		return $flag;
	}

	function setWhere($instance)
		{
			$selectCols="";
			if(is_array($instance))
				foreach($instance as $key=>$word)
				{
				if($word||$word=='0')
					$selectCols.=' and '.$key."='".$word."'";
				
				}
			else
				$selectCols=$instance;
			
			return $selectCols;
		}
//根据sql直接操作.

	function operateSql($query){
		// print_r($query);
		global $db;
		$flag = $db->sql($query);
		return $flag;	
	}
////基础库的操作...主要用于更新操作..
	function operateSql1($query){
		// print_r($query);
		global $db;
		$flag = $db->sql($query);
		return $flag;	
	}


	function getCollegeid_s(){
		$arr = array();
		$usernumb = $_SESSION['usernumb'];
		if(strlen($usernumb)==6)
			$info  = get_Teacherinfo($usernumb);
		if(strlen($usernumb)==10)
			$info = get_Userinfo($usernumb);
		$collegeid = $info['collegeid'];//老师的学员id
		if($collegeid){
			$arr['collegeid'] = $collegeid;
			$arr['usernumb'] = $usernumb;
			$arr['authority'] = $_SESSION['authority'];
			return $arr;
		}
		return false;		 
	}

 

?>