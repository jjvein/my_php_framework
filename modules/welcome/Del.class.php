<?PHP
namespace Twt\Modules\Welcome;
use Twt\Libs\Controller;
use Twt\Packages\Demo\Test;

class Del extends Controller {

	public function run () {

		$this->mode = "json"; //可以通过这个来控制输出类型.

		$data = array(
			'id' => $this->reqeuest->GET['id'];
			);	
		$pac = new Test ();
		$ret = $pac->del ($data);
		print_r($ret);
		$this->view = $ret;
	}



}
?>