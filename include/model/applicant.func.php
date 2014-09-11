<?php
//这里是申请人培训的相关函数。


	function applicantTest_statusChange($table,$test_status,$test_id){
		global $db;
		$test_status += 1;
		$query = "update ".table($table)."  set test_status = ".$test_status." where test_id = ".$test_id;
		//print_r($query);
		return $db->sql($query);
	}
//这个函数的作用是改变我们priority属性的。通过它可以设置课程排序的优先级。
	function applicant_change_priority($table,$value,$course_id){
		global $db;
		$va = (int)base_nulltest($value);
		//print_r($va);
		$query = "update ".table($table)."  set course_priority = course_priority +".$va."   where course_id = ".$course_id;
		//print_r($query);
		return $db->sql($query);
		}

	//***************************************************************
//********************************************
	//这个方法写的太尼玛好了，要判断为0的情况时不能当做假来处理，要当做0处理。

	function certificate_grant($table,$updatearr,$wherearr){
		global $db;
		$query = "update ".table($table)." set certificate_isgrant = 1 ";
		$and = "";
		$where = " where 1=1";
		if(is_array($updatearr)){
			foreach($updatearr as $key=>$value){
				if($key == 'certificate_id')
				$and.=",".$key." = ".$value;
				else 
				$and.=",".$key." = '".$value."'";
			}
		}
		if(is_array($wherearr)){
			foreach($wherearr as $key =>$value){
			$where.=" and ".$key."= '".$value."'";
		}
		}
		$query.=$and.=$where;
		//print_r($query);

		return $db->sql($query);
		

	}
//这个方法是获取题目的答案....

	function getAnswer($num){
		$answer = "";
		if($num==1)
			$answer = "A";
		if($num==2)
			$answer = "B";
		if($num==4)
			$answer = "C";
		if($num==8)
			$answer = "D";
		if($num==16)
			$answer = "E";

		if($num==3)
			$answer = "AB";
		if($num==5)
			$answer = "AC";
		if($num==9)
			$answer = "AD";
		if($num==17)
			$answer = "AE";

		if($num==6)
			$answer = "BC";
		if($num==10)
			$answer = "BD";
		if($num==18)
			$answer = "BE";
		if($num==12)
			$answer = "CD";

		if($num==20)
			$answer = "CE";
		if($num==24)
			$answer = "DE";
		if($num==7)
			$answer = "ABC";
		if($num==11)
			$answer = "ABD";
		if($num==19)
			$answer = "ABE";
		if($num==13)
			$answer = "ACD";
		if($num==21)
			$answer = "ACE";
		if($num==25)
			$answer = "ADE";

		if($num==14)
			$answer = "BCD";
		if($num==22)
			$answer = "BCE";
		if($num==28)
			$answer = "CDE";
 

		if($num==15)
			$answer = "ABCD";
		if($num==23)
			$answer = "ABCE";
		if($num==29)
			$answer = "ACDE";
		if($num==30)
			$answer = "BCDE";
		if($num==31)
			$answer = "ABCDE";

	return $answer;
	}
?>