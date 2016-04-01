<?php
namespace lib\jog;
use lib\db\DB;
defined( '_MOTTO' ) or die( 'Restricted access' );
class Jog
{
    public static function fromGOB()
    {
        $userjog=array('noname');
        if(isset($_SESSION['userid']))
        {
            $userid=$_SESSION['userid'];
            if($userid>0)
            {
                $userjog[]='user';
            }

            //szerzo jog beállítása-----------
            if(in_array($userid,\CONF::$adminok))
            {
                $userjog[]='admin.php';
            }

            return $userjog;
        }
    }
    public static function fromDB(){

    }
}

