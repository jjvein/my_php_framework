<?PHP
namespace Twt\Modules\Article_category;
use Twt\Libs\Controller;
use Twt\Packages\Article\Article_category;

class add_handler extends Controller {

	public function run () {
		$this->mode = 'json';
		$name = $this->request->REQUEST['name'];
		if ($name) {
			$ac = new Article_category ();
			$data = array(
				'name'	 => $name,
				'status' => 1,
				);
			$ret = $ac->add ($data);
		} else {
			$ret = 0;
		}

		$this->view = array(
			'code' => 0,
			'msg'  => $ret,
			);
	}
}
?>