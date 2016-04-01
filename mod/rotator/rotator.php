<?php
namespace mod\rotator;
defined( '_MOTTO' ) or die( 'Restricted access' );
class Rview
{//$varolista='<div id="infoablak">';public static $fejlec=

    public static $fejlec='<div class="varodiv">
                            <div class="varolink">Link</div>
                            <div class="varopont">Sat</div>
                            <div class="varopont">Min</div>
                            <div style="clear: both;"></div>
                         </div>' ;

    public static function varolista()
    {
        $varolista ='<div id="infoablak" style="margin-top: 0px;padding-top: 0px" >';
        $varolista = $varolista .self::$fejlec;
        foreach (ROT::$linktomb as $varosor)
        {
            $varolista = $varolista . self::varolink($varosor['link'], $varosor['pont'], $varosor['hatravan'],$varosor['id']);
        }
        $varolista= $varolista.'</div>';
        return $varolista;
    }

    public static function varolink($link,$pont,$perc,$id)
    {
        if(parse_url($link, PHP_URL_HOST)==''){$linknev='undefined link';}
        else{$linknev=parse_url($link, PHP_URL_HOST);}
        $result='<div id="'.$id.'" class="varodiv">
                <div class="varolink"><a href="index.php?app=rotator&id='.$id.'" >'.$linknev.'</a></div>
                <div class="varopont">'.$pont.'</div>
                <div class="varoperc">'.$perc.'</div>
                <div style="clear: both;"></div>
             </div>' ;
        return $result;
    }
}
class ROT
{
    //paraméterek Nem szabad törölni!---------------------------
    public static $jog='user';
   // public static $allowed_task=array('joghiba');
   // public static $allowed_fget=array();

    //globális változók Nem szabad törölni!--------------------------
   // public static $task='alap';
    public static $itemid='0';
    public static $userid='0';
    public static $view='';
    //public static $tartalom='';
   // public static $html='';
    /** a view osztály állítja be ez alapján az alp html-t alapértelmezés: tmpl/GOB::tmpl/ROT::html.'html' ;
     */
  //  public static $html_file='base.html';
    public static $itemtomb=array();
    public static $litatomb=array();
    //app változók------------------------
    public static $mktime='0';
    public static $linktomb=array();

}

/**
 * feltölti a lapváltozókat ROT::userid, ROT::lapid;
 */
class Lap
{


    public function __construct()
    {
        ROT::$mktime=time();
        ROT::$userid=$_SESSION['userid'];
        if(isset($_GET['id'])){ROT::$itemid=$_GET['id'];}
    }
}

class Adatok
{

    public function __construct()
    {
        $this->linktomb_feltolt();
        $this->itemtomb_feltolt();
        $this->logol();
    }

    public  function logol()
    {
        $sql1= "DELETE FROM faucet_log WHERE userid = '".ROT::$userid."' AND linkid = '".ROT::$itemid."' ";
        DB::parancs($sql1);
        $sql= "INSERT INTO faucet_log (userid,linkid,mktime)VALUES ('".ROT::$userid."','".ROT::$itemid."','".ROT::$mktime."')";
        DB::beszur($sql);
    }
    public function linktomb_feltolt()
    {
        $sql="SELECT * FROM faucet WHERE pub='0' ORDER BY pont DESC ";
        $sql_log="SELECT * FROM faucet_log WHERE userid='".ROT::$userid."'" ;
        $linktomb=DB::assoc_tomb($sql);
        $logtomb=DB::assoc_tomb($sql_log);
        $logtomb_idkulcs=TOMB::mezobol_kulcs($logtomb,'linkid');

        foreach($linktomb as $linksor)
        {
            if(isset($logtomb_idkulcs[$linksor['id']]))
            {
                $eltelt_sec=ROT::$mktime-$logtomb_idkulcs[$linksor['id']]['mktime'];
                $eltelt_perc=round($eltelt_sec/60);
                $linksor['hatravan']=$linksor['perc']-$eltelt_perc;
                if($linksor['hatravan']<1){$linksor['hatravan']=0;}
            }
            else
            {
                $linksor['hatravan']=0;
            }

            ROT::$linktomb[]=$linksor;
        }
    }
    public function itemtomb_feltolt()
    {
        if(ROT::$itemid==0)
        {
            $megvan='';
            foreach(ROT::$linktomb as $link)
            {
                if($link['hatravan']==0 && $megvan=='')
                {
                    ROT::$itemtomb=$link;
                    // print_r(ROT::$linktomb);
                    ROT::$itemid=ROT::$itemtomb['id'];
                    $megvan='ok';
                }

            }

        }
        else
        {
            ROT::$itemtomb=DB::assoc_sor("SELECT * FROM faucet WHERE id='".ROT::$itemid."'");
        }
    }
}


class App
{
    public function alap()
    {


        $tartalom = '<table><tr><td valign="top">'.Rview::varolista();
        $tartalom = $tartalom . '</td><td style="width:100%"> <iframe src="' . ROT::$itemtomb['link'] . '" style="width:100%;height:4000px;"></iframe></td></tr></table>';
        ROT::$view = $tartalom;
        GOB::$html = str_replace('//<!--var_itemid-->','var itemid=\''.ROT::$itemid.'\';',GOB::$html );
        return ROT::$view;

    }
}
class Rotator
{
    public function result()
    {   $lap=new Lap();
        $adatok = new Adatok();
        $app=new App();
      return  $app->alap();}
}



