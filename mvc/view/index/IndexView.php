<?php

class IndexView extends View
{

	public function openHtml()
	{
		echo '<!DOCTYPE html>' . PHP_EOL;
		echo '<html>' . PHP_EOL;
		echo '<head>' . PHP_EOL;
		
		echo '<!-- META -->' . PHP_EOL;
		echo '<meta charset="utf-8" />' . PHP_EOL;
		echo '<meta name="author" content="azwick" />' . PHP_EOL;
		
		echo '<title>' . Config::TITLE . ' ' . Config::VERSION . '</title>' . PHP_EOL;
		
		echo '<!-- CSS -->' . PHP_EOL;
		echo '<link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.min.css">' . PHP_EOL;
		echo '<link rel="stylesheet" href="' . addModifiedTime('css/style.css') . '" />' . PHP_EOL;
		
		echo '<!-- jQuery -->' . PHP_EOL;
		echo '<script type="text/javascript" src="js/jquery/jquery.js"></script>' . PHP_EOL;
		
		echo '<!-- React -->' . PHP_EOL;
		echo '<script type="text/javascript" src="js/reactjs/react.min.js"></script>' . PHP_EOL;
		echo '<script type="text/javascript" src="js/reactjs/react-dom.min.js"></script>' . PHP_EOL;
		echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.24/browser.min.js"></script>' . PHP_EOL;
		
		echo '<!-- JS -->' . PHP_EOL;
		echo '<script type="text/babel" src="' . addModifiedTime('js/reactsql.js') . '"></script>' . PHP_EOL;
		
		echo '</head>' . PHP_EOL;
		echo '<body>' . PHP_EOL;
	}

	public function closeHtml()
	{
		echo '</body>' . PHP_EOL;
		echo '</html>';
	}
}

?>