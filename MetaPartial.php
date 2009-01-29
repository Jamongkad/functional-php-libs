<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
*
* The MetaPartial Class
* 
* This class is taken directly from the Functional PHP Extension Library.
*
* @package MetaPartial Class
* @category Libraries
* @author Mathew Wong aka Jamongkad
*
*/
class MetaPartial{
    private $values = array();
    private $func;

    function __construct($func = false, $args = false) {
        if(is_array($args)):
         $this->values = array_shift($args);     
        else: 
         $this->values = $args; 
        endif;
        $this->func = $func;
    }

    function method($str) {
        $args = func_get_args();
        return call_user_func_array($this->func, array_merge($args, $this->values));
    }
}

function mP() {
 $args = func_get_args();   
 $func = $args[0];
 $p = new MetaPartial($func, array_slice($args,1));
 return array($p, 'method');
}
?>
