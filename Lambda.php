<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* The Lambda Class
* 
* This class is taken directly from the Functional PHP Extension Library.
*
* @package MetaPartial Class
* @category Libraries
* @author Mathew Wong aka Jamongkad
*
*/
class Lambda {

 private $args ,$expr;

 function __construct($args = false,$expr = false) {
   $this->args = $args;
   $this->expr = $expr;
 }

 // Several places we need to make up unique function names.
 // gensym() formalizes the process and makes it reusable.
 function gensym() {
  static $counter;
  $counter ++;
  return "__lambda_$counter";
 }

 //This has been slightly changed to NOT suppress errors
 function def_fun($name, $args, $code) {
  eval( "function $name ($args) { $code }" );
   if (!function_exists($name)) die(
     "<pre>Failed to define function:
        \n\nfunction $name ($args) {\n$code\n}\n\n</pre>\n\n"
   );
 }

 function evaluate() {
   $name = $this->gensym();
   $this->def_fun($name, $this->args, "return $this->expr;");
   return $name;
  }
}

function lam() {
 $args = func_get_args();
 $p = new Lambda($args[0],$args[1]);
 return $p->evaluate();
}

function fx($body) {
 return lam('$x', $body);
}

?>
