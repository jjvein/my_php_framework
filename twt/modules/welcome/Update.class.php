<?PHP
namespace Twt\Modules\Welcome;
use Twt\Libs\Controller;
use Twt\Packages\demo\Test;
class Update extends Controller {

	public function run () {

		$this->mode = 'json';
		$updateParams = array(
			'where' => array(
				'id' => array(
					'value' => 1,
					'op'	=> '=',
					)
				),
			'update' => array(
				'name' => 'lichao',
				'pwd'  => 'helloworld'
				)
			);

		$pc = new Test ();
		$ret = $pc->update ($updateParams);
		print_r($ret);
	}

}
?>