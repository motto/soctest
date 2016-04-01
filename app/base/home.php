<?php
namespace lib\base;
use lib\db\DB;

defined( '_MOTTO' ) or die( 'Restricted access' );
class PAR
{
    public static $jog="noname";
    public static $view_file='';
    public static $sql='';
    public static $lt_sql=false;
}
class ADT
{
    public static $task='alap';
    public static $view="Nincs tartalom";
    public static $dataT=array();
    public static $LT=array();
};
//PAR::$sql="SELECT nev,".\GOB::$lang." FROM lng WHERE lap='nyito'";
class View
{
    static public function alap()
    {
        ADT::$view=file_get_contents(PAR::$view_file, true);
    }
}
class Data
{
    static public function alap()
    {
        //if(PAR::$sql!=''){ }
        ADT::$LT=DB::assoc_tomb("SELECT nev,hu FROM lng WHERE lap='nyito'");
       // print_r(ADT::$dataT);
        if(PAR::$lt_sql){
           //  ADT::$LT=DB::assoc_tomb(ADT::$sql_LT)
        ;}
    }
}

class App
{
    public function alap()
    {
        Data::alap();
        View::alap();//print_r(ADT::$dataT);
    }

    public function joghiba()
    {
        if ($_SESSION['userid'] == 0) {
            ADT::$view = MOD::login();
        } else {
            ADT::$view = '<h2><!--#joghiba--></h2>';
        }
    }
}
//print_r(ADT::$datatomb_LT);



