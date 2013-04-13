<?php

// call the __init() method once all libs load
$__able_lib_callbacks[] = 'REQUEST::__init';

class Request
{
   public static $url;
   public static $remote_addr;
   public static $remote_port;   
   protected static $__source;
   
   public static function __init()
   {
      $_SERVER['REQUEST_PATH'] = explode('?', 
         $_SERVER['REQUEST_URI'])[0];
      
      self::$url = new URL();
      self::$url->scheme = $_SERVER['REQUEST_SCHEME'];
      self::$url->host = $_SERVER['HTTP_HOST'];
      self::$url->port = $_SERVER['SERVER_PORT'];
      self::$url->path = $_SERVER['REQUEST_PATH'];
      self::$url->raw_query = $_SERVER['QUERY_STRING'];
      self::$url->local = Request::__local_url();
      self::$url->build();
      
      $prefix = strstr(self::$url->url, self::$url->path, true);
      self::$url->base = $prefix . Context::$conf['base_url'];
      
      self::$remote_addr = $_SERVER['REMOTE_ADDR'];
      self::$remote_port = $_SERVER['REMOTE_PORT'];
      
      Request::$__source = &$_REQUEST;
      Post::$__source = &$_POST;
      Get::$__source = &$_GET;
   }
   
   // return the path after able root
   private static function __local_url() 
   {
      $base = Context::$conf['base_url'];
      $path = self::$url->path;      
      if (strpos($path, $base) !== 0)
         throw new Exception();      
      return substr($path, strlen($base));
   }
   
   // return request data (or set it)
   public static function & data($name = null, $value = null)
   {
      if ($name === null) return self::$__source;
      if ($value === null) return self::$__source[$name];
      self::$__source[$name] = $value;
      return self::$__source[$name];
   }
   
   // determine if request data is set ~ true
   public static function evaluate($name, $if_true = true)
   {
      if (!isset(self::$__source[$name])) return false;
      return self::$__source[$name] ? $if_true : false;
   }
   
   // determine if request data is set for <name>
   public static function has($name = null)
   {
      if ($name === null) return isset(self::$__source);
      return isset(self::$__source[$name]);
   }
   
   // return the part for $index 
   public static function param($index)
   {
      $url = new URL();
      $url->path = self::$url->local;
      return $url->param($index);
   }
   
   // sends redirect to url but does not exit
   // * $use_base indicates to prefix with base url
   public static function redirect($url = null, $use_base = false)
   {
      if ($url === null)
      {
         $url = Request::$url->url;
         $use_base = false;
      }
      
      if ($use_base === true)
         $url = Context::$conf['base_url'] . $url;
      header(sprintf('Location: %s', $url));
      return $url;
   }
}

class Post extends Request 
{
   protected static $__source;
}

class Get extends Request 
{
   protected static $__source;
}

?>