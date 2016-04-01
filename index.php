<?php
session_start();
define("DS", "/"); define("_MOTTO", "igen");

use  lib\db ;
use  lib\base as alap;

include 'def.php';

if(CONF::$offline=='igen'&& !GOB::get_userjog('admin.php'))
{die(CONF::$offline_message);}

db\Connect::connect();//GOB::$db-be létrehozza az adatbázis objektumot

$azon= new \lib\jog\Azonosit();
GOB::$userT=$azon::set_userdata($_SESSION['userid']);

GOB::set_userjog();
GOB::$lang=alap\Base::set_lang(GOB::$lang);

if(isset($_GET['ref'])){$_SESSION['ref']=$_GET['ref'];}

if(isset($_POST['app'])){CONF::$app=$_POST['app'];}
if(isset($_GET['app'])){CONF::$app=$_GET['app'];}
include_once 'app/'.CONF::$app.'/'.CONF::$app.'.php';

echo GOB::$html;