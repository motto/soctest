<?php
namespace lib\base;
class VAL
{
    static public function safe($arr=array(),$key='',$val='')
    {
        if (isset($arr[$key]))
        {
            return $arr[$key];
        } else
        {
            return $val;
        }
    }

}