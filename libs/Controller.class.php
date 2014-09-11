<?PHP
namespace Twt\Libs;
abstract class Controller {



	protected $request;  //这个变量需要在子类中进行获取, 所以可以设置为protected.
	private $userSession;
	private $module = ''; //模块名.
	private $action = ''; //方法名.
	
	private $variables = array();	//导入到页面中的变量.

	public $mode = 'html';	//默认是显示html模板页面.

	abstract function run ();  //抽象方法, 不可直接实例化, 必须继承.

	public function __construct (HttpRequest $request, $userSession) {
		$this->request = $request;
		$this->userSession = $userSession;

		//执行自己的run 方法.
		$this-> run ();
		$this-> display (); //如何渲染数据.
	}

	//展现模板页面.
	public function display () {
		//将变量放入到该方法中,进而可以在数组中进行展现..
		//其他没有通过exportVariables () 函数导入的变量将不会显示在模板中.	
		$this->sendHeader();
		$mode = strtolower($this->mode);
		if ( $mode == 'html' ) {
			//加载模板页面..
 			extract($this->variables);
			$template = $this->findTemplate ();
			if (file_exists($template)) {
				//这里需要进行模板的解析操作..
				$file_path = TemplateParser::parse ($template, $this->request->module, $this->request->action);
				require ($file_path);
				//这里还有很多要做的事情..
			} else {
				throw new VException("Sorry, file:" . $template . " doesn't exists!!", 400);
				
			}
		} else if ( $mode == 'json' ) {
			$this->echoJson ();
		} else if ( $mode == 'xml' ) {
			//这里缺少一个将PHP数组转化xml的类型转化器.
			header("Content-type: text/xml");
			echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
			echo "<users><user><name>小小菜鸟</name><age>24</age><sex>男</sex></user>";
			echo "<user><name>艳艳</name><age>23</age><sex>女</sex></user></users>";
		} else {
			throw new VException("不支持的数据输出格式!", 1);
			
		}
	}

	public function echoJson () {
		$view = $this->view;
		$view ['code'] = isset($view ['code']) ? $view ['code'] : 200;
		$json = json_encode( $view );
		echo $json;		
	}
	public function findTemplate () {

		$template_folder = ROOT_PATH . '/template/' . $this->request->module . '/';
		$template_html = $template_folder . $this->request->action . '.html';
		return $template_html;
	}

	//将变量导入到模板中. 
 	public function extract ($key, $value) {
 		if ($key) {
			$this->variables [$key] = $value;
 		}
 	}

 	public function sendHeader () {
 		$mode = $this->mode;
 		switch ($mode) {
                case 'json' :
                    header('Content-Type: text/plain; charset=UTF-8');
                    break;
                case 'captcha' :
                    ob_clean();
                    header('Content-type: image/jpeg;');
                    header("Cache-Control: no-cache");
                    header("Expires: -1");
                    break;
                default:
                    header('Content-Type: text/html; charset=UTF-8');
 		}
 	}
//end-of-clazz.
}
?>