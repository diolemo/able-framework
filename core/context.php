<?php

class Context
{
   const ENV_DEVELOPMENT = E_ALL;
   const ENV_TESTING     = E_ERROR | E_WARNING | E_PARSE;
   const ENV_PRODUCTION  = 0;

   public static $cache;
   public static $conf;
   public static $db;
   public static $session;
}

?>