<?php 

abstract class Controller
{
	protected $model;
	protected $view;
	
	public abstract function index();
	
	protected function getView()
	{
		return $this->view;
	}
	
	public function setView($view)
	{
		$this->view = $view;
	}
	
	protected function getModel()
	{
		return $this->model;
	}
	
	public function setModel($model)
	{
		$this->model = $model;
	}
}

?>