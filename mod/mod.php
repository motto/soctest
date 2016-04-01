<?php
namespace mod;
use lib\html\FeltoltS;
use mod\coin\ADT;
use mod\coin\Coin;
use mod\ikonsor\Ikonsor;
use mod\login\Login;
use mod\tabla\Tabla;
use mod\zaszlo\Zaszlo;

defined( '_MOTTO' ) or die( 'Restricted access' );
class MOD
{
public static function zaszlo()
{
   // include_once 'mod/zaszlo/zaszlo.php';
    $zaszlo=new Zaszlo();
    return $zaszlo->eng_hu();
}
public static function btc()
{  // include_once 'mod/coin/coin.php';
    $coin=new Coin();
    $rates = ADT::$client->getExchangeRates('btc');
    return $rates['rates']['USD'];
}
public static function rotator()
{
    include_once 'mod/rotator/rotator.php';
    $rotator=new Rotator();
    return $rotator->result();
}
public static function login()
    {
       //  include_once 'mod/login/login.php';
        $login=new Login();
        $view=$login->result();
        $view=FeltoltS::LT($view,'ModLT');
            return $view;
    }
public static function ikonsor($param)
    {
        //include_once 'mod/ikonsor/ikonsor.php';
        $ob=new Ikonsor();
       // $ob->mezok=$ikonok;
        return $ob->result($param);
    }
public static function tabla($dataszerk,$datatomb)
    {
        //include_once 'mod/tabla/tabla.php';
        $ob=new Tabla($dataszerk,$datatomb);
        return $ob->result();
    }
public static function email()
{
    include_once 'mod/email/email.php';
    $ob=new Mail();
    return $ob->result();
}
}