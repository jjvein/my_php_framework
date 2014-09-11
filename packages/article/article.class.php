<?PHP
namespace Twt\Packages\Article\Article;
use Twt\Libs\Packages;
use Twt\Libs\Vlog;

class Article extends Packages {

/*

create table category(
	id int(11) not null auto_increment,
	title varchar(50) not null comment "分类名称",
	status tinyint(1) default 1 comment "0表示不显示, 1表示显示",
	primary key (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 */
	//这里,你需要定义到底使用那咱表.
	protected  $tableName = 'article';
	protected $fields = array(
		'id',
		'name',
		'pwd',
		);



}

?>