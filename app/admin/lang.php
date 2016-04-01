<?php
namespace app\admin;
defined( '_MOTTO' ) or die( 'Restricted access' );

use app\admin\lib\trt\joghiba;
use lib\html\FeltoltS;
use lib\db\DBA;
use mod\MOD;
class PAR
{
    public static $jog = 'noname';
    public static $app_nev = 'lang';
    public static $LT_forras = 'ADT'; // vagy: SQL
    public static $view_file='app/admin/view/tabla_alap.html';
    public static $urlap_file='app/admin/view/urlapok/lang.html';
    public static $db_tabla = 'lt_txt';
    public static $alap_sql = ''; //ha üres a Data:alap állítja elő
    public static $ikonsorT =
           ['szerk'=>'edit',
            'uj'=>'plus',
            'torol'=>'trash',
            'def'=>'asterisk'];
    public static $tabla_paramT  = [];
    public static $data_szerkT  =
    [
        ['cim'=>'Label','mezonev'=>'label','ell'=>''],
        ['cim'=>'Tárca név','mezonev'=>'tarcanev','conv'=>''],
        ['cim'=>'Tárca cím','mezonev'=>'tarca'],
        ['cim'=>'Egyenleg','mezonev'=>'egyenleg','tipus'=>'']
    ];
}
class ADT
{
    public static $adminapp = ''; //ehhez az apphoz tartozó rekordokat kérdezi le
    public static $langT = '';  //az elérhető nyelvek listája
    public static $idT = array();
    public static $view = '';
    public static $dataT = array();
    public static $LT = array();
    public static $hiba  = '';//hibauzenet
}


PAR::$alap_sql="SELECT * FROM ".PAR::$db_tabla;

class Data
{
    public static function ment()
    {
        if(isset(ADT::$idT[0]))
        {
            $res=DBA::frissit_postbol(PAR::$db_tabla,ADT::$idT[0],PAR::$data_szerkT);
        }else
        {
            $res=DBA::beszur_postbol(PAR::$db_tabla,PAR::$data_szerkT);
        }
        return $res;
    }

    public static function alap()
    {
       // ADT::$dataT=\lib\db\DB::assoc_tomb(PAR::$alap_sql);
        $whereszo='WHERE';$where='';
        if(isset(ADT::$adminapp))
        {
            $where=$whereszo." app='" .ADT::$adminapp."'";
            $whereszo='AND';
        }
        if(isset(ADT::$adminapp))
        {
            $where=$whereszo." verse='default'";
        }

        $alap_sql="SELECT * FROM ".PAR::$db_tabla.$where;
        ADT::$dataT=\lib\db\DB::assoc_tomb($alap_sql);
        print_r(ADT::$dataT);
    }
}
class View
{
    public static function alap()
    {
        ADT::$view=file_get_contents(PAR::$view_file, true);
    }
    public static function szerk()
    {
        ADT::$view=file_get_contents(PAR::$urlap_file, true);
    }
}
class Admin
{
 use joghiba;
    public function __construct()
    {
        $_SESSION['applang']=\GOB::$lang;
        if(isset($_GET['applang'])){$_SESSION['applang']=$_GET['applang'];}
        if(isset($_POST['applang'])){$_SESSION['applang']=$_POST['applang'];}
        if(isset($_POST['id'])){ADT::$idT[]=$_POST['id'];}
        if(isset($_POST['sor'])){ADT::$idT=$_POST['sor'];}
        if(isset($_POST['adminapp'])){ADT::$adminapp=$_POST['adminapp'];}

    }
    public function ment()
    {
      Data::ment();
      $this->alap();
    }
    public function mentadduj()
    {
        Data::ment();
        $this->uj();
    }
    public function uj()
    {
        View::szerk();
       // ADT::$dataT['id']=ADT::$idT[0];
        ADT::$dataT['lang']=$_SESSION['applang'];
    }
    public function alap()
    {
        Data::alap();
        View::alap();
        $ikonsor=MOD::ikonsor(PAR::$ikonsorT);
        $admin_tab=MOD::tabla(PAR::$data_szerkT,ADT::$dataT);
        ADT::$view=ADT::$view=str_replace('<!--|ikonsor|-->',$ikonsor,ADT::$view );
        ADT::$view=ADT::$view=str_replace('<!--|tabla|-->',$admin_tab,ADT::$view );

    }

};