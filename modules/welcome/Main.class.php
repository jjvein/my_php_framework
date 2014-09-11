<?PHP
namespace Twt\Modules\Welcome;
use Twt\Libs\Controller;

class Main extends Controller {

	public function run () {
		$this->mode = 'json';
		$this->view = array(
			'code' => 0,
			'msg'  => 'Hello world'
			);
		
	}
}
?>