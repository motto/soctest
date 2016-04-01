<?php
include_once'app/app_nyito.php';



class Rview
{//$varolista='<div id="infoablak">';public static $fejlec=

    public static $fejlec='<div class="varodiv">
                            <div class="varolink">Link</div>
                            <div class="varopont">Pont</div>
                            <div class="varopont">perc</div>
                            <div style="clear: both;"></div>
                         </div>' ;

    public static function varolista()
    {
        $varolista ='<div id="infoablak">';
        $varolista = $varolista .self::$fejlec;
        foreach (Appt::$linktomb as $varosor)
        {
            $varolista = $varolista . self::varolink($varosor['link'], $varosor['pont'], $varosor['hatravan'],$varosor['id']);
        }
        $varolista= $varolista.'</div>';
        return $varolista;
    }

    public static function varolink($link,$pont,$perc,$id)
    {
        $result='<div id="'.$id.'" class="varodiv">
                <div class="varolink"><a href="index.php?app=rotator&id='.$id.'" >'.parse_url($link, PHP_URL_HOST).'</a></div>
                <div class="varopont">'.$pont.'</div>
                <div class="varoperc">'.$perc.'</div>
                <div style="clear: both;"></div>
             </div>' ;
        return $result;
    }
}

class APPT
{
    public static $itemid='0';
    public static $userid='0';
    public static $itemtomb=array();
    public static $litatomb=array();
    public static $mktime='0';
    public static $linktomb=array();
}

ADT::$jog='user';


/**
 * feltölti a lapváltozókat ADT::userid, ADT::lapid;
 */
class Lap
{
    public function __construct()
    {
       APPT::$mktime=time();
        APPT ::$userid=$_SESSION['userid'];
        if(isset($_GET['id'])){APPT::$itemid=$_GET['id'];}
    }
}

/**
 * Az adattömbök feltöltését végzi APPT:: itemtömb stb
 */
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
        $sql1= "DELETE FROM faucet_log WHERE userid = '".APPT::$userid."' AND linkid = '".APPT::$itemid."' ";
        DB::parancs($sql1);
        $sql= "INSERT INTO faucet_log (userid,linkid,mktime)VALUES ('".APPT::$userid."','".APPT::$itemid."','".APPT::$mktime."')";
        DB::beszur($sql);
    }
    public function linktomb_feltolt()
    {
        $sql="SELECT * FROM faucet WHERE pub='0' ORDER BY pont DESC ";
        $sql_log="SELECT * FROM faucet_log WHERE userid='".APPT::$userid."'" ;
        $linktomb=DB::assoc_tomb($sql);
        $logtomb=DB::assoc_tomb($sql_log);
        $logtomb_idkulcs=TOMB::mezobol_kulcs($logtomb,'linkid');

        foreach($linktomb as $linksor)
        {
            if(isset($logtomb_idkulcs[$linksor['id']]))
            {
                $eltelt_sec=APPT::$mktime-$logtomb_idkulcs[$linksor['id']]['mktime'];
                $eltelt_perc=$eltelt_sec/60;
                $linksor['hatravan']=$linksor['perc']-$eltelt_perc;
                if($linksor['hatravan']<1){$linksor['hatravan']=0;}
            }
            else
            {
                $linksor['hatravan']=0;
            }

            APPT::$linktomb[]=$linksor;
        }
    }
    public function itemtomb_feltolt()
    {
        if(APPT::$itemid==0)
        {
            $megvan='';
            foreach(APPT::$linktomb as $link)
            {
                if($link['hatravan']==0 && $megvan=='')
                {
                    APPT::$itemtomb=$link;
                    // print_r(APPT::$linktomb);
                    APPT::$itemid=APPT::$itemtomb['id'];
                    $megvan='ok';
                }

            }

        }
        else
        {
            APPT::$itemtomb=DB::assoc_sor("SELECT * FROM faucet WHERE id='".APPT::$itemid."'");
        }
    }
}

/**
 *becsatolja az fget-et ha van illetve beállítja az APPT::$task-ot
 * tartalmazza a task függvényeket;
 */
class App extends App
{
    public function alap()
    {


        $tartalom = '<div style="width:1500px; position:absolute;top:100px;">' . Rview::varolista();
        $tartalom = $tartalom . '<iframe src="' . APPT::$itemtomb['link'] . '"  width="1200px" height="1500px"></iframe></div>';
        ADT::$view = $tartalom;

    }
}
$lap=new Lap();
$adatok = new Adatok();
$app=new App();

//$view=new View_base();



