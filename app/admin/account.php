<?php
include_once'app/admin.php/lib/tablas_alap.php';
ADT::$jog='user'; //csak akkor akkor számít ha TASK_S::get_funcnev-et használunk
ADT::$ikonsor=array();
ADT::$view_file='app/admin.php/view/tabla_alap.html';
//ADT::$urlap_file ='app/admin.php/view/faucet_urlap.html';
ADT::$datatomb_sql="SELECT tr_cim,satoshi,megjegyzes,ido   FROM penztar WHERE userid='".GOB::$user['id']."'";
ADT::$ikonsor=array();
ADT::$tablanev='penztar';
ADT::$tabla_szerk =array(
    array('cim'=>'Tr','mezonev'=>'tr_cim','tipus'=>''),
    array('cim'=>'Satoshi','mezonev'=>'satoshi','tipus'=>''),
    array('cim'=>'Comment','mezonev'=>'megjegyzes','tipus'=>''),
    array('cim'=>'Date','mezonev'=>'ido','tipus'=>'')

);


class Admin extends Admin{

};

$app=new Admin();
//$fn=TASK_S::get_funcnev($app); //jogosutság  miatt kell
if($_SESSION['userid']>0)
{
    $tartalom=$app->alap();
}
else
{
    $tartalom=MOD::login();
}
