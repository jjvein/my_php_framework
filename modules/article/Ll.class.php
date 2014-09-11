<?PHP
namespace Twt\Modules\Article;
use Twt\Libs\Controller;

class Ll extends Controller {

	public function run () {
		$this->mode = 'html';
		$this->view = array(
			'code' => 0,
			'msg'  => 'Hello world'
			);
	}
}
?>