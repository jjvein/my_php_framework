<?PHP
namespace TWt\Libs;
class TemplateParser {

	private static $cachePath = '/cache/';
	private static $patterns = array(

		'/\<\!--\{template\s*(.*)\s*\}--\>/ie' => 'readTemplate(${1})',
		//这里要注意这个e, 表示执行该函数, 可以通过非返回值的形式来将内容输出到content中.
		
		'/\<\%\s*if(.*)\s*\%\>/i'  		 => '<?PHP if$1{ ?>',  //<%if(true)%>
		'/\<\%\s*else\s*\%\>/i'			 => '<?PHP }else{ ?>',	  //<%else%>
		'/\<\%\s*endif\s*\%\>/i'   		 => '<?PHP } ?>',		  //<%endif%>
		'/\<\%\s*elseif(.*)\s*\%\>/i'    => '<?PHP }else if$1{?>',		  //<%elseif(){%>

		'/\<%\s*foreach(.*)\s*\%\>/i' 	 => '<?PHP foreach$1{ ?>', //<%foreach($name as $key => $value)%>
		'/\<\%\s*endforeach\s*\%\>/i' 	 => '<?PHP } ?>',			//<%endforeach%>

		'/\<\%\=\s*(.*)\s*\%\>/i' 		 => '<?PHP echo $1 ?>',	//<%=value%>
		
		);


	private static $path_patterns = array(
		'/__PUBLIC__/' 		 => PUBLIC_PATH,  //公共项目文件夹..
		'/__ROOT__/'   => ROOT,
		);

	//如果找到有载入另外模板的代码,将其载入.

	public static function parse ($template, $module, $action) {

		$content = file_get_contents($template);

		//对模板标签进行处理。
		foreach (self::$patterns as $key => $value) {
			$content = preg_replace($key, $value, $content);
		}
		//对路径变量进行处理
		foreach (self::$path_patterns as $key => $value) {
			$content = preg_replace($key, $value, $content);
		}

		$cacheDir = ROOT_PATH . self::$cachePath . $module;
		if (!is_dir($cacheDir)) {
			if (!mkdir ($cacheDir, 777)) {
				throw new VException("不好意思, 缓存目录 " . $cacheDir ."不可写!", 1);
			} 
		}

		$cacheFile = $cacheDir . '/' . $action . '.blade.php';
		file_put_contents($cacheFile, $content);
		if (file_exists($cacheFile)) {
			return $cacheFile;
		} else {
			throw new VException("不好意思, 缓存文件写入失败! ", 1);
			
		}
	}
}
?>