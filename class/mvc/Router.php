<?php

class Router
{
	const DEFAULT_CONTROLLER = "index";
	const DEFAULT_METHOD = "index";
	
	private $basename;
	private $key;
	private $methodName;
	private $params = array();
	private $bolError = true;
	private $error = "";

	public function __construct($route)
	{
		if (trim($route) == "")
		{
			$route = Router::DEFAULT_CONTROLLER . '/' . Router::DEFAULT_METHOD;
		}
		$arrRoute = explode("/", $route);
		
		foreach ($arrRoute as $key => $val)
		{
			if ($key == 0)
			{
				$controller = trim($val);
			}
			elseif ($key == 1)
			{
				$this->methodName = trim($val);
			}
			else
			{
				$this->params[] = $val;
			}
		}
		if (trim($this->methodName) == "")
		{
			$this->methodName = Router::DEFAULT_METHOD;
		}
		
		$arrController = preg_split('/(?=[A-Z])/', $controller, 2, PREG_SPLIT_NO_EMPTY);
		if (count($arrController) == 1)
		{
			$this->key = strtolower($controller);
			$this->basename = ucfirst($this->key);
		}
		else
		{
			$this->key = strtolower($arrController[0]);
			$controllersubname = ucfirst($arrController[1]);
			$this->basename = ucfirst($this->key) . $controllersubname;
		}
	}

	public function route()
	{
		$controllerName = $this->basename . 'Controller';
		$viewName = $this->basename . 'View';
		$modelName = $this->basename . 'Model';
		if (class_exists($controllerName, true))
		{
			if (class_exists($viewName, true))
			{
				include_once 'inc/functions.php';
				
				$modelObject = null;
				if (class_exists($modelName, true))
				{
					$modelObject = new $modelName();
				}
				
				$controllerObject = new $controllerName();
				/* @var $controllerObject Controller */
				
				$viewObject = new $viewName();
				/* @var $viewObject View */
				if ($modelObject instanceof Model)
				{
					$controllerObject->setModel($modelObject);
				}
				
				if ($controllerObject instanceof Controller)
				{
					if ($viewObject instanceof View)
					{
						$controllerObject->setView($viewObject);
						
						if (method_exists($controllerObject, $this->methodName))
						{
							call_user_func_array(array($controllerObject, $this->methodName), $this->params);
							$this->bolError = false;
						}
						else
						{
							$this->error = "Controllermethode nicht gefunden. [$controllerName | $this->methodName]";
						}
					}
					else
					{
						$this->error = "Viewklasse erbt nicht von View. [$viewName]";
					}
				}
				else
				{
					$this->error = "Controllerklasse erbt nicht von Controller. [$controllerName]";
				}
			}
			else
			{
				$this->error = "View nicht gefunden. [$viewName]";
			}
		}
		else
		{
			$this->error = "Controller nicht gefunden. [$controllerName]";
		}
	}
}
?>