<?php
@session_start();
define ("ROOT_PATH", __DIR__); //这里通过绝对路径来获取文件..

//由于访问的资源文件通过相对路径进行访问.
//比如该项目不是在host根目录下, 那么我们的PUBLIC_PATH 就需要加上你项目的文件夹, 注意前面的/ ,表示
//相对于host的绝对路径.
//不使用绝对路径的原因是: css的link使用的就是相对路径.

define('ROOT', '/twt');  //项目的根路径..
include_once( ROOT_PATH . '/common.php' );

?>