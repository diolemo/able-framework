<?php 

define('ABLE_NO_AUTH', true);
require_once('init.php');

if (Context::$conf['content_dir'] !== null)
{
   $path = str_replace('/', '_', Request::$url->local);
   $file_pattern = sprintf('%s/%s.html', 
      Context::$conf['content_dir'], $path);   
   if ($files = glob($file_pattern))
      return require($files[0]);
}

Content::$__auto_render = false;
header('HTTP/1.0 404 Not Found');
if (Context::$conf['error_doc_404'] !== null)
   die(require(Context::$conf['error_doc_404']));

?>
<!doctype html>
<html>
   <head>
      <title>File Not Found</title>
   </head>
   <body>
      <h1>File Not Found</h1>
   </body>
</html>
