<?php
    function category_filter($option)
    {
        global $db;
        $option = base_protect($option);
        $query = 'SELECT * FROM '.table('category')
            .' WHERE ';
        foreach ($option as $key => $value) {
            $query.=' `'.$key.'`="'.$value.'" AND ';
        }
        $query.=' ishide=0';
        // echo $query;
        $result = $db->sql($query);
        $arr = $db->getRows($result);
        return $arr;
    }

    function category_ofteam($key)
    {
        global $db;
        $key = base_protect($key);
        $query = 'SELECT * FROM '.table('category')
            .' WHERE ishide=0';
        if($key)
            $query.=' AND groupkey="'.$key.'"';
        $query.=' ORDER BY nice DESC';
        $result = $db->sql($query);
        $arr = $db->getRows($result);
        // echo $query;
        // var_dump($arr);exit;
        return $arr;
    }

    function category_getById($index)
    {
        return category_get($index, false, false);
    }

    function category_get($index, $key, $nohide=true)
    {
        global $db;
        $key = base_protect($key);
        $index = intval($index);
        $query = 'SELECT * FROM '.table('category')
            .' WHERE `index`='.$index;
        if($key)    
            $query.=' AND groupkey="'.$key.'"';
        if($nohide)
            $query.=' AND ishide=0';
        $query.=' LIMIT 1';
        // echo $query;exit;
        $result = $db->sql($query);
        $row = $db->getRow($result);
        return $row;
    }

    function category_loadpreview($c)
    {
        $data = false;
        switch ($c['template']) {
            case 'news-list':
                load_model('news.func');
                $query='WHERE category='.$c['index']
                    .' AND isdelete=0'
                    .' AND ishide=0'
                    .' ORDER BY istop DESC LIMIT 8';
                $data = news_getBySQL($query);
                break;
            case 'item-list':
                load_model('item.func');
                $data = item_ofcategory($c['index']);
                break;
            case 'download-list':
                load_model('download.func');
                $data = download_ofcategory($c['index']);
                break;
            default:
                # code...
                break;
        }
        return $data;
    }
    
    function category_update($setarr, $index){
        $wherearr = array('index' => (int)$index);
        $setarr=base_protect($setarr);
        return updatetable('category', $setarr, $wherearr)?$index:false;
    }
    
    function category_insert($insertarr){
        $insertarr=base_protect($insertarr);
        return inserttable('category', $insertarr);
    }
