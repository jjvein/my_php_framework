<?PHP
namespace Twt\Libs;
/**
 * 这个类专门用来分发路由功能.
 * Filename: Dispatcher.class.php
 * Author: Vein1992
 * Date: 2014-08-31
 */

class Dispatcher {

	private $request;
	public function __construct () {
		$this->request = new HttpRequest ();
	}

	public function dispatch () {
		$request = $this->request;
		$request_args = $request->request_args; //请求参数.
		
		if ( is_array( $request_args ) && $request_args) {
			$this->action = array_pop( $request_args );
			$this->module = array_pop( $request_args );
		} else {
			$this->module = 'welcome';
			$this->action = 'main';
		}

		//print_r($request_args);
		$class = "Twt\\Modules\\" . ucfirst( $this->module ) . "\\" . ucfirst ( $this->action );
		if (!class_exists( $class )) {
			//这里在检测类的时候, 马上去调用自动加载函数进行文件的加载.
			//我们无法在自动加载的地方直接抛出异常, 只能在那里加入一个log
			//来标记一下哪个文件没有加载成功! 所以在这里加了一步
			//判断我们的控制器是否存在, 如果不存在, 那么就直接抛出异常了.
			throw new Vexception("未找到类:" . $class . " !", 1);
		}

		$controller = new $class ( $request, $this->userSession);	

		
		

	}
//end-of-clazz.
}
?>