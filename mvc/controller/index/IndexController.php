<?php 

/**
 * @property IndexModel $model
 * @property IndexView $view
 */
class IndexController extends Controller
{
	public function index()
	{
		$this->view->openHtml();
		$this->view->closeHtml();
	}
}

?>