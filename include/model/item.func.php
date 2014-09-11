<?php

    function item_ofcategories($categories,$start,$limit,$orderby = false)
    {
        global $db;
        $list = array();
        foreach ($categories as $key => $value) {
            $list[]=$value['index'];
        }
        $start = intval($start);
        $limit = intval($limit);
        $query = 'SELECT * FROM '.table('item')
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

    function item_ofcategory($category,$start=0,$limit=20)
    {
        global $db;
        $start = intval($start);
        $limit = intval($limit);
        $category = intval($category);
        $query = 'SELECT * FROM '.table('item')
            .' WHERE category = "'.$category.'"'
            .' AND isdelete = 0';
        $query .= ' ORDER BY `nice` DESC, `index` DESC';
        $query .= ' LIMIT '.$start.','.$limit;
        // echo $query;exit;
        $result = $db->sql($query);
        $arr = $db->getRows($result);
        return $arr;
    }

    function item_getById($id)
    {
        global $db;
        $id = base_protect($id);
        $query = 'SELECT * FROM '.table('item')
            .' WHERE `index`="'.$id.'" LIMIT 1';
        $result = $db->sql($query);
        $row = $db->getRow($result);
        return $row;
    }
    
    function item_delete($index){
        global $db;
        return updatetable('item', array("isdeleted" => 1), array("index" => $index));
    }
    
    function item_update($setarr, $index){
        $wherearr = array('index' => (int)$index);
        $setarr=base_protect($setarr);
        return updatetable('item', $setarr, $wherearr)?$index:false;
    }
    
    function item_insert($insertarr){
        $insertarr=base_protect($insertarr);
        return inserttable('item', $insertarr);
    }

