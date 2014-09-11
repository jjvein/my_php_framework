<?PHP
namespace Twt\Packages\Article;
use Twt\Libs\Packages;
use Twt\Libs\Vlog ;

class Article_category extends Packages {
/*
create table article_category(
	id int(11) not null auto_increment,
	title varchar(50) not null comment "分类名称",
	status tinyint(1) default 1 comment "0表示不显示, 1表示显示",
	primary key (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 */
	protected $tableName = "article_category";
	protected $fields = array(
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

	public function query ($data) {
		if ( is_array($data) && $data ) {
			$ret = $this->execute ('query', $data);	
			return $ret;
		} else {
			VLog::warning ("插入数据请填写数据.");
		}		
	}
}
?>