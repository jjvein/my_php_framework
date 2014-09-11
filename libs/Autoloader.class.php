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
			//这里有个强制的规定, 如果在自动加载过程中没有将指定的文件加载进来的话,
			//那么系统直接抛出异常进行处理. 没有容错机制.
			throw new VException("未找到文件" . $filePath, 1);
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