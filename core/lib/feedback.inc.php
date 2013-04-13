<?php

class Feedback
{
   private static $queue = array();
   private static $has_callback = false;
   
   public static function read() 
   {
      if (defined('ABLE_TERMINATED')) return null;
      $feedback = Context::$session->read('able_feedback');
      if ($feedback === null) $feedback = array();
      $feedback = array_merge($feedback, self::$queue);
      if (count($feedback) === 0) return null;
      Context::$session->delete('able_feedback');
      self::$queue = array();
      return $feedback;
   }
   
   public static function set($feedback)
   {
      self::write($feedback);
   }
   
   public static function write($feedback) 
   {
      self::$queue[] = $feedback;
      
      if (self::$has_callback) return;
      self::$has_callback = true;
      
      Context::$session->on_commit(function($session) {
         $session->write('able_feedback', self::$queue);
      });
   }
}

?>