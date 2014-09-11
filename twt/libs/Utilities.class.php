<?PHP
namespace Twt\Libs;

class Utilities {

	//获取头部信息.
	public static function getHeaders () {
		$headers = array ();
		foreach ($_SERVER as $key => $v) {
			if (strtolower(substr($key, 0, 5)) == 'http_') {
				$headers[$key] = $v;
			}
		}

		return $headers;
	}

	public static function U ($path) {
		if (empty($path)) {
			return '/';
		}

		$path = trim($path, '/');
		$path = ROOT . '/' . $path;

		return $path;
	}
	
}
?>