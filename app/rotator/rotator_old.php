<?php
include_once 'app/rotator/view/view.php';
include_once 'app/app.php';
class ADT
{
    //paraméterek Nem szabad törölni!---------------------------
    public static $jog='user';
    public static $allowed_task=array('joghiba');
    public static $allowed_fget=array();

    //globális változók Nem szabad törölni!--------------------------
    public static $task='alap';
    public static $itemid='0';
    public static $userid='0';
    public static $tartalom='';
    public static $html='';
    /** a view osztály állítja be ez alapján az alp html-t alapértelmezés: tmpl/GOB::tmpl/ADT::html.'html' ;
     */
    public static $html_file='base.html';
    public static $itemtomb=array();
    public static $litatomb=array();
    //app változók------------------------
    public static $mktime='0';
    public static $linktomb=array();

}

/**
 * feltölti a lapváltozókat ADT::userid, ADT::lapid;
 */
class Lap
{


    public function __construct()
    {
        ADT::$mktime=time();
        ADT::$userid=$_SESSION['userid'];
       if(isset($_GET['id'])){ADT::$itemid=$_GET['id'];}
    }
}

/**
 * Az adattömbök feltöltését végzi ADT:: itemtömb stb
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
        $sql1= "DELETE FROM faucet_log WHERE userid = '".ADT::$userid."' AND linkid = '".ADT::$itemid."' ";
        DB::parancs($sql1);
        $sql= "INSERT INTO faucet_log (userid,linkid,mktime)VALUES ('".ADT::$userid."','".ADT::$itemid."','".ADT::$mktime."')";
        DB::beszur($sql);
    }
    public function linktomb_feltolt()
    {
        $sql="SELECT * FROM faucet WHERE pub='0' ORDER BY pont DESC ";
        $sql_log="SELECT * FROM faucet_log WHERE userid='".ADT::$userid."'" ;
        $linktomb=DB::assoc_tomb($sql);
        $logtomb=DB::assoc_tomb($sql_log);
        $logtomb_idkulcs=TOMB::mezobol_kulcs($logtomb,'linkid');

        foreach($linktomb as $linksor)
        {
            if(isset($logtomb_idkulcs[$linksor['id']]))
            {
                $eltelt_sec=ADT::$mktime-$logtomb_idkulcs[$linksor['id']]['mktime'];
                $eltelt_perc=$eltelt_sec/60;
                $linksor['hatravan']=$linksor['perc']-$eltelt_perc;
                if($linksor['hatravan']<1){$linksor['hatravan']=0;}
            }
            else
            {
                $linksor['hatravan']=0;
            }

            ADT::$linktomb[]=$linksor;
        }
    }
    public function itemtomb_feltolt()
    {
        if(ADT::$itemid==0)
        {
          $megvan='';
          foreach(ADT::$linktomb as $link)
          {
              if($link['hatravan']==0 && $megvan=='')
              {
                  ADT::$itemtomb=$link;
                  // print_r(ADT::$linktomb);
                  ADT::$itemid=ADT::$itemtomb['id'];
                  $megvan='ok';
              }

          }

        }
        else
        {
            ADT::$itemtomb=DB::assoc_sor("SELECT * FROM faucet WHERE id='".ADT::$itemid."'");
        }
    }
}

/**
 *becsatolja az fget-et ha van illetve beállítja az ADT::$task-ot
 * tartalmazza a task függvényeket;
 */
class App extends App_base
{
public function alap()
{
    $adatok = new Adatok();

    $tartalom = '<div style="width:1500px; position:absolute;top:100px;">' . Rview::varolista();
    $tartalom = $tartalom . '<iframe src="' . ADT::$itemtomb['link'] . '"  width="1200px" height="1500px"></iframe></div>';
    ADT::$tartalom = $tartalom;

}
}
$lap=new Lap();
$app=new App();
$view=new View_base();
$task=ADT::$task;
$app->$task();

ADT::$html = str_replace('<!--|tartalom|-->',ADT::$tartalom,ADT::$html );
ADT::$html = str_replace('//<!--var_itemid-->','var itemid=\''.ADT::$itemid.'\';',ADT::$html );
echo ADT::$html;

