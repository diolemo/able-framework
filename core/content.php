<?php

ob_start(); // trim
ob_start(); // render

class Content
{
   public static $__auto_render = true;
   public static $__auto_trim = true;
   public static $__captured = array();
   
   private static $__active_captures = array();   
   private static $__content;
   private static $__type_set = false;
   
   public static function __render() 
   {
      // end all open captures
      while (count(self::$__active_captures) > 0)
         self::end();
         
      if (self::$__auto_render === true) 
         self::render();
      ob_end_flush();
   }
   
   public static function __trim() 
   {
      if (self::$__auto_trim !== true) 
         return ob_end_flush();
      
      $out = ob_get_contents();
      $out = trim($out);
      ob_end_clean();
      echo $out;
   }
   
   public static function esc($content)
   {
      if ($content === null) return;
      return htmlspecialchars($content, ENT_QUOTES);
   }
   
   public static function render()
   {
      if (self::$__type_set === false)
         self::mime(Context::$conf['mime_type']);
      
      self::$__content = ob_get_contents();
      ob_clean();
      
      extract(self::$__captured);      
      require(Context::$conf['template']);
   }
      
   public static function mime($type, $encoding = ABLE_DEFAULT) 
   {
      if ($encoding === null || $encoding === false) 
         return self::mime_bin($type);
      
      if ($encoding === ABLE_DEFAULT)
         $encoding = Context::$conf['encoding'];
      
      $header = 'Content-Type: %s; charset=%s';
      $header = sprintf($header, $type, $encoding);
      self::$__type_set = true;
      header($header);
   }
   
   public static function mime_bin($type) 
   {
      $header = 'Content-Type: %s';
      $header = sprintf($header, $type);
      self::$__type_set = true;
      header($header);
   }
   
   // capture content from file
   public static function capture_file($file, $name = ABLE_DEFAULT)
   {
      ob_start();
      require($file);
      $out = ob_get_contents();
      self::$__captured[$name] = $out;
      ob_end_clean();
      return $out;      
   }
   
   // start buffer to capture content
   public static function capture($name = ABLE_DEFAULT, $value = null)
   {
      if ($value !== null)
         return self::$__captured[$name] = $value;
      
      array_push(self::$__active_captures, $name);
      ob_start();
   }
   
   // end buffer 
   public static function end()
   {
      $out = ob_get_contents();
      $name = array_pop(self::$__active_captures);
      self::$__captured[$name] = $out;
      ob_end_clean();
      return $out;
   }
}

?>
