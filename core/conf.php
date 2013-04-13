<?php

// the data cache connection info
Context::$conf['cache'] = array(

   'host' => '127.0.0.1',              // memcache server hostname
   'port' => 11211,                    // memcache connection port (default: 11211)

);

// the database connection info
Context::$conf['database'] = array(

   'host' => '127.0.0.1',              // database server hostname
   'name' => 'database_name',          // database name
   'user' => 'database_user',          // database username
   'pass' => 'database_pass',          // database password
   'port' => 3306,                     // database connection port (default: 3306)

);

?>
