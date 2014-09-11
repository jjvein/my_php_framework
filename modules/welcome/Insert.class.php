<?PHP
namespace Twt\Modules\Welcome;
use Twt\Libs\Controller;
use Twt\Packages\Demo\Test;
use Twt\Libs\Vlog;
class Insert extends Controller {

	public function run () {
		if (!$this->init () ) {
			return FALSE;
		}

		$arr = array(
			'name' => $this->name,
			'pwd'  => $this->pwd,
			);
		$this->mode = "json";
		$pc = new Test ();
		$ret = $pc->insert ($arr);
		$this->view = $ret;

	}

	public function init () {
		$this->name = $this->request->GET ['name'];
		$this->pwd  = $this->request->GET ['pwd'];
		return true;
	}


}
?>