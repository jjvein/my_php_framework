<?PHP
namespace Twt\Libs;

class VException extends \Exception {
	private $exception_file = '/log/exception.log';
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0) {
        // 自定义的代码

        // 确保所有变量都被正确赋值
        parent::__construct($message, $code);
    }

    public function __toString() {
    	$str = $this->errorMessage ();
    	file_put_contents(ROOT_PATH . $this->exception_file, $str, FILE_APPEND);
        return $this->errorMessage ();
    }    

	public function errorMessage () {
		$str = "\n错误报告:" . date ('Y-m-d H:i:s') . "\n";
		$str .= "错误码:" . $this->code . "\n";
		$str .= "错误行:" . $this->getLine () . "\n";
		$str .= "错误文件:" . $this->getFile () . "\n";
		$str .= "错误消息:" . $this->getMessage() . "\n";

		return $str;
	}
}
?>