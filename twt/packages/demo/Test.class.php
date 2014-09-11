<?PHP
namespace Twt\Packages\Demo;
use Twt\Libs\Packages;
use Twt\Libs\Vlog;
class Test extends Packages { 

	//这里,你需要定义到底使用那咱表.
	protected  $tableName = 'user';

	protected $fields = array(
		'id',
		'name',
		'pwd',
		);

	public function insert ($data) {
		if ( is_array($data) && $data ) {
			$ret = $this->execute ('insert', $data);	
			return $ret;
		} else {
			VLog::warning ("插入数据请填写数据.");
		}
	}

	public function update ($data) {
		$id = $data ['id'];
		if (!$id) {
			Vlog::error ('不好意思, 没有设置ID');

			return false;
		}
		$updateData = array(
			'where' => array(
				'id' => array(
					'value' => $id,
					'op' 	=> '='
					)
				),
			'update' => $data
			);
		$ret = $this->execute ('update', $updateData);
		return $ret;		
	}

	public function query ($data) {

		$ret = $this->execute ('query', $data);
		return $ret;		
	}

	
}
?>