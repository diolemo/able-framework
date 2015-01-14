<?php

date_default_timezone_set('UTC');

// the base directory of the able framework
define('ABLE_BASE_DIR', realpath(sprintf('%s%s..', 
	dirname(__FILE__), DIRECTORY_SEPARATOR)));

// a value that means use the default
define('ABLE_DEFAULT', '__able_default__');

set_include_path(ABLE_BASE_DIR);
chdir(ABLE_BASE_DIR);

require('core/utf8_safe.php');
require('core/version.php');

require('core/context.php');
require('core/conf.defaults.php');
require('core/conf.php');
require('core/content.php');
require('core/lib.php');

Content::__init_buffers();
Context::$db = new MySQL_Database(Context::$conf['database']);
Context::$cache = new DataCache(Context::$conf['cache']);
// Context::$session = Session::start();

require('core/auth.php');
require('core/local.php');

register_shutdown_function(function() 
{ 
	chdir(ABLE_BASE_DIR);
	Content::__render();
	Session::__commit();
	Content::__trim();
});

$route = require('core/router.php');
if ($route === null) 
	return require('404.php');

require('core/auth.check.php');
require($route);

?>