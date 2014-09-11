<?PHP
namespace Twt\Modules\Demo;
use Twt\Libs\Controller;
//Demo Controller
class Main extends Controller {

	public function run () {
		if ( !$this->init () ) {
			return false;
		}

		$content = array(
			'name' => 'Vein1992',
			'age'  => 22
			);
		$this->html = true;
		$this->view = $content;
	}

	public function init () {

		return true;		
	}
}
?>