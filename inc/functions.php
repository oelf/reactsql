<?php 

function echo_pre($array)
{
	echo '<pre>';
		print_r($array);
	echo '</pre>';
}

/**
 * F�gt zu der URL das �nderungsdatum hinzu
 * @param string $skript
 * @return string
 */
function addModifiedTime($skript)
{
	$mtime = filemtime($skript);
	return $skript . "?$mtime";
}

?>