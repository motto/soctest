<?php
namespace mod\coin;
defined( '_MOTTO' ) or die( 'Restricted access' );
require_once('vendor/autoload.php');
use lib\db\DB;
use lib\db\DBA;

use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;
//küldés---------
use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money;


class ADT{
    //paraméterek
    public static $coinKey='duqWXbUlCKH8qNg8';
    public static $coinSecret='DE4hteGw1nAzRwpxh4hPVN8dwRBjSBCL';
    public static $tarcaBase='1FzgRFSFRS6aPxEWXS9yG9o4ZSPuCrvmTR';
    public static $tarcaDelBase='13gc8kS3K1NJcSWXm3yC4Y5pcmrV6QrCaQ';
    public static $notarcaT=array('BTC Wallet','Base','Deleted base','ggg');
    public static $szazalekT=array(71,14,8,4,2,1,0.4,0.2,0.1);//ref jutalék százalékok
//változók--------------------
    public static $satoshi=0;
    public static $accuserid=0;
    static public $client=null;
}


class Coin
{

    public function __construct()
    {
        if(ADT::$client==null)
        {
        $configuration =Configuration::apiKey(ADT::$coinKey, ADT::$coinSecret);
        ADT::$client = Client::create($configuration);
        }

    }

    public static function ujtarca($usernev)
    {
        $account = new Account();
        $account->setName($usernev);
        ADT::$client->createAccount($account);
        $accountid=$account->getId();
        $address = new Address();
        ADT::$client->createAccountAddress($account, $address);
        $addressid=ADT::$client->getAccountAddresses($account)->getFirstId();
        $address=ADT::$client->getAccountAddress($account,$addressid)->getAddress();

        $res['address']=$address;
        $res['addressid']=$addressid;
        $res['accountid']=$accountid;
     return $res;
    }


    public static function refleker($userid)
    {
        $sql="SELECT ref FROM userek WHERE id='".$userid."'";
        $res=DB::assoc_sor($sql);
        if(empty($res)){return 0;}else{return $res['ref'];}
    }

    public function tarca_leker($userid)
    {
        $sql="SELECT id,userid,tarca FROM tarcak  WHERE userid ='".$userid."'";
        $res=DB::assoc_sor($sql);
        if(empty($res)){return 0;}else{return $res['tarca'];}
    }

    public  function ujleker()
    {
        $datatomb=array();
        $accounts =ADT::$client->getAccounts();

        foreach ($accounts as &$account)
        {
            $balance = $account->getBalance();
            if($balance->getAmount()>0)
            {
               $datatomb[] =Array
               (
                   'id'=>$balance->getAmount().':'.$account->getId(),
                   'tarcanev'=>$account->getName() ,
                   'trcim'=>'bejövő','accountid'=>$account->getId(),
                   'amount'=>$balance->getAmount()
               );

            }

        }
    return $datatomb;
    }

    public  function utal($accountid,$osszeg,$to_tarca='',$uzenet=' ')
    {
        if($to_tarca==''){$to_tarca=ADT::$tarcaBase;}
        $result=true;
        $account= ADT::$client->getAccount($accountid);
        $transaction = Transaction::send([
            'toBitcoinAddress' => $to_tarca,
            'amount'           => new Money($osszeg, CurrencyCode::BTC),
            'description'      => $uzenet,
            'fee'              => '0.0001' // only requi..
        ]);

        try {
            ADT::$client->createAccountTransaction($account, $transaction);
            $response=ADT::$client->decodeLastResponse();
            if(!$response['data']['status'] == 'pending')
            {
                $result=false;
                \GOB::$hiba['coin'][]='status hiba történt tranzakció közben! accountid:'.$accountid.', to tarca:'.$to_tarca.', osszeg: '.$osszeg.' uzenet: '.$uzenet;
            }


        } catch (Exception $e)
        {
           \GOB::$hiba['coin'][]='1-es szintű hiba történt tranzakció közben!accountid:'.$accountid.', to tarca:'.$to_tarca.', osszeg: '.$osszeg.' uzenet: '.$uzenet;
            $result=false;
        }
        return $result;
    }

    public function utal_todb($userid,$accountid,$maradek, $satoshi,$i=0)
    {
        $szazalek = ADT::$szazalekT[$i] / 100;
        $osszeg = $satoshi* $szazalek;
        if($osszeg<=$maradek)
        {
            $maradek = $maradek - $osszeg;
            if($i==0)
            {$megjegyzes='rotator jovairás';
            }
            else
            { $megjegyzes='jutalek:' . $szazalek*100 . '%';}


            $sql = "INSERT INTO penztar (userid,tr_cim,satoshi,megjegyzes)
                    VALUES('" . $userid . "',
                    '" .$megjegyzes . "',
                    '" . $osszeg . "',
                    'kuldo accountid:" . $accountid . "')";

            DBA::parancs($sql);
        }
        else
        {
            \GOB::$hiba['coin'][]='elfogyott a jutalék. összeg:'.$osszeg.',maradék:'.$maradek.'szint:'.$i;
        }

        return $maradek;
    }

    public function accountid_to_userid($accountid)
    {
        $sql = "SELECT u.id,t.tarca FROM userek u INNER JOIN tarcak t ON u.tarcaid=t.id   WHERE accountid ='".$accountid ."'";
        $userT = DB::assoc_sor($sql);
        if(isset($userT['id']))
        { ADT::$accuserid=$userT['id'];
            return $userT['id'];
        }
        else
        {
            \GOB::$hiba['coin'][] = 'ezzel az accountiddel nincs felhasználó';
            return 0;
        }
    }
}