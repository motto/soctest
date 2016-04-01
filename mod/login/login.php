<?php
namespace mod\login;
defined( '_MOTTO' ) or die( 'Restricted access' );
//echo $_POST['ltask'].'éééé';
include_once 'mod/mod_alap.php';
include_once 'mod/login/lt.php';
use lib\db\DB;
use lib\db\DBA;
use mod\coin\ADT;
use mod\coin\Coin;

class LogADT
{
    public static $itemid='0';
    public static $jog='noname';
    public static $tablanev='userek';
    public static $view='';
    public static $referer=true;
    public static $alap_func='alap';
    public static $task_nev='ltask';
    public static $task_tip=array('get','post');
    public static $reg_form='mod/login/view/regisztral_form.html';
    public static $belep_form='mod/login/view/belep_form.html';
    public static $kilep_form='mod/login/view/kilep_form.html';
    public static $szerk_form='mod/login/view/szerk_form.html';
    public static $szerk_kesz_form='mod/login/view/szerk_kesz_form.html';
    public static $tiltott_func=array();

    public static $frissitmezok=array
    (
       // array('mezonev'=>'username'),
        //array('mezonev'=>'','postnev'=>'','ell'=>'','tipus'=>''),
        array('mezonev'=>'password')
    );


    public static $mentmezok=array
    (
        array('mezonev'=>'username',),
        //array('mezonev'=>'','postnev'=>'','ell'=>'','tipus'=>''),
        array('mezonev'=>'email',),
        array('mezonev'=>'password',),
        array('mezonev'=>'ref')
    );
}
class LogView
{
   public static function belep()
   {
      LogADT::$view= file_get_contents(LogADT::$belep_form, true);
   }
    public static function kilep(){
        LogADT::$view= file_get_contents(LogADT::$kilep_form, true);
    }
    public static function reg(){
        LogADT::$view= file_get_contents(LogADT::$reg_form, true);
        if(isset($_POST['ref'])){
            LogADT::$view = str_replace('<!--<h5>ref</h5>-->','<h5>Ref:'.$_POST['ref'].'</h5>', LogADT::$view);
            LogADT::$view = str_replace('data="ref"','value="'.$_POST['ref'].'"', LogADT::$view);
        }

    }
    public static function szerk(){
        LogADT::$view= file_get_contents(LogADT::$szerk_form, true);
    }
    public static function szerk_kesz(){
    LogADT::$view= file_get_contents(LogADT::$szerk_kesz_form, true);
}

}

class Login
{
    public function result()
    {
        $func='alap';
        if(isset($_POST['ltask'])){$func=$_POST['ltask'];}
        $this->$func();
        return LogADT::$view;
    }
    public function alap()
    {
        if ($_SESSION['userid'] > 0) {
            LogView::kilep();
        } else {
            LogView::belep();
            $hiba = LogDataS::hibakiir();
            LogADT::$view = str_replace('<!--<h5>hiba</h5>-->', $hiba, LogADT::$view);
        }
    }


    public function belep()
    {
            $this->alap();
    }


    public function kilep()
    {
       /* if(isset($_COOKIE['cook_sess_id']))
        {
        setcookie("cook_sess_id", "", time()-COOKIE_EXPIRE, COOKIE_PATH);
        }*/
        $_SESSION['userid'] = 0;
        $this->alap();
    }

    public function szerk()
    {
        LogView::szerk();
    }

    public function szerkment()
    {
        LogDataS::szerk_ment();
        if (empty(\GOB::$hiba['login'])) {
            LogView::szerk_kesz();
        } else {
            LogView::szerk();
            $hiba = LogDataS::hibakiir();
            LogADT::$view = str_replace('<!--<h5>hiba</h5>-->', $hiba, LogADT::$view);
        }
//print_r(\GOB::$hiba['login']);
    }


    public function reg()
    {
        $view = file_get_contents(LogADT::$reg_form, true);
        if (isset($_SESSION['ref'])) {
            $view = str_replace('<!--<h5>ref</h5>-->', 'Referencia:' . $_SESSION['ref'], $view);
            $view = str_replace('data="ref"', 'value="' . $_SESSION['ref'] . '"', $view);
        }

        LogADT::$view= $view;

    }

    public function ment()
    {

        if (LogDataS::ment()) {
            $this->alap();
        } else {
            $view = file_get_contents(LogADT::$reg_form, true);
            $hiba = LogDataS::hibakiir();
            $tartalom = str_replace('<!--<h5>hiba</h5>-->', $hiba, $view);
            LogADT::$view= $tartalom;
        }

    }

