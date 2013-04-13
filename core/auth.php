<?php

class Auth
{
   private static $__allow_no_auth = array();
   private static $__auth_conditions = array();
   public static $user = null; // user account
   
   // determines whether an anon user can load
   // the current url based on url patterns
   public static function __check_no_auth()
   {
      if (defined('ABLE_NO_AUTH')) return;
      if (self::$user !== null) return;
      
      for ($i = 0, $c = count(self::$__allow_no_auth); $i < $c; $i++)
      {
         $pattern = self::$__allow_no_auth[$i];
         if (preg_match($pattern, Request::$url->local))
            return;
      }
      
      self::not_authorized();
   }
   
   // determines whether the current user
   // has permission to access the current page
   // using url patterns and callbacks
   public static function __check_auth_conditions()
   {      
      for ($i = 0, $c = count(self::$__auth_conditions); $i < $c; $i++)
      {
         $pattern = self::$__auth_conditions[$i]['pattern'];
         if (preg_match($pattern, Request::$url->local))
         {
            $callback = self::$__auth_conditions[$i]['callback'];
            if (call_user_func($callback, self::$user) === false)
               self::not_authorized();
         }
      }
   }
   
   // allow anon access to urls matching this pattern
   public static function allow_no_auth($pattern=null)
   {
      self::$__allow_no_auth[] = $pattern;
   }
   
   // add a condition for urls matching $pattern such 
   // that if the result of $callback is false then 
   // the current user is not authorized to continue
   public static function add_auth_condition($pattern=null, $callback)
   {
      self::$__auth_conditions[] = array(
         'pattern' => $pattern, 'callback' => $callback);
   }
   
   // show a not authorized screen
   public static function not_authorized()
   {
      die(require(Context::$conf['no_auth_file']));
   }
   
   // quick function to check for 
   // the presence of an account
   public static function test() 
   {
      return self::$user !== null;
   }
   
   // quick function to check 
   // for logged in user
   public static function login($user) 
   {
      self::$user = $user;
   }
   
   // quick function to check 
   // for logged in user
   public static function logout() 
   {
      self::$user = null;
   }
}

Auth::$user = Context::$session->read('able_user');
Context::$session->on_commit(function($session) {
   $session->write('able_user', Auth::$user);
});

?>
