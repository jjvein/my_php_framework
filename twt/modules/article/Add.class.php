<?PHP
namespace Twt\Modules\Article;
use Twt\Libs\Controller;
use Twt\Packages\Front\Article;

class Add extends Controller {

	public function run () {
		loadFunctions ('user/common');
		$this->mode = "html";
		
	}
}
?>