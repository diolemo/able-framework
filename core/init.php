<?php

require('context.php');

require('conf.php');
require('content.php');
require('lib/mysql.db.inc.php');

Context::$db = new mysql_db(Context::$conf['database']);
Content::$__template_file = 'html/template.php';

ob_start();

register_shutdown_function(function() 
{ 
   chdir(dirname($_SERVER['SCRIPT_FILENAME']));
   Content::render();    
});

?>