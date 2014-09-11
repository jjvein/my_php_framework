<?php

//系统函数文件...
//读取模板<!--{template header/menu/footer}-->

if (!function_exists('readTemplate')) {
	function readTemplate ($file) {
		$common_dir = ROOT_PATH . '/public/common/';
		$file = $common_dir . $file . '.html';
		if (file_exists($file)) {
			return sreadfile ($file);
		} else {
			die("template " . $file . ' not exists !');
		}
	}
}

//读取文件..
if (!function_exists('sreadfile')) {
	function sreadfile ($filename) {
		if (function_exists('file_get_contents')) {
			$cont = file_get_contents($filename);
		} else {
			$fp = fopen($filename, 'r');
			$cont = fread($fp, filesize($filename));
		}

		return $cont;
	}
}

//加载公共文件的函数.
//该函数主要加载include目录下的文件..
if (! function_exists('loadFunctions')) {
	function loadFunctions ($file) {
		$file = trim($file, '/');
		$include_file = INCLUDE_PATH . '/' . $file . '.inc.php';
		if (file_exists($include_file)) {
			require_once ($include_file);
		} else {
			throw new VException("不好意思, 您加载的自定义函数文件不存在!", 1);
		}
	}
}
?>