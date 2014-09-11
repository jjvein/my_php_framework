<?php
namespace Twt\Libs;
use Twt\Libs\Db\DbModel;

//这个类是给packages 目录下的数据库操作类做继承的. 可以实现平滑过度.
class Packages {

	protected $tableName = null;  //表名..
	protected $fields = null;  //数据表字段..

	private $supportMethods = array(
		'insert',
		'update',
		'query',
		'exists',
		'totalCount',
		'del',
		);
	//这个变量在后面的之类中会使用到,所以定义为protected.
	protected $dbModel = null;

	public function __construct () {
		//实例化数据库模型.
		$this->dbModel = new DbModel ( $this->tableName, $this->fields );
	}

	public function execute ($func, $param) {

		if (in_array( $func, $this->supportMethods )) {
			return call_user_func_array(array($this->dbModel, $func), array($param));
		} else {
			throw new VException("不支持的数据库操作!", 1);
		}
	}
}
?>