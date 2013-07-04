<?php

// the base directory of the able framework
define('ABLE_BASE_DIR', realpath(sprintf('%s%s..', 
   dirname(__FILE__), DIRECTORY_SEPARATOR)));

// a value that means use the default
define('ABLE_DEFAULT', '__able_default__');

set_include_path(ABLE_BASE_DIR);
chdir(ABLE_BASE_DIR);

require('core/version.php');
require('core/context.php');
require('core/conf.defaults.php');
require('core/conf.php');
require('core/lib.php');

Context::$db = new MySQL_Database(Context::$conf['database']);
Context::$cache = new DataCache(Context::$conf['cache']);
Context::$session = Session::start();

require('core/content.php');
require('core/auth.php');

register_shutdown_function(function() 
{ 
   chdir(ABLE_BASE_DIR);
   Content::__render();
   Session::__commit();
   Content::__trim();
});

require('core/local.php');

// Auth::__check_no_auth();
// Auth::__check_auth_conditions();

?>