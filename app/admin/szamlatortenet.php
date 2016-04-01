<?php
include_once'app/admin.php/lib/tablas_alap.php';
/*require_once('vendor/autoload.php');
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;*/



ADT::$jog='admin.php';
ADT::$ikonsor=array('pub','unpub');
ADT::$view_file='app/admin.php/view/tabla_alap.html';
//ADT::$urlap_file ='app/admin.php/view/faucet_urlap.html';
ADT::$datatomb_sql="SELECT u.username,p.tr_cim,p.satoshi,p.megjegyzes,p.ido FROM penztar p INNER JOIN userek u ON u.id=p.userid ";
ADT::$ikonsor=array();

ADT::$tablanev='penztar';
ADT::$tabla_szerk =array(
    //  array('cim'=>'','mezonev'=>'','tipus'=>'checkbox'),
    // array('cim'=>'','mezonev'=>'pub','tipus'=>'pubmezo'),
   // array('cim'=>'Tárcanév','mezonev'=>'id','tipus'=>''),
    array('cim'=>'Usernév','mezonev'=>'username','tipus'=>''),
    array('cim'=>'Tranzakio','mezonev'=>'tr_cim','tipus'=>''),
    //array('cim'=>'Tárca cím','mezonev'=>'tarca','tipus'=>''),
    array('cim'=>'Staoshi','mezonev'=>'satoshi','tipus'=>''),
   // array('cim'=>'Satoshi','mezonev'=>'uj','tipus'=>''),
   array('cim'=>'Dátum','mezonev'=>'ido','tipus'=>'')
);
ADT::$ment_mezok =array(
    array('mezonev'=>'link'),
    //array('mezonev'=>'','postnev'=>'','ell'=>'','tipus'=>''),
    array('mezonev'=>'leiras','tipus'=>'text'),
    array('mezonev'=>'megjegyzes'),
    array('mezonev'=>'perc'),
    array('mezonev'=>'pont'));


class Admin extends Admin{


};

//if(isset($_POST['sor'])){print_r($_POST['sor']);}

$app=new Admin();
$fn=TASK_S::get_funcnev($app);
//ADT::$datasor_sql="SELECT * FROM faucet WHERE id='".ADT::$id."' ";
ADT::$datatomb=DB::assoc_tomb(ADT::$datatomb_sql);
//echo ADT::$datatomb_sql;
//print_r(ADT::$datatomb);
$app->$fn();