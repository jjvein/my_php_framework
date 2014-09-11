<?php

	function column_update($setarr, $index){
		$wherearr = array('index' => (int)$index);
		$setarr=base_protect($setarr);
		return updatetable('column', $setarr, $wherearr)?$index:false;
	}
	
	function column_delete($index){
		global $db;
        return updatetable('column', array('belong' => 4), array('index' => $index));
	}
	
	function column_insert($table,$insertarr){
		$insertarr=base_protect($insertarr);
		return inserttable($table, $insertarr);
	}
	
	function column_getById($index,$autoprotect=true){
		global $db;
		if($index == NULL)
			return NULL;
		else{
			$index=(int)$index;
	        $query = "SELECT * FROM ".table('column')." WHERE `index` = '$index'";
			$result = $db->sql($query);
			$row = $db->getRow($result);
			if($autoprotect)
        		$row['subject']=base_escape($row['subject']);
	        return $row;
		}
	}
		function findSecond($index,$_where,$autoprotect=true){
		global $db;
		if($index == NULL)
			return NULL;
		else{
		    $index=(int)$index;
		    if($_where==''){
			    $where= " where `parent` = $index";
			}else{
			    $where= " where `parent` = $index AND `isDelete` = 0";
			}
	        $query = "SELECT * FROM ".table('column').$where;
			//echo $query;			
			$result = $db->sql($query);
			$row = mysql_num_rows($result);
	        return $row;
		}
	}
	function column_getSecond($index,$_where,$autoprotect=true){
		    global $db;	
			$index=(int)$index;
			if($_where==''){
			    $where= " where `parent` = $index";
			}else{
			    $where= " where `parent` = $index AND `isDelete` = 0";
			}
	        $query = "SELECT * FROM ".table('column').$where;
			$result = $db->sql($query);
			$list = array();
			while($arr = $db->getRow($result)){
				$list[] = $arr;
			}
	        return $list;
		}

function column_get_Details($table,$where,$col="*"){
		global $db;
	
        $query = 'SELECT '.$col.' FROM ' . table($table) .' '.$where;
		//die($query);
		//print_r($query);
		$result = $db->sql($query);
		$arr = array();
        while ($row = $db->getRow($result)) {
        	$arr[] = $row;
        }
        return $arr;
	}
?>