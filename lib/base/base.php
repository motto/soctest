<?php
namespace lib\base;
class Base
{
public static function set_lang($lang)
{
 if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
 {
     $brow=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
     if(in_array($brow,\CONF::$accepted_langT)){$lang=$brow ;}
 }
 if(isset($_SESSION['lang']) && in_array($_SESSION['lang'],\CONF::$accepted_langT)){$lang=$_SESSION['lang'];}
 if(isset($_POST['lang']) && in_array($_POST['lang'],\CONF::$accepted_langT)){$lang=$_POST['lang'];}
 if(isset($_GET['lang']) && in_array($_GET['lang'],\CONF::$accepted_langT)){$lang=$_GET['lang'];}
    $_SESSION['lang']=$lang;
    return $lang;
}
}