    public function megsem()
    {
        $this->alap();
    }
}
class LogDataS {
    public static function ment()
    {
        $hiba=true;
        $jelszo = md5($_POST['password']);
        $jelszo2 = md5($_POST['password2']);
        $usernev = $_POST['username'];
        if($jelszo!=$jelszo2)
        {
            \GOB::$hiba['login'][]=ModLT::$newpasswd_nomatch[\GOB::$lang];
            $hiba=false;
        }
        if(!self::usernev_ell($usernev))
        {
            $hiba=false;
        }
        $sql = "SELECT username FROM  userek WHERE username='" . $usernev . "'";
        $marvan = DB::assoc_sor($sql);
        if (isset($marvan['username'])) {
            \GOB::$hiba['login'][] = ModLT::$username_have[\GOB::$lang];
            $hiba = false;
        }
        if($hiba)
        {
            $beszurtid=DB::beszur_postbol(LogADT::$tablanev,LogADT::$mentmezok);
            if($beszurtid==0)
            {
                \GOB::$hiba['login'][]=ModLT::$dberror[\GOB::$lang];
                $hiba=false;
            }
            else
            {
                $coin=new Coin();
                $tarcaT=$coin->ujtarca($usernev);
                if($tarcaT['address']!=''){
                    $sql="INSERT INTO tarcak (tarcanev,tarca,tarcaid,accountid)
                          VALUES ('".$usernev."',
                          '".$tarcaT['address']."',
                          '".$tarcaT['addressid']."',
                          '".$tarcaT['accountid']."')";
                    $tarcaid=DBA::beszur($sql);

                    $ql2="UPDATE userek SET tarcaid='".$tarcaid."' WHERE id='".$beszurtid."'";
                    DBA::parancs($ql2);
                }
                else
                {\GOB::$hiba['coin'][]= 'nincs coin address';}

            }
        }
        return $hiba;
    }
    public static function hibakiir()
    {
        $result='';
        if(isset(\GOB::$hiba['login']))
        {//print_r(\GOB::$hiba);
            foreach(\GOB::$hiba['login'] as $hiba)
            {
                $result=$result.$hiba.'</br>';
            }
        }
      //  echo $result;
        return $result;
    }
    public static function usernev_ell($usernev)
    {
        $result=true;
        $Regex_hu_tobbszo='/^[a-zA-Z\d éáűúőóüöÁÉŰÚŐÓÜÖ]+$/';
        $Regex_minmax='/^.{5,20}$/';
        if(preg_match($Regex_minmax,$usernev)!=1)
        {
            \GOB::$hiba['login'][]= ModLT::$usernamelong_err[\GOB::$lang];
            $result=false;
        }
        if(preg_match($Regex_hu_tobbszo,$usernev)!=1)
        {
            \GOB::$hiba['login'][]=ModLT::$spec_char_error[\GOB::$lang];
            $result=false;
        }

        return $result;
    }
    public static function belep()
    {   $result=true;
        $jelszo = md5($_POST['password']);
        $usernev = $_POST['username'];

        if(self::usernev_ell($usernev))
        {
            $sql="SELECT id,password FROM userek WHERE username='".$usernev."' AND pub='0'";
            $dd=DB::assoc_sor($sql);
            if(!empty($dd))
            {    if($jelszo!=$dd['password'])
                {
                    \GOB::$hiba['login'][]=ModLT::$login_data_nomatch[\GOB::$lang];

                }
                else
                {
                    $_SESSION['userid']=$dd['id'];
                   // echo $_SESSION['userid'];
                }
            }else{ $result=false;\GOB::$hiba['login'][]=ModLT::$login_data_nomatch[\GOB::$lang];}
        }
        return $result;
       // echo 'belépés----';
    }
    public static function szerk_ment()
    {
        /**TODO
         * usernév ellenórzést megcsinálni hogy ne lehessen két ugyanolyan de a sajátja maradhasson  ment llenőrzése nemó ide!
         */

        $hiba = true;
        $old_jelszo = md5($_POST['oldpass']);
        $jelszo = md5($_POST['password']);
        $jelszo2 = md5($_POST['password2']);
        if ($old_jelszo != \GOB::$user['password']) {
            \GOB::$hiba['login'][] = ModLT::$oldpasswd_err[\GOB::$lang];
            $hiba = false;
        }
        if ($jelszo != $jelszo2) {
            \GOB::$hiba['login'][] = ModLT::$newpasswd_nomatch[\GOB::$lang];
            $hiba = false;
        }
        if ($hiba) {
          DB::parancs("UPDATE userek SET password='".$jelszo."' WHERE id='".\GOB::$user['id']."'");;
        }

    }

}
