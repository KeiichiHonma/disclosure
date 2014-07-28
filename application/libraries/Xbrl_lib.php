<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Xbrl_lib
{
    var $ATTR = "_attributes";
    var $NS = "_namespace";
    var $VAL = "_value";
    function __construct()
    {
        $this->ci =& get_instance();
    }
    
    public function _parseXml($file="") {
        libxml_use_internal_errors(true);
        $doc = @simplexml_load_file($file, null, LIBXML_COMPACT|LIBXML_NOCDATA|LIBXML_NOBLANKS|LIBXML_NOENT);
var_dump($doc->xbrli);
die();
        if(!is_object($doc)) {
         $err["status"] = "Failed loading XML.";
         foreach(libxml_get_errors() as $error) {
            $err["error"][] = $error->message;
         }
         return $err;
        }
        $test = $doc->getDocNamespaces();
var_dump($test);
die();
        $ns = $data[$this->NS] = $doc->getDocNamespaces();
var_dump($ns);
die();
        if(!count($ns)) {
            $body = $this->_parseXmlLoop($doc, "", 1);
        }else {
            $ns = $this->_nameSpace($ns, 1);
            //_namespaceセット
            foreach($ns as $key=>$val) {
                $obj_attr = ($val) ? $doc->attributes($val) : $doc->attributes();
                if($obj_attr) {
                    $data[$this->ATTR.$key] = $this->_perseAttributes($obj_attr);
                }
            }

            $root = $doc->getName();

            $body[$root] = $data;
            $i = 0;
            foreach($ns as $key=>$val) {
                if($obj_data = $this->_parseXmlLoop($doc, $val)) {
var_dump($obj_data);
die();
                    $body[$root][$key] = $obj_data;
/*
                    if($key != 'xbrli' && $key != 'link'){
                        $body[$i][$root][$key] = $obj_data;
                        $i++;
                    }else{
                        $body[$root][$key] = $obj_data;
                    }
*/
                }
            }
        }
        return $body;
    }
/**********************************************************
Name Space.
*********************************************************/
    private function _nameSpace($array="") {
    if(!array_key_exists("", $array) || !in_array("", $array)) {
     $array[""] = "";
    }
    return $array;
 }
/**********************************************************
Perse Xml Attributes.
*********************************************************/
    private function _perseAttributes($attributes="") {
    foreach($attributes as $attr) {
     $data[(string)$attr->getName()] = (string)$attr;
    }
    return $data;
 }
/**********************************************************
Perse Xml Loop.
*********************************************************/
    private function _parseXmlLoop($doc="", $vals="", $not="") {
    if(!is_object($doc)) {
     return;
    }
    $ns = $this->_nameSpace($doc->getNamespaces(true));
    $vals_ = (!$vals) ? $doc->children() : $doc->children($vals);
    foreach($vals_ as $obj_key=>$obj_val) {
     if($not===1) {
        if($obj_attr = $doc->attributes()) {
         $body[$obj_key][$this->ATTR] = $this->_perseAttributes($obj_attr);
        }
     }
     $data = "";
     foreach($ns as $key=>$val) {
        if($obj_loop = $this->_parseXmlLoop($obj_val, $val)) {
         $data[$key] = $obj_loop;
        }
     }
     if(count($ns)===1) {
        $data = $data[""];
     }
     foreach($ns as $key=>$val) {
        $obj_attr = ($val) ? $obj_val->attributes($val) : $obj_val->attributes();
        if($obj_attr) {
         $data[$this->ATTR.$key] = $this->_perseAttributes($obj_attr);
        }
     }
     if((string)$obj_val && !is_array($obj_val)) {
        $data[$this->VAL] = (string)$obj_val;
     }
     $body[$obj_key][] = $data;
    }
    if(isset($body)) return $body;
 }


}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */