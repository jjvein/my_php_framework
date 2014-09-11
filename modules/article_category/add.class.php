<?PHP
namespace Twt\Modules\Article_category;
use Twt\Libs\Controller;

class add extends Controller {

	public function run () {
		$this->mode = 'html';
		$this->view = array(
			'code' => 0,
			'msg'  => 'Hello world'
			);
	}
}
?>