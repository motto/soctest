<?php
namespace app\admin\lib;
defined( '_MOTTO' ) or die( 'Restricted access' );

use lib\base\html\FeltoltS;
use lib\db\DBA;
use lib\mod\coin\Coin;
use mod\MOD;

class PAR
{
    public static $jog = 'admin.php';
    public static $ikonsor = array('pub','unpub');
    public static $view_file='app/admin.php/view/tabla_alap.html';
    public static $db_tabla = '';
    public static $view_mod = 'tabla';
}
class ADT
{
    public static $idT = array();
    public static $view = '';
    public static $view_item = ''; //ide kerül a generált táblázat
    public static $view_tmpl = '';
    public static $dataT = array();
    public static $LT = array();
    public static $view_to_view  = array();
    public static $data_szerk  = array();

}

/*
ADT::$datatomb_sql="SELECT u.username,t.tarca,t.tarcanev, SUM(p.satoshi) AS egyenleg FROM penztar p INNER JOIN userek u ON p.userid=u.id INNER JOIN tarcak t ON t.id=u.tarcaid  GROUP BY p.userid";
ADT::$ikonsor=array();

ADT::$tablanev='penztar';*/
ADT::$data_szerk =array
(
    array('cim'=>'Usernév','mezonev'=>'username','tipus'=>''),
    array('cim'=>'Tárca név','mezonev'=>'tarcanev','tipus'=>''),
    array('cim'=>'Tárca cím','mezonev'=>'tarca','tipus'=>''),
    array('cim'=>'Egyenleg','mezonev'=>'egyenleg','tipus'=>'')
);


class Admin
{


};