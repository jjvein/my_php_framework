<?php
    
    function group_update($set,$where)
    {
        if(!is_array($where))
            return group_updateIndex($set,$where);
        $set=base_protect($set);
        $where=base_protect($where);
        $flag=updatetable('group',$set,$where);
        return $flag;
    }
    
    function group_all()
    {
        global $db;
        $query = 'SELECT * FROM '.table('group').' WHERE ishide=0 ';
        $result = $db->sql($query);
        $rows = $db->getRows($result);
        return $rows;
    }

    function group_teams()
    {
        global $db;
        $query = 'SELECT * FROM '.table('group').' WHERE ishide=0 AND grouptype="team"';
        $result = $db->sql($query);
        $rows = $db->getRows($result);
        return $rows;
    }

    function group_getteam($key)
    {
        global $db;
        $key = base_protect($key);
        $query = 'SELECT * FROM '.table('group')
            .' WHERE groupkey="'.$key.'" AND ishide=0 AND grouptype="team"'
            .' LIMIT 1';
        // echo $query;    exit;
        $result = $db->sql($query);
        $rows = $db->getRow($result);
        return $rows;
    }

    function group_getById($id)
    {
        global $db;
        $id = base_protect($id);
        $query = 'SELECT * FROM '.table('group')
            .' WHERE `index`="'.$id.'" AND ishide=0'
            .' LIMIT 1';
        // echo $query;    exit;
        $result = $db->sql($query);
        $rows = $db->getRow($result);
        return $rows;
    }
    
    function group_updateIndex($setarr, $index){
        $wherearr = array('index' => (int)$index);
        $setarr=base_protect($setarr);
        return updatetable('group', $setarr, $wherearr)?$index:false;
    }
    
    function group_insert($insertarr){
        $insertarr=base_protect($insertarr);
        return inserttable('group', $insertarr);
    }

