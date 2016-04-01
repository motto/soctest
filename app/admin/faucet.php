<?php
include_once'app/admin.php/lib/tablas_alap.php';
    ADT::$jog='admin.php';
    ADT::$view_file='app/admin.php/view/tabla_alap.html';
    ADT::$urlap_file ='app/admin.php/view/faucet_urlap.html';
    ADT::$datatomb_sql="SELECT * FROM faucet ORDER BY id DESC ";

    ADT::$tablanev='faucet';
    ADT::$tabla_szerk =array(
array('cim'=>'','mezonev'=>'','tipus'=>'checkbox'),
array('cim'=>'','mezonev'=>'pub','tipus'=>'pubmezo'),
array('cim'=>'id','mezonev'=>'id','tipus'=>''),
array('cim'=>'Webcím','mezonev'=>'link','tipus'=>''),
array('cim'=>'Megjegyzés','mezonev'=>'megjegyzes','tipus'=>''),
array('cim'=>'Perc','mezonev'=>'perc','tipus'=>''),
array('cim'=>'Pont','mezonev'=>'pont','tipus'=>'')
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

$app=new Admin();
$fn=TASK_S::get_funcnev($app);
ADT::$datasor_sql="SELECT * FROM faucet WHERE id='".ADT::$id."' ";

$app->$fn();



