<?php
namespace app\admin;
use lib\html\FeltoltS;
//include_once 'app/admin.php/lib/admin_func.php';
//include_once 'lib/ell_conv.php'; //elenőrző convertáló class
defined( '_MOTTO' ) or die( 'Restricted access' );
\GOB::$html=file_get_contents('tmpl/flat/base.html', true);

$fget='user';
if(isset($_GET['fget'])){$fget=$_GET['fget'];}
if(isset($_POST['fget'])){$fget=$_POST['fget'];}

switch ($fget) {

    case 'home':

        break;
    default:
        include_once 'app/admin/'.$fget.'.php';
}
$app=new Admin();$fn='alap';
if(isset($_GET['task'])){$fn=$_GET['task'];}
if(isset($_POST['task'])){$fn=$_POST['task'];}
if(!\GOB::get_userjog(PAR::$jog)){$fn='joghiba';}
$app->$fn();


ADT::$view=str_replace('<!--|hiba|-->', ADT::$hiba, ADT::$view );
\GOB::$html= str_replace('<!--|tartalom|-->',ADT::$view,\GOB::$html);
//\GOB::$html=FeltoltS::mod(\GOB::$html);
\GOB::$html=FeltoltS::LT(\GOB::$html,ADT::$LT);
\GOB::$html=FeltoltS::data(\GOB::$html,ADT::$dataT);
\GOB::$html=FeltoltS::tisztit(\GOB::$html);
\GOB::$html= str_replace('<!--refid-->',\GOB::$userT['id'],\GOB::$html);
//echo \GOB::$html;

?>