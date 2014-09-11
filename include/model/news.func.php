<?php 
    function news_ofcategories($category1,$category2)
    {
        global $db;
        $list = array();
        $query = 'SELECT * FROM '.table('column')
            .' WHERE `column_id` IN ( '.$category1.','.$category2.') order by column_pid';
        $result = $db->sql($query);
        $arr = $db->getRows($result);
        return $arr;
    }

	function news_countAll($row='COUNT(*) as count',$where=false){
		$query='SELECT '.$row.' FROM '.table('news').' '.$where;
		global $db;
		$result=$db->sql($query);
		// echo $query.'<br/>';
		return $db->getRow($result);
	}

	function news_incvisit($index){
		$index=(int)$index;
		global $db;
		$query="UPDATE " .table('news').' SET visitcount=visitcount+1 WHERE `index`='.$index.' LIMIT 1';
		// die($query);
		$result=$db->sql($query);
	}
	
	function news_getBySQL($where,$col="*"){
		global $db;
	
        $query = 'SELECT '.$col.' FROM ' . table('news') .' '.$where;
		//die($query);
		$result = $db->sql($query);
		$arr = array();
        while ($row = $db->getRow($result)) {
        	$row['newsname']=base_escape($row['newsname']);
        	$arr[] = $row;
        }
        return $arr;
	}
	
	function news_getById($index,$autoprotect=true){
		global $db;
		if($index == NULL)
			return NULL;
		else{
			$index=(int)$index;
	        $query = "SELECT * FROM ".table('news')." WHERE `index` = '$index'";
			$result = $db->sql($query);
			$row = $db->getRow($result);
			if($autoprotect)
        		$row['subject']=base_escape($row['subject']);
	        return $row;
		}
	}
	
	function news_delete($index){
		global $db;
        return updatetable('news', array("isdeleted" => 1), array("index" => $index));
	}
	
	function news_update($setarr, $index){
		$wherearr = array('index' => (int)$index);
		$setarr=base_protect($setarr);
		return updatetable('news', $setarr, $wherearr)?$index:false;
	}
	
	function news_insert($insertarr){
		$insertarr=base_protect($insertarr);
		return inserttable('news', $insertarr);
	}

