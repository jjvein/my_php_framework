<?PHP
namespace Twt\Modules\Welcome;
use Twt\Libs\Controller;

class Main extends Controller {

	public function run () {
		$this->mode = 'html';
		$this->view = array(
			'code' => 0,
			'msg'  => 'Hello world'
			);
	}
}
?>