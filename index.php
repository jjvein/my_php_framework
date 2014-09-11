<?php
@session_start();
define ("ROOT_PATH", __DIR__); //这里通过绝对路径来获取文件..

//由于访问的资源文件通过相对路径进行访问.
//比如该项目不是在host根目录下, 那么我们的PUBLIC_PATH 就需要加上你项目的文件夹, 注意前面的/ ,表示
//相对于host的绝对路径.
//不使用绝对路径的原因是: css的link使用的就是相对路径.
/**
 * 这里需要注意一点, 如果域名对应的更目录就是项目目录,那么无需设置ROOT,
 * 如果项目不是在更目录中, 那么需要设置下面的root变量. 比如
 * 项目的目录是: localhost/twt 就需要设置为: /twt
 * 
*/
#define('ROOT', '/');  //项目的根路径..
include_once( ROOT_PATH . '/common.php' );

?>