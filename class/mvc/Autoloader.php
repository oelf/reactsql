<?php

class Autoloader
{
	private $script = 'autoload.php';
	private $arrClasses = array();

	public function __construct()
	{
		if (! file_exists($this->script))
		{
			$this->build();
		}
		else
		{
			include $this->script;
			$this->arrClasses = $arrClasses;
		}
	}

	public function load($class, $bolError = false)
	{
		if (isset($this->arrClasses[$class]))
		{
			include $this->arrClasses[$class];
		}
		elseif (! $bolError)
		{
			$this->build();
			$this->load($class, true);
		}
	}

	private function build()
	{
		$arrClasses = array();
		$arrClasses["Config"] = "Config.php";
		
		$arrOrdner = array();
		$arrOrdner[] = "class";
		$arrOrdner[] = "mvc/controller";
		$arrOrdner[] = "mvc/view";
		$arrOrdner[] = "mvc/model";
		foreach ($arrOrdner as $ordner)
		{
			$arrAllFiles = $this->read_all_files($ordner);
			$arrFiles = $arrAllFiles["files"];
			ksort($arrFiles);
			$arrClasses = array_merge($arrClasses, $arrFiles);
		}
		
		$string_representation = var_export($arrClasses, true);
		$php_code = '<?php ' . PHP_EOL . '$arrClasses = ' . $string_representation . ';' . PHP_EOL . '?>';
		$fh = fopen("autoload.php", "w");
		fputs($fh, $php_code);
		fclose($fh);
		
		$this->arrClasses = $arrClasses;
	}

	private function read_all_files($root = '.', $file_suffix = "", $bolRecursive = true)
	{
		$files = array('files' => array(), 'dirs' => array());
		$directories = array();
		$last_letter = $root[strlen($root) - 1];
		$root = ($last_letter == '\\' || $last_letter == '/') ? $root : $root . DIRECTORY_SEPARATOR;
		
		$directories[] = $root;
		
		while (sizeof($directories))
		{
			$dir = array_pop($directories);
			if ($handle = opendir($dir))
			{
				while (false !== ($file = readdir($handle)))
				{
					if ($file == '.' || $file == '..')
					{
						continue;
					}
					$file = $dir . $file;
					if (is_dir($file))
					{
						$directory_path = $file . DIRECTORY_SEPARATOR;
						if ($bolRecursive)
						{
							array_push($directories, $directory_path);
						}
						$files['dirs'][] = $directory_path;
					}
					elseif (is_file($file))
					{
						if ($file_suffix == "" || ($file_suffix != "" && strtolower(substr($file, strlen($file) - strlen($file_suffix))) == strtolower($file_suffix)))
						{
							$files['files'][basename($file, ".php")] = $file;
						}
					}
				}
				closedir($handle);
			}
		}
		
		return $files;
	}
}

?>