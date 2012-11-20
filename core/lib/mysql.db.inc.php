<?php

class mysql_db extends mysqli
{
   public function __construct($conf)
   {
      parent::init();
      parent::options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);

      extract($conf);

      // persistant connection
      if (PHP_SAPI !== 'cli')
        $host = sprintf('p:%s', $host);

      if (!(@$this->real_connect($host, $user, $pass, $name, $port)))
        throw new Exception("mysqldb: database connect failed");
   }

   // output error
   public function error()
   {
      echo $this->error;
   }

   // format a date/time into timestamp
   public function timestamp($date)
   {
      return date('Y-m-d H:i:s', strtotime($date));
   }
   
   // return last auto id
   public function insert_id()
   {
      $row = $this->row('select last_insert_id() as id');
      if ($row !== null) return $row['id'];
      return null;
   }
   
   // executes a raw sql query and allows 
   // for the fetching of rows 1 by 1
   public function raw_exec($sql, $prepare_args=null)
   {
      $stmt = $this->prepare($sql);
      
      if ($this->error)
        trigger_error($this->error, E_USER_WARNING);

      if (!$stmt)
        return null;

      if ($prepare_args !== null)
        call_user_func_array(array($stmt, 'bind_param'), $prepare_args);
        
      if ($this->error)
        trigger_error($this->error, E_USER_WARNING);
        
      $exec = $stmt->execute();

        if ($this->error)
          trigger_error($this->error, E_USER_WARNING);

      if (!$exec)
        return null;
      
      $meta = $stmt->result_metadata();
      
      if ($meta)
      {
         $fields = $meta->fetch_fields();
         
         $bind_result_args = array();
         $row = array();      
   
         foreach ($fields as $field)
         {
            $row[($field->name)] = null;
            $bind_result_args[] = &$row[($field->name)];
         }
      
         // bind results to $result_row elements
         call_user_func_array(array($stmt, 'bind_result'), $bind_result_args);
         
         // construct handle as array of the required variables
         return array('meta' => &$meta, 'row' => &$row, 'stmt' => &$stmt);
      }
      
      $rows = $stmt->affected_rows;
      $stmt->close();
      
      return $rows;
   }
   
   public function raw_read($handle)
   {
      // case when we don't receive expected array
      if (!isset($handle['meta'])) return null;
      if ($handle['stmt']->fetch() !== true) return null;
      
      $row = array();
      
      // we have to copy as the elements
      // are references to fixed variables
      foreach ($handle['row'] as $k => $v)
        $row[$k] = $v;
      
      return $row;
   }
   
   public function raw_close($handle)
   {
      // close the meta and statement
      if (isset($handle['meta']))
        $handle['meta']->free_result();          
      $handle['stmt']->close();
   }
   
   // fetch a limited number of rows (or all) as array
   public function call($sql, $prepare_args=null, $limit=PHP_INT_MAX)
   {
      $handle = $this->raw_exec($sql, $prepare_args);
      
      // return affected rows
      if (!isset($handle['meta']))
        return $handle;
      
      $fetched = 0;
      $rows = array();
   
      // loop over reading rows into an array
      while (($row = $this->raw_read($handle)) !== null 
      && $fetched++ < $limit)
      {
         $rows[] = $row;
      }
      
      $this->raw_close($handle);
      
      if (count($rows) == 0) 
        return null;
      
      return $rows;
   }

   // fetch all rows as array
   public function all($sql, $prepare_args=null)
   {
      return $this->call($sql, $prepare_args);
   }

   // fetch single row as array
   public function row($sql, $prepare_args=null)
   {
      $result = $this->call($sql, $prepare_args, 1);
      
      if (is_array($result) && isset($result[0]))
        return $result[0];
         
      return null;
   }
   
   // fetch single field from single row
   public function field($sql, $field, $prepare_args=null)
   {
      $result = $this->call($sql, $prepare_args, 1);
      
      if (is_array($result) && isset($result[0]))
        return $result[0][$field];

      return null;
   }
}

?>