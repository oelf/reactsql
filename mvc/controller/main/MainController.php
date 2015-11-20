<?php

/**
 * @property MainModel $model
 * @property MainView $view
 */
class MainController extends Controller
{

	public function index()
	{
	
	}

	public function getServer()
	{
		echo json_encode($this->model->getServer());
	}

	public function getDatabases($server)
	{
		echo json_encode($this->model->getDatabases($server));
	}

	public function getTables($database)
	{
		echo json_encode($this->model->getTables($database));
	}
	
	public function getData($server, $database, $table, $offset = 0)
	{
		echo json_encode($this->model->getData($server, $database, $table, $offset));
	}
}

?>