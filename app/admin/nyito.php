<?php

class ADT
{
    public static $jog='admin.php';
    public static $task='alap';
    public static $nev=''; //ha nem üres a task szerkeszt lesz, és ezt a mezőt szerkeszti
    public static $mezokeszit=false; //elkészíti az adott db mezőt ha még nincs
    public static $mezotorol=false; //törli a fölösleges mezőt a táblából
    public static $task_valaszt=array('post','get');
    public static $view='';
    public static $datatomb_LT=array();
    public static $datasor_LT=array();
    public static $lapnev='nyito';
    public static $tablanev='lng';
    public static $texturlap='app/admin.php/view/urlapok/nyitotxt.html';
    public static $inputurlap='app/admin.php/view/urlapok/nyitoinput.html';
    public static $view_file='tmpl/flat/content/nyito.html';
    public static $mezotomb=array();
   // public static $func_aliasT=array();

}


class AppView {

   public static function alap()
    {
        ADT::$view=file_get_contents(ADT::$view_file, true);

    }
    public static function textmezo()
    {
        $html=file_get_contents(ADT::$texturlap, true);
        $datasor=AppDataS::datasor_LT(ADT::$nev);
        $html=str_replace('data="nev"', 'value="'.ADT::$nev.'"', $html );
        if(!empty($datasor))
        {
            $html=str_replace('<!--#hu-->', $datasor['hu'], $html );
            $html=str_replace('<!--#en-->', $datasor['en'], $html );
        }


        ADT::$view= $html;
    }
    public static function inputmezo()
    {
        $html=file_get_contents(ADT::$inputurlap, true);
        $datasor=AppDataS::datasor_LT(ADT::$nev);

        $html=str_replace('data="nev"', 'value="'.ADT::$nev.'"', $html );
        if(!empty($datasor))
        {
            $html=str_replace('data="hu"', 'value="'.$datasor['hu'].'"', $html );
            $html=str_replace('data="en"', 'value="'.$datasor['en'].'"', $html );
        }
        ADT::$view= $html;
    }
}

class Admin {

    public function __construct()
    {

    }

    public function alap()
    {
       AppView::alap();
       $this->szerk_gomb_beszur();
        AppDataS::datatomb_LT();
       ADT::$view= FeltoltS::LT_fromdb(ADT::$view,ADT::$datatomb_LT);
       if(ADT::$mezokeszit)
       {AppDataS::db_mezo_keszit();}
       if(ADT::$mezotorol)
       {AppDataS::db_mezo_torol();}
    }
    public function ment()
    {
        AppDataS::ment();
       $this->alap();
    }
    public function cancel()
    {
        $this->alap();
    }
    public function szerk()
    {
        if(substr(ADT::$nev, 0, 2)=='tx')
        {AppView::textmezo();}
        else
        {AppView::inputmezo();}
    }

    /**
     *  első alakalommal mezők létrehozása a táblában taskok funkciok létrehozása
     */
    public  function szerk_gomb_beszur()
    {
        $matches='';
        preg_match_all ("/<!--##([^`]*?)-->/",ADT::$view , $matches);

        foreach($matches[1] as $mezo)
        {

            $gomb='<button class="btkep" type="submit" name="nev" value="'.$mezo.'"><img src="res/ico/32/edit.png"/></br><h4>Szerk</h4></button>';
            $cserestring='<!--##'.$mezo.'-->';
            ADT::$view=str_replace($cserestring, $gomb.$cserestring, ADT::$view );
            ADT::$mezotomb[]=$mezo;
        /*
          $mezonevtomb=  explode('_',$elem);
            if( $mezonevtomb[0]=='tx'){ $mezonev=$mezonevtomb[1];
               ADT::$func_aliasT[$mezonev]= 'text';
            } else { $mezonev=$elem;
                ADT::$func_aliasT[$mezonev]= 'input';}*/
        }

    }
    public function joghiba()
    {
        if($_SESSION['userid']==0)
        {ADT::$view=MOD::login();}
        else
        {ADT::$view='<h2><!--#joghiba--></h2>';}

    }

}

class AppDataS
{

    public static function db_mezo_keszit()
    {
        foreach( ADT::$mezotomb as $mezo)
        {
            $sql="SELECT * FROM ".ADT::$tablanev." WHERE nev='".$mezo."' AND lap='".ADT::$lapnev."'";
            $marvan=DB::assoc_sor($sql);
            if(empty($marvan))
            {
                $sql="INSERT INTO ".ADT::$tablanev." (nev,lap) VALUES ('".$mezo."','".ADT::$lapnev."') ";
                DB::parancs($sql);
            }
        }
    }
    public static function db_mezo_torol()
    {
        $sql="SELECT * FROM ".ADT::$tablanev." WHERE  lap='".ADT::$lapnev."'";
        $tomb=DB::assoc_tomb($sql);
        foreach( $tomb as $sor)
        {
            if(!in_array($sor['nev'],ADT::$mezotomb))
            {
                DB::del('lng',$sor['id']);
            }
        }
    }
    public static  function ment()
    {   $en='';$hu='';
        if(isset($_POST['en'])){$en=$_POST['en'];}
        if(isset($_POST['hu'])){$hu=$_POST['hu'];}
        if(isset($_POST['mezo']))
        {
            $sql="UPDATE ".ADT::$tablanev." SET en='".$en."', hu='".$hu."' WHERE lap='".ADT::$lapnev."' AND nev='".$_POST['mezo']."'";
         // echo $sql;
          DB::parancs($sql);
        }
    }
    public static  function datasor_LT($nev)
    {
        $sql="SELECT * FROM ".ADT::$tablanev." WHERE nev='".$nev."'";
        ADT::$datasor_LT =DB::assoc_sor($sql);
        return ADT::$datasor_LT;
    }
    public static  function datatomb_LT()
    {
        $sql="SELECT nev,".GOB::$lang." FROM ".ADT::$tablanev." WHERE lap='".ADT::$lapnev."'";
        ADT::$datatomb_LT=DB::assoc_tomb($sql);
        return ADT::$datatomb_LT;
    }
}
$app=new Admin();
$fn=Task_S::get_nev_funcnev($app);
//echo $fn;
$app->$fn();