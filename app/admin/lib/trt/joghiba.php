<?php
namespace app\admin\lib\trt;
use app\admin\ADT;
use mod\MOD;

trait joghiba
{
    public function joghiba()
    {
        if($_SESSION['userid']==0)
        {
            ADT::$view=MOD::login();
        }
        else
        {
            ADT::$view='<h2><!--#joghiba--></h2>';}
    }


}