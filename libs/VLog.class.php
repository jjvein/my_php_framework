<?PHP
namespace Twt\Libs;

class Vlog {

	private static $logPath = '/log';

	private static $logLevel = array(
		'error' => LOG_ERR,
		'warning' => LOG_WARNING,
		'notice' => LOG_NOTICE
		);

	public static function setLogFile ($logLevel) {
		$logFile = ROOT_PATH . self::$logPath . '/log_' . $logLevel;

		return $logFile;
	}
	public static function log ( $logLevel, $message) {
		if ( !array_key_exists($logLevel, self::$logLevel) ) {
			$logLevel = "error";	
		}

		$logFile = self::setLogFile ($logLevel);
		$str = "Log等级:" . $logLevel . "\n";
		$str .= "时间:" . date ('Y-m-d H:i:s') . "\n";

		$file_info = debug_backtrace();
		$str .= "错误文件:" . $file_info[1]['file'] . "\n";
		$str .= "错误行:" . $file_info[1]['line'] . "\n";
		$str .= "消息:" . $message . "\n\n";		
		//error_log($str, self::$logLevel [$logLevel], $logFile);
		return file_put_contents($logFile, $str, FILE_APPEND);
	}

	public static function error ($message) {
		return self::log ('error', $message);
	}

	public static function warning ($message) {
		return self::log ('warning', $message);
	}

	public static function notice ($message) {
		return self::log ('notice', $message);
	}
}
?>