<?php
namespace alap;
use ggg\ddd\proba_trait;

include 'proba_trait.php';
//defined( '_MOTTO' ) or die( 'Restricted access' );
class proba{

    public $gg=15;
    use proba_trait;
    public function egyes(){
        echo 'egyes';
    }

}
 $ff=new proba();
$ff->egyes_futtat();
$ff::egyes_futtat_stat();