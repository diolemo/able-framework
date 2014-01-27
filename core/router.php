<?php

$sections = array();
while (($section = Request::section(count($sections))) !== null)
	$sections[] = $section;
$slash = DIRECTORY_SEPARATOR;

if (!count($sections) && is_file('index.php'))
	return 'index.php';

for ($klen = count($sections); $klen > 0; $klen--)
{
	$ksections = array_slice($sections, 0, $klen);
	$path = implode($slash, $ksections);
	
	// sections lead to folder with index
	if (is_file("{$path}{$slash}index.php"))
		return "{$path}{$slash}index.php";
	
	// sections lead to file
	if (is_file("{$path}.php"))
		return "{$path}.php";
	
	// classic in-root files
	$path = implode("_", $ksections);
	if (is_file("{$path}.php"))
		return "{$path}.php";
}

return null;
	
?>