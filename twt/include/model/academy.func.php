<?php 
			//这里是院级积极分子的函数页面。





function academyTest_statusChange($table,$test_status,$test_id){
		global $db;
		$test_status += 1;
		$query = "update ".table($table)."  set test_status = ".$test_status." where test_id = ".$test_id;
		//print_r($query);
		return $db->sql($query);
	}

function academy_change_priority($table,$value,$course_id){
		global $db;
		$va = (int)base_nulltest($value);
		$query = "update ".table($table)."  set course_priority = course_priority +".$va."   where course_id = ".$test_id;
		print_r($query);
		return $db->sql($query);
	}


	function academyGrade_update($sql){
		global $db;
		return $db->sql($sql);		
	}


//下面的函数用来获取培训的期数.....
	function getTrainList(){
		global $db;
		$query = "select test_id,test_name from twt_academy_testlist where test_parent = '0' order by test_id desc ";
		$result = $db->sql($query);
			$arr = array();
        while ($row = $db->getRow($result)) {
        	$arr[] = $row;
        }
        return $arr;
	
	}
?>