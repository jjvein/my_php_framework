<?PHP
namespace Twt\Modules\Welcome;
use Twt\Libs\Controller;
use Twt\Packages\Demo\Test;

class Select extends Controller {

	public function run () {
		$this->mode = 'html';
		$queryData  = array(
			'fields' => array(
				'id',
				'name',
				'pwd',
				),
			'where' => array(
				'id' => array(
					'value' => 1,
					'op'	=> '>='
					)
				)
			);

		$pc = new Test ();
		$ret = $pc->query ($queryData );
		//这个比较好的方法, 将变量放到Controller的一个私有变量中,
		//然后使用extract 函数将数组转化为display()的一个成员变量.
		//以便于展现.
		$this->extract('ret', $ret);	

	}

//end-of-clazz.
}
?>