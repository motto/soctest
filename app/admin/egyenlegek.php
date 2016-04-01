<?php
namespace app\admin;
defined( '_MOTTO' ) or die( 'Restricted access' );

use lib\html\FeltoltS;
use lib\db\DBA;
use mod\MOD;

class PAR
{
    public static $jog = 'admin.php';
    public static $app_nev = 'egyenlegek';
    public static $LT_forras = 'ADT'; // vagy: SQL
    public static $ikonsor = array();
    public static $view_file='app/admin.php/view/tabla_alap.html';
    public static $db_tabla = 'penztar';
    public static $view_mod = 'tabla';
    public static $alap_sql = '';
}
class ADT
{
    public static $idT = array();
    public static $view = '';
    public static $dataT = array();
    public static $LT = array();
    public static $data_szerk  = array();
    public static $hibaT  = array();
}


PAR::$alap_sql="SELECT u.username,t.tarca,t.tarcanev, SUM(p.satoshi) AS egyenleg FROM penztar p INNER JOIN userek u ON p.userid=u.id INNER JOIN tarcak t ON t.id=u.tarcaid  GROUP BY p.userid";

ADT::$data_szerk =array
(
 array('cim'=>'Usernév','mezonev'=>'username','tipus'=>''),
 array('cim'=>'Tárca név','mezonev'=>'tarcanev','tipus'=>''),
 array('cim'=>'Tárca cím','mezonev'=>'tarca','tipus'=>''),
 array('cim'=>'Egyenleg','mezonev'=>'egyenleg','tipus'=>'')
);
class Data
{
    public static function alap()
    {
        ADT::$dataT=\lib\db\DB::assoc_tomb(PAR::$alap_sql);
       // if(PAR::$LT_forras=='SQL'){ADT::$LT= MOD::lt_fromdb;}
    }
}
class View
{
    public static function alap()
    {
        ADT::$view=file_get_contents(PAR::$view_file, true);
    }
}
class Admin
{
public function alap()
{
    Data::alap();
    View::alap();
    $view_func=PAR::$view_mod;
    $admin_tab=MOD::$view_func(ADT::$data_szerk,ADT::$dataT);
    ADT::$view=ADT::$view=str_replace('<!--|tabla|-->',$admin_tab,ADT::$view );

}

};
