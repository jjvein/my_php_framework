<?php
    function controller_render($name, $action)
    {
        if(!$name)
            $name='default';
        
        $name=strtolower($name);
        $ret=preg_match('/(^[a-z][a-z]*?[a-z]$)/',$name);
        if($ret<=0)
            return '控制器名称非法'.$ret.$name;

        $path=_ROOT.'controller/'.$name.'.php';
        if(!file_exists($path))
            return '控制器不存在';
        include_once($path);

        $classname=$name.'Controller';
        if(!class_exists($classname))
            return '控制器声明不完全';

        $o = new $classname;
        if(!method_exists($o, 'render'))
            return '控制器未继承BaseController::Render';
        define('BASE_CONTROLLER',$name);
        return call_user_method('render', $o, $action);
    }

    class BaseController
    {
        function __construct()
        {

        }

        function index()
        {
            return 'Unimplement index Action';
        }

        function render($name)
        {
            if(!$name)
                $name = 'index';
            if(!method_exists($this, '_'.$name))
                return 'Unimplement '.$name.' Action';
            define('BASE_ACTION',$name);
            define('BASE_URL','./?c='.BASE_CONTROLLER.'&a='.BASE_ACTION);
            $ret = call_user_func(array( $this , '_'.$name ));
            if($ret != null && $ret !== true)
                return $ret;
            return true;
        }

    }