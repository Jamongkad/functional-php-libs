<?php if(!defined('BASEPATH')) die ('No direct script access allowed');
/** 
* TupleFish Map Class
* 
* This class allows the use of higher order functions 
* to create more expressive functions.
*
* @package		TupleFish_0.2.1
* @category	        Libraries
* @author		Mathew Wong aka Jamongkad
*/
class Map {

   private $function; 
   private $proc;
   private $join;
   private $main_class;
   private $arguments;
   private $log;
   private $sql;
   private $var;   
   private $metameta;   
   private $lambda;

   public function __construct() {
     $this->metameta = new MetaPartial();

     if(!class_exists('Lambda')) 
      $this->lambda = new Lambda();      
   }

   //For the use of proc functions
   public function proc($func = null) {
     $this->proc = $func;   
     return $this;
   }
    
   //Function that will grab function parameters then will construct
   //a sentence when compiled by join for metapartial.
   public function args() {
     $args = func_get_args();
     if(isset($args)) {
      $this->arguments = $args;     
     } 
     return $this;
   }
    
   public function iteration_array(array $array = array()) { 
     $this->sql = $array;  
     return $this;
   }

   //Function that takes a list or an array in PHP's case
   //and maps it to a function.
   public function join($sql = null) {
    //Check if main class is set. But requires no additional args.
    if(isset($sql)) {
     $this->iteration_array($sql);     
    }
    
    if(isset($this->proc) and empty($this->arguments)) {
      $var = array_map( $this->proc,$this->sql ); 
      $this->log_msg('procedure: <b>set</b> <br/> args: <b>no arguments</b> <br/> join result: <b>compiled to string</b> ');
      $this->_reset();
      return $this->concat($var); 
    } 
     
    if(isset($this->proc) and isset($this->arguments)) {    
     $var = array_map( mP( $this->proc ,$this->arguments ) ,$this->sql ); 
     $this->log_msg('procedure: <b>set</b> <br/> args: <b>set</b> <br/> join result: <b>compiled to string</b>');
     $this->_reset();
     return $this->concat($var); 
    }
  } 
  
  //Function that takes a list or an array and returns the values
  //store in an array.
  public function compile($sql) {
     if(isset($sql)) {
     $this->iteration_array($sql);     
    }  

   if(isset($this->proc) and empty($this->arguments)) {
      $var = array_map( $this->proc,$this->sql ); 

      $this->log_msg('procedure: <b>set</b> <br/> args: <b>no arguments</b> <br/> join result: <b>compiled to array<b/>');
      $this->_reset();
      return $var; 
    } 
     
    if(isset($this->proc) and isset($this->arguments)) {    
     $var = array_map( mP( $this->proc,
                           $this->arguments ),
                           $this->sql ); 
     $this->log_msg('cprocedure: <b>set</b> <br/> args: <b>set</b> <br/> join result: <b>compiled to array</b>');
     $this->_reset();
     return $var; 
    }

  }

   //Reset function called when join compiles arguments and procedure
   //calls.
   private function _reset() {
    $reset = array(  
           'proc' => null,
           'join' => null, 
           'arguments' => null, 
           'log' => null,
           'sql' => null,
           'var' => null,
         );
    $this->_run_reset($reset);

   }
   
   private function _run_reset($reset_items) {
     foreach($reset_items as $item => $value) {
       $this->$item = $value;   
     }  
   }

   //Log message for debugging.
   private function log_msg($msg) { 
   
     if( $this->log  ) 
      echo tag("function: ".
                (($this->proc) ? tag($this->proc,'b') : null) 
              ,"div").
           tag("array: ".
                (($this->sql) ? tag('set and is valid array','b') : null)
              ,'div').
           tag($msg,"div").br(1);
    
   }
   
   //debug method to active shallow stack trace.
   public function trace($cond = null) { 
     $this->_reset();  

     if(isset($cond)) {
      $this->log = $cond;              
     }
     return $this;
   }

   private function concat($arr, $sep="") {
      $out = implode($sep, $arr);
      if (count($arr)) $out .= $sep;
      return $out;
   }
}

//Another cool minty function that allows users to supply a callback instantly 
//withought invoking the proc function.
function xM($callback) {
 $mappers = new Map();
 return $mappers->proc($callback);
}

?>
