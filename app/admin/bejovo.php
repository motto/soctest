<?php
namespace app\admin;
use lib\base\html\FeltoltS;
use lib\db\DBA;
use lib\mod\coin\Coin;
use mod\MOD;

defined( '_MOTTO' ) or die( 'Restricted access' );

class PAR
{
    public static $jog = 'admin.php';
    public static $ikonsor = array('pub','unpub');
    public static $view_file='app/admin.php/view/tabla_alap.html';
}
class ADT
{
    public static $hiba = '';
    public static $view = '';
    public static $dataT = array();
    public static $LT = array();
    public static $tabla_szerk  = array();
    public static $datatabla = ''; //ide kerül a generált táblázat
}

ADT::$tabla_szerk =array(
    array('cim'=>'','mezonev'=>'','tipus'=>'checkbox'),
    array('cim'=>'Tárcanév','mezonev'=>'tarcanev','tipus'=>''),
    array('cim'=>'bejövő','mezonev'=>'amount','tipus'=>'')
);


class Admin
{
    private $coin=null;

    public function __construct($coin)
    {
        $this->coin = new Coin();
    }


    public function pub()
{
    if (isset($_POST['sor']))
    {
    foreach ($_POST['sor'] as $sor)
    {
        $sorT = explode(':', $sor);
        $accountid = $sorT[1];$amount = $sorT[0];
        $satoshi=$amount/0.00000001;
        $maradek =$satoshi;
        $accuserid=$this->coin->accountid_to_userid($accountid);
        if($accuserid>0)
        {
            if($this->coin->utal($accountid,$amount,'','bejovo'))
            {
            //user rész elküldése-------------------
            $maradek =$this->coin->utal_todb($accuserid,$accountid,$maradek,$satoshi,0);
            //rfjutalékok leosztása----------------------
            $refid =$this->coin->refleker($accuserid);
            $i = 1;
                while ($refid > 0)
                {
                    if ($refid > 0)
                    {
                    $maradek = $this->coin->utal_todb
                    ($refid, $accountid,$maradek,$satoshi, $i);

                    $refid = $this->coin->refleker($refid);
                    $i++; if ($i > 8) {$refid = 0;}
                    }
                }

                if ( $maradek> 0)
                {
                DBA::parancs("INSERT INTO penztar (userid,tr_cim,satoshi,megjegyzes)VALUES('0','jutalek maradék','" .$maradek . "','kuldo accountid:" . $accountid . "')");
                }
            }
        }
    }
    }

    $this->alap();

}

    public function alap()
    {
        $hiba='';
        ADT::$dataT=$this->coin->ujleker();

        ADT::$view=MOD::ikonsor(ADT::$ikonsor);

        $datatabla=MOD::tabla(ADT::$tabla_szerk,ADT::$dataT);
        ADT::$view=str_replace('<!--|tabla|-->', $datatabla, ADT::$view );
        if(isset(\GOB::$hiba['bejovo']))
        {ADT::$hiba=FeltoltS::hibakiir(\GOB::$hiba['bejovo']);}
        if(isset(\GOB::$hiba['coin']))
        {ADT::$hiba.=FeltoltS::hibakiir(\GOB::$hiba['coin']);}

    }

}

