<?PHP
namespace Twt\Libs;
/**
 * The Autoloader class is used to load invoked class file automaticly.
 */
class Autoloader {

	//construct function to register the autoload method to the global scope.
	public function __construct () {

		spl_autoload_register(array($this,'autoload'));  //注册自动加载函数.
	}

	//spotlight method for us to include class file.
	public function autoload($className) {
		$filePath = $this -> getFilePath($className);
		if(file_exists($filePath)) {
			require_once($filePath);
		} else {
			//这里需要做个处理, 将没有载入的文件全部记录到一个log中..
			VLog::notice ("不好意思, 没有找到文件:" . $filePath);
		}
	}
	/**
	 * convert the namespace which looks like 'Queen\\Libs\\' to 
	 * root_path / Libs / 
	 * @param  String $className the Namespace .
	 * @return String   the path of the specified clazz.
	 */
	public function getFilePath($className) {
		$pieces = explode('\\', $className);
		array_shift($pieces);
		$class_name = ucfirst(array_pop($pieces));
		$filePath = ROOT_PATH . DIRECTORY_SEPARATOR 
			. strtolower(implode(DIRECTORY_SEPARATOR, $pieces))
			. '/' . $class_name . '.class.php';
		return $filePath ;

	}
/*end-of-clazz*/	
}
?>