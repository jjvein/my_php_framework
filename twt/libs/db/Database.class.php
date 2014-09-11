<?PHP
namespace Twt\Libs\Db;

//这里需要将配置变量载入,然后处理成全局变量.
require ROOT_PATH . '/' . 'config/config.inc.php';
$GLOBALS['dbconfig'] = $IVPConfig ['database'];

class Database {

	private static $connection = array();

	//singleton 一个数据库对象..
	public static function singleton () {
		static $db;
		is_null( $db ) && $db = new Database ();
		return $db;
	}

	//这里也是将多个通过静态变量, 将多个数据库的连接进行持久化. 
	public function getConnection () {
		$dbconfig = $GLOBALS['dbconfig'];
		$dbname = $dbconfig ['dbname'];
		if ( !self::$connection [$dbname]) {
			$pdo = new Vpdo (
				$dbconfig ['host'], $dbname, 
				$dbconfig ['dbuser'], $dbconfig ['dbpassword']
			);
			self::$connection [$dbname] = $pdo;
		}

		return self::$connection [$dbname];
	}

	public function write ($sql, $params) {
		$sth = $this->prepare ($sql, $params);
		$rowCount = $sth->rowCount ();
		$lastInsertId = $this->getLastInsertId ();
		if ($lastInsertId) {
			//如果是插入的话,返回插入id.
			return $lastInsertId;
		}
		//否则,返回受影响的行数..
		return $rowCount;
	}

	public function read ($sql, $params) {
		$sth = $this->prepare ($sql, $params);
		$ret = array();
		while ($item = $sth->fetch( \PDO::FETCH_ASSOC)) {
			array_push($ret, $item);
		}
		return $ret;
	}

	public function prepare ($sql, $params) {
		$conn = $this->getConnection ();
		$sth = $conn->prepare($sql, array(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => FALSE));
		if (!empty($params)) {
			foreach ($params AS $key => $value) {
				if (strpos($key, '_') === 0) {
					$sth->bindValue(":{$key}", $value, \PDO::PARAM_INT);
				} else {
					$sth->bindValue(":{$key}", $value, \PDO::PARAM_STR);
				}
			}
		}
		$sth->execute();
		return $sth;		
	}

	public function getLastInsertId () {
		$conn = $this->getConnection ();
		$lastInsertId = $conn->lastInsertId ();
		return $lastInsertId;
	}
}
?>