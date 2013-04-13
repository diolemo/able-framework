<?php

class URL
{
   protected $__parsed = false;
   
   public $fragment = null;
   public $host = null;
   // able-relative url
   public $local = null;
   public $pass = null;
   public $path = null;
   public $port = null;
   public $query = null;
   public $raw_query = null;
   public $scheme = null;
   // entire url
   public $url = null;
   public $user = null;
   
   public static function __parse(&$url)
   {
      $instance = new self($url, true);
      return $instance;
   }
   
   public function __construct(&$url = null, $parse = false)
   {
      $this->url = $url;
      if (!$parse) return;
      $this->parse();
   }
   
   public function build()
   {
      $bits = (array) $this;
      $bits['query'] = $bits['raw_query'];
      $this->url = http_build_url($bits);
      return $this->url;
   }
   
   public function parse($url = null)
   {
      if ($url === null && $this->__parsed) return $this;
      if ($url !== null) $this->url = $url;
      $bits = parse_url($this->url);
            
      $this->fragment   = $bits['fragment'];
      $this->host       = $bits['host'];
      $this->pass       = $bits['pass'];
      $this->path       = $bits['path'];
      $this->port       = $bits['port'];
      $this->raw_query  = $bits['query'];
      $this->scheme     = $bits['scheme'];
      $this->user       = $bits['user'];
      
      if ($this->raw_query !== null)
         parse_str($this->raw_query, $this->query);    
      
      $this->__parsed = true;           
      return $this;
   }
   
   // url can be divided into parts
   // at each slash and this will return
   // the part at the given index
   public function param($index)
   {
      $params = explode('/', $this->path);
      if (strlen($params[0]) === 0)
         array_shift($params);
      if (!isset($params[$index])) return null;
      return $params[$index];
   }
   
}

?>