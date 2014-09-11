<?PHP

//********************************************


//下面这个函数是对证书发放写的。。主要是对条件和需要插入的内容进行了数组化。。

	function certificate_grant($table,$updatearr,$wherearr){
		global $db;
		$query = "update ".table($table)." set certificate_isgrant = 1 ";
		$and = "";
		$where = " where 1=1";
		if(is_array($updatearr)){
			foreach($updatearr as $key=>$value){
				$and.=",".$key." = '".$value."'";
			}
		}
		if(is_array($wherearr)){
			foreach($wherearr as $key =>$value){
			$where.=" and ".$key."= '".$value."'";
		}
		}
		$query.=$and.=$where;
		print_r($query);

		return $db->sql($query);
		
	}
?>