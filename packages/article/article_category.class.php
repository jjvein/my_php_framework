<?PHP
namespace Twt\Packages\Article\Article_category;
use Twt\Libs\Packages;
use Twt\Libs\Vlog ;

class Article_category extends Packages {

	protected $table = "Article_category";
	protected $fileds = array(
		'id',
		'name',
		'status',
		);

	public function add ($data) {
		if ( is_array($data) && $data ) {
			$ret = $this->execute ('insert', $data);	
			return $ret;
		} else {
			VLog::warning ("插入数据请填写数据.");
		}
	}
}
?>