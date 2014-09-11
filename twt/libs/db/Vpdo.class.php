<?PHP
namespace Twt\Libs\Db;
//这个类专门用来封装PDO.
class Vpdo extends \PDO {
	public function __construct($host, $db, $user, $pass, $port = 3306, $persist = FALSE) {
		$dsn ="mysql:dbname={$db};host={$host};port={$port};";
		$options = array(
			\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
			\PDO::ATTR_TIMEOUT => 10,
		);

		parent::__construct($dsn, $user, $pass, $options);
	}
}
?>