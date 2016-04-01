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
ADT::$view_file='app/admin.php/view/tabla_alap.html';
//ADT::$urlap_file ='app/admin.php/view/faucet_urlap.html';
ADT::$datatomb_sql="SELECT u.id, u.username,u.ref,u.datum,u.ellenorzott,u.pub,t.tarca FROM userek u INNER JOIN tarcak t on t.id=u.tarcaid ";
ADT::$ikonsor = array('pub','unpub','torol','email');
ADT::$tablanev='userek';
ADT::$tabla_szerk =array(
    array('cim'=>'','mezonev'=>'','tipus'=>'checkbox'),
    array('cim'=>'','mezonev'=>'pub','tipus'=>'pubmezo'),
    array('cim'=>'id','mezonev'=>'id','tipus'=>''),
    array('cim'=>'Usernev','mezonev'=>'username','tipus'=>''),
    array('cim'=>'Ref','mezonev'=>'ref','tipus'=>''),
    array('cim'=>'Tárca','mezonev'=>'tarca','tipus'=>''),
    array('cim'=>'Reg. Dátum','mezonev'=>'datum','tipus'=>''),
    array('cim'=>'Ellenőrzött?','mezonev'=>'ellenorzott','tipus'=>'')
);


class SDT{
    public static $kif_account_id='c1d64af3-931f-5109-940f-3bb52a80786a';//base
    public static $torol_base='13gc8kS3K1NJcSWXm3yC4Y5pcmrV6QrCaQ';
//változók--------------------
    public static $satoshi=0;
    public static $accuserid=0;
}

class PdataS{


    public static function tarca_leker($userid)
    {
        $sql="SELECT id,userid,tarca,accountid FROM tarcak  WHERE userid ='".$userid."'";
        $res=DB::assoc_sor($sql);
        if(empty($res)){return 0;}else{return $res['accountid'];}
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




class Admin extends Admin{
    public function torol()
    {
        //DB::tobb_del(ADT::$tablanev,ADT::$idT);
        foreach (ADT::$idT as $userid){
          $acid=  Data::tarca_leker($userid);
          if($acid>0)
          {
              try {
                  $account =GOB::$client->getAccount($acid);
                  $balance = $account->getBalance();
                  if($balance>0)
                  {
                      Data::utal($acid, $balance,ADT::$torol_base);
                  }
                  GOB::$client->deleteAccount($account);

              } catch (Exception $e)
              {GOB::$hiba['usertorles'][]='Hiba a tárca törlése közben:'.$e->getMessage();}

          }
            if($userid>0)
            {
                DB::del('userek',$userid);
                DB::del('tarcak',$userid,'userid');
                DB::del('penztar',$userid,'userid');
            }
        }

        $this->alap();
    }

};

$app=new Admin();
$fn=TASK_S::get_funcnev($app);
ADT::$datasor_sql="SELECT * FROM userek WHERE id='".ADT::$id."' ";

$app->$fn();


