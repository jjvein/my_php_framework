<?php

    function download_ofcategories($categories,$start,$limit,$orderby = false)
    {
        global $db;
        $list = array();
        foreach ($categories as $key => $value) {
            $list[]=$value['index'];
        }
        $start = intval($start);
        $limit = intval($limit);
        $query = 'SELECT * FROM '.table('download')
            .' WHERE category IN ('.implode(',', $list).')'
            .' AND isdelete = 0';
        if($orderby)
        {
            if(!is_array($orderby))
                $orderby = array($orderby);
            $query.=' ORDER BY '.implode(',', $orderby);
        }
        $query.=' LIMIT '.$start.','.$limit;
        // echo $query;
        $result = $db->sql($query);
        $arr = $db->getRows($result);
        return $arr;
    }

    function download_ofcategory($category)
    {
        global $db;
        $category = intval($category);
        $query = 'SELECT * FROM '.table('download')
            .' WHERE category = "'.$category.'"'
            .' AND isdelete = 0';
        // echo $query;
        $result = $db->sql($query);
        $arr = $db->getRows($result);
        return $arr;
    }

    function download_getById($id)
    {
        global $db;
        $id = base_protect($id);
        $query = 'SELECT * FROM '.table('download')
            .' WHERE `index`="'.$id.'" LIMIT 1';
        $result = $db->sql($query);
        $row = $db->getRow($result);
        return $row;
    }
    
    function download_delete($index){
        global $db;
        return updatetable('download', array("isdeleted" => 1), array("index" => $index));
    }
    
    function download_update($setarr, $index){
        $wherearr = array('index' => (int)$index);
        $setarr=base_protect($setarr);
        return updatetable('download', $setarr, $wherearr)?$index:false;
    }
    
    function download_insert($insertarr){
        $insertarr=base_protect($insertarr);
        return inserttable('download', $insertarr);
    }

