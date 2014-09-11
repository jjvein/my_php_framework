<?php
    global $_CACHE;
    $_CACHE = array();
    function cache_call($method, $args = null)
    {
        return cache_call_array($method, $args==null? false: array($args));
    }
    
    function cache_call_array($method, $args = false)
    {
        global $_CACHE;
        $key = $method.'@'.implode('|', $args);
        if(isset($_CACHE[$key]))
        {
            return $_CACHE[$key];
        }
        if(!is_array($args) || count($args) == 0)
            $_CACHE[$key] = call_user_func($method);
        else if(count($args) == 1)
            $_CACHE[$key] = call_user_func($method, $args[0]);
        else if(count($args) == 2)
            $_CACHE[$key] = call_user_func($method, $args[0], $args[1]);
        else if(count($args) == 3)
            $_CACHE[$key] = call_user_func($method, $args[0], $args[1], $args[2]);
        return $_CACHE[$key];
    }