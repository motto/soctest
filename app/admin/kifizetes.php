<?php
include_once'app/admin.php/lib/tablas_alap.php';
require_once('vendor/autoload.php');
use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
//küldés---------
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money;
ADT::$jog='admin.php';
ADT::$ikonsor=array('pub','unpub');
ADT::$tablanev='penztar';
ADT::$tabla_szerk =array(
    array('cim'=>'','mezonev'=>'id','tipus'=>'checkbox'),
    array('cim'=>'Usernev','mezonev'=>'username','tipus'=>''),
    array('cim'=>'Kifizetési cím','mezonev'=>'kifcím','tipus'=>''),
    array('cim'=>'Egyenleg','mezonev'=>'egyenleg','tipus'=>'')
);

class SDT{
    //paraméterek
    public static $kif_limit=100000;//
    public static $kif_account_id='c1d64af3-931f-5109-940f-3bb52a80786a';//base
//változók--------------------
    public static $satoshi=0;
    public static $accuserid=0;
}

class PdataS{

    public static function ujleker()
    {
        $sql="SELECT u.id,u.kifcim,u.username,p.userid,SUM(p.satoshi) AS egyenleg FROM userek u  INNER JOIN penztar p ON p.userid=u.id GROUP BY p.userid HAVING egyenleg>".ADT::$kif_limit;
        ADT::$datatomb =DB::assoc_tomb($sql);

    }
    public static function user_leker($userid)
    {
        $sql="SELECT u.id,u.kifcim,u.username,p.userid,SUM(p.satoshi) AS egyenleg FROM userek u  INNER JOIN penztar p ON p.userid=u.id WHERE u.id='".$userid."' GROUP BY p.userid";
        return DB::assoc_sor($sql);

    }


    public static function utal($accountid,$osszeg,$to_tarca,$uzenet=' ')
    {
        $result=true;
        $account= GOB::$client->getAccount($accountid);
        $transaction = Transaction::send([
            'toBitcoinAddress' => $to_tarca,
            'amount'           => new Money($osszeg, CurrencyCode::BTC),
            'description'      => $uzenet,
            'fee'              => '0.0001' // only requi..
        ]);

        try {
            GOB::$client->createAccountTransaction($account, $transaction);
            $response=GOB::$client->decodeLastResponse();
            if(!$response['data']['status'] == 'pending')
            {
                $result=false;
                GOB::$hiba['coin'][]='status hiba történt tranzakció közben! accountid:'.$accountid.', to tarca:'.$to_tarca.', osszeg: '.$osszeg.' uzenet: '.$uzenet;
            }


        } catch (Exception $e)
        {
            GOB::$hiba['coin'][]='1-es szintű hiba történt tranzakció közben!accountid:'.$accountid.', to tarca:'.$to_tarca.', osszeg: '.$osszeg.' uzenet: '.$uzenet;
            $result=false;
        }
        return $result;
    }

}

class Admin extends Admin
{

    public function pub()
    {
        if (isset($_POST['sor']))
        {
            foreach ($_POST['sor'] as $userid)
            {
               $usersor= Data::user_leker($userid);
                if(!empty($usersor['kifcim']))
                {
                    $amount=$usersor['egyenleg']/100000000 ;
                    $formazott = number_format($amount, 6, '.', '');
                   // echo $szam_forma.$usersor['username'].$usersor['id'];
                 if(Data::utal(ADT::$kif_account_id,$formazott ,$usersor['kifcim'],'bejovo'))
                  {
                        $sql = "INSERT INTO penztar (userid,tr_cim,satoshi,megjegyzes)VALUES('" . $usersor['id']. "','kifizetés','-" . $usersor['egyenleg'] . "','kuldo accountid:" . ADT::$kif_account_id. "')";
                        // echo $sql;
                        DB::parancs($sql)   ;
                  }else{GOB::$hiba['coin'][]=$usersor['username'].': nem sikerült az utalás! Valószínűleg nem jók a kifizetési adatok.';}
                }
                else{GOB::$hiba['coin'][]=$usersor['username'].': nem rendelkezik kifizetési címmel!';}
        }
    }


        $this->alap();

    }

    public function alap()
    {
        $hiba='';
        Data::ujleker();
        ADT::$datatabla=MOD::tabla(ADT::$tabla_szerk,ADT::$datatomb);
        ADT::$view=MOD::ikonsor(ADT::$ikonsor);
        ADT::$view=str_replace('<!--|tabla|-->', ADT::$datatabla, ADT::$view );
        if(isset(GOB::$hiba['belep']))
        {$hiba=FeltoltS::hibakiir(GOB::$hiba['belep']);}
        if(isset(GOB::$hiba['coin']))
        {$hiba=$hiba.FeltoltS::hibakiir(GOB::$hiba['coin']);}
        ADT::$view=str_replace('<!--|hiba|-->', $hiba, ADT::$view );
    }

};

if(isset($_POST['sor'])){//print_r($_POST['sor']);
}

$app=new Admin();
$fn=TASK_S::get_funcnev($app);
//ADT::$datasor_sql="SELECT * FROM faucet WHERE id='".ADT::$id."' ";

$app->$fn();