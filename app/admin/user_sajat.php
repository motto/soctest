<?php
include_once 'app/admin.php/lib/usernyito_alap.php';
ADT::$jog='user';
ADT::$inputurlap='app/admin.php/view/urlapok/input.html';
ADT::$LT=array(
    'username_have'=>array(
        'hu'=>'Már van ilyen felhasználónév!',
        'en'=>'Username allready have'
    ) ,
    'sajat_egyenleg'=>array(
        'hu'=>'Elérhető egyenleg:',
        'en'=>'Your deposit:'
    ) ,
    'penztar'=>array(
        'hu'=>'Pénztár',
        'en'=>'Account'
    ),'username'=>array(
        'hu'=>'Felhasználó név:',
        'en'=>'User name:'
    ) ,
    'sajat_tarca'=>array(
        'hu'=>'Saját BTC cím:',
        'en'=>'Your BTC address:'
    ) ,
    'ajanlo_link'=>array(
        'hu'=>'Személyes ajánló link:',
        'en'=>'Your personal ref link:'
    ) ,   'ajanlo_link_tx'=>array(
        'hu'=>'Az ajánló linket bármilyen formában megoszthatja, aki rákattintva érkezik a weblapunkra automatikusan az ön referáltja lesz.',
        'en'=>'The click this shared link,have a your reference '
    ) ,   'ajanlo_link_tx2'=>array(
        'hu'=>'Szintén szeméyre szabott linket generáló közösségi gombok:',
        'en'=>'Too personal reference button:'
    ) ,   'passwd'=>array(
        'hu'=>'Jelszó',
        'en'=>'Password'
    ) ,  'valtoztatni'=>array(
        'hu'=>'Titkos.Érdemes havonta változtatni!',
        'en'=>'Secret.You change a mount!'
    ) ,  'change'=>array(
        'hu'=>'Változtatás',
        'en'=>'Change'
    ) ,  'kifcim'=>array(
        'hu'=>'Kifizetési cím',
        'en'=>'Payment address'
    ) ,
    'udv'=>array(
    'hu'=>'Üdvözöljük ',
    'en'=>'Welcome '
)


);
class ViewS extends AlapView{}
class DataS extends AlapDataS{
    public static function useradatment()
    {
    $nev=$_POST['nev'];
    $sql="UPDATE userek SET ".$nev."='".$_POST[$nev]."' WHERE id='".GOB::$user['id']."'";
   //echo $sql;
    return DB::parancs($sql);
    }
    public static function alap()
    {

$egyenlegT=DB::assoc_sor("SELECT SUM(p.satoshi) AS egyenleg FROM penztar p WHERE userid='".GOB::$user['id']."' GROUP BY p.userid");

$tarcaT=DB::assoc_sor("SELECT t.tarca FROM userek u INNER JOIN tarcak t ON t.id=u.tarcaid WHERE u.id='".GOB::$user['id']."'");

        ADT::$dataT['egyenleg']=$egyenlegT['egyenleg'];
        ADT::$dataT['tarca'] =$tarcaT['tarca'];

        ADT::$dataT['username']=GOB::$user['username'];
        ADT::$dataT['userid']=GOB::$user['id'];
        ADT::$dataT['email']=GOB::$user['email'];
        ADT::$dataT['kifcim']=GOB::$user['kifcim'];
    }
}
class Admin extends AlapAdmin{

  public function szerk(){
      if($_POST['nev']=='email')
      {
          ADT::$view=file_get_contents('app/admin.php/view/urlapok/email.html', true);
      }else
      {
          ADT::$view=file_get_contents(ADT::$inputurlap, true);
      }

      ADT::$dataT['ertek']=GOB::$user[$_POST['nev']];
      ADT::$dataT['nev']=$_POST['nev'];
      ADT::$dataT['task']='useradatment';
      ViewS::feltolt();
      $hiba= ViewS::hibakiir();
      ADT::$view=str_replace('<!--#hiba-->', $hiba,ADT::$view  );

  }

    public function useradatment(){
        $hiba = true;
        if($_POST['nev']=='usernev')
        {
            $sql = "SELECT username FROM  userek WHERE username='" . $_POST['username'] . "'";
            $marvan = DB::assoc_sor($sql);
            if (isset($marvan['username']))
            {
                GOB::$hiba['login'][] = ADT::$LT['username_have'][GOB::$lang];
                $hiba = false;
            }
        }
        if($hiba)
        {
            if( DataS::useradatment())
            {
                GOB::$user=DB::assoc_sor("SELECT id,kifcim,username,email,password FROM userek WHERE id='".$_SESSION['userid']."'");
              $this->alap();
            }else
            {
                $this->szerk();
            }
        }else
        {
            $this->szerk();
        }

    }

}
$app=new Admin();
$funcnev='alap';
if(isset($_POST['task'])){$funcnev=$_POST['task'];}
if(isset($_GET['task'])){$funcnev=$_GET['task'];}
$nev='nincs';
if(isset($_POST['nev'])){$nev=$_POST['nev'];}
if(isset($_GET['nev'])){$nev=$_GET['nev'];}
ADT::$nev=$nev;
if(!GOB::get_userjog(ADT::$jog))
{$funcnev='joghiba';}
$app->$funcnev();