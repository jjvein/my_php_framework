<?PHP
namespace Twt\Modules\Article_category;
use Twt\Libs\Controller;
use Twt\Packages\Article\Article_category;

class Ll extends Controller {

	public function run () {
		$this->mode = 'html';

		$ac = new Article_category ();
		$queryData = array(
			'where' => array(
				'status' => array(
					'value' => 1,
					'op' 	=> '='
					)
				)
			);
		$ret = $ac->query ($queryData);
		$this->extract('ret', $ret);
		
	}
}
?>