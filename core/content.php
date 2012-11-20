<?php

class Content
{
   public static $__auto_render = true;
   public static $__template_file;   
   public static $title;
   public static $head;

   public static function esc($content)
   {
      return htmlspecialchars($content, ENT_QUOTES);
   }
   
   public static function render()
   {
      if (Content::$__auto_render === false) return;
      
      $content = ob_get_contents();
      $title = Content::$title;
      $head = Content::$head;
      ob_end_clean(); 
      
      require(Content::$__template_file);
   }
}

?>