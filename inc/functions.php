<?php 

function echo_pre($array)
{
	echo '<pre>';
		print_r($array);
	echo '</pre>';
}

/**
 * Fügt zu der URL das Änderungsdatum hinzu
 * @param string $skript
 * @return string
 */
function addModifiedTime($skript)
{
	$mtime = filemtime($skript);
	return $skript . "?$mtime";
}

?>