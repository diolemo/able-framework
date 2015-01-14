<?php

function route_request($route)
{
	if (ABLE_DIRECT) return;
	require($route);
}

// direct request so just return
if (ABLE_DIRECT) return;

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
	$cpath = implode("_", $ksections);
	if (is_file("{$cpath}.php"))
		return "{$cpath}.php";
	
	// the path for html files
	$base = Context::$conf['content_dir'];
	
	// sections lead to folder with index
	if (is_file("{$base}/{$path}{$slash}index.html"))
		return "{$base}/{$path}{$slash}index.html";
	
	// sections lead to file
	if (is_file("{$base}/{$path}.html"))
		return "{$base}/{$path}.html";
	
	// classic in-root files
	$cpath = implode("_", $ksections);
	if (is_file("{$base}/{$cpath}.html"))
		return "{$base}/{$cpath}.html";
}

return 404;
	
?>
