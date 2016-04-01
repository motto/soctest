<?php
class GOB {
    private static $userjog=Array();
    public static $log_mod=true;
    public static $lang='en';
    public static $LT=array(); //nyelvi tömb
    public static $user=Array();
    public static $hiba=array();
    public static $param=array();
    public static $client=null;
    /**
     * @var string
     * '' (alapértelmezés) az adminok csak saját cikkeiket szerkeszthetik
     * 'kozos' az adminok egymás cikkeit szerkeszthetik
     * 'tulajdonos' Az adminok szerkeszthetnek minden cikket
     */
    public static $admin_mod='';
    public static function get_userjog($jogname){
        if(in_array($jogname,self::$userjog)){return true;}
        else{return false;}
    }
    public static function set_userjog(){
        self::$userjog=Jog::fromGOB();
    }
    public static function set_userdata(){
        GOB::$user=DB::assoc_sor("SELECT id,kifcim,username,email,password FROM userek WHERE id='".$_SESSION['userid']."'");
    }
    public static function set_lang(){
        if(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)=='hu'){GOB::$lang= 'hu';}
        if(isset($_SESSION['lang'])){GOB::$lang=$_SESSION['lang'];}
        if(isset($_GET['lang'])){GOB::$lang=$_GET['lang'];$_SESSION['lang']=$_GET['lang'];}
    }
}