<?php
/**
 * Created by PhpStorm.
 * User: dolgozo
 * Date: 2016.03.27.
 * Time: 21:19
 */

namespace lib\base;
require 'G:\www\soctest\lib\base\val.php';

class VALTest extends \PHPUnit_Framework_TestCase
{
    public function divisionProvider()
    {
        return array(
            array(['egy'=>'egyes'], 'egy' ,null, 'egyes'),
            array(['egy'=>'egyes'], 'egy' ,'ketto', 'egyes'),
            array(['egy'=>'egyes'], 'egy' ,false, 'egyes'),
            array(['egy'=>'egyes'], 'egy' ,true, 'egyes'),
            array(['egy'=>'egyes']),

            array(['egy'=>'egyes'], '' ,null, null),
            array(['egy'=>'egyes'], '' ,'ketto', 'ketto'),
            array(['egy'=>'egyes'], '' ,false, false),
            array(['egy'=>'egyes'], '' ,true, true),
            array(['egy'=>'egyes'], '' ,array(),array()),

            array(['egy'=>'egyes'], false ,null, null),
            array(['egy'=>'egyes'], false,'ketto', 'ketto'),
            array(['egy'=>'egyes'], false ,false, false),
            array(['egy'=>'egyes'], false ,true, true),
            array(['egy'=>'egyes'], false ,array(),array()),

            array(null, null ,null, null),
            array(null, false,'ketto', 'ketto'),
            array([], false ,false, false),
            array([], false ,true, true),
            array([], 0 ,array(),array()),


            array(['egy'=>'kettes'], 'egy', null,'kettes'),
            array(['egy'=>'kettes'], 'ketto',null, null),
            array(['egy'=>'kettes'], 'ketto','ff','ff' ),
            array(['egy'=>'kettes'], null,null, null),
            array(['egy'=>'kettes'], null,'ff','ff'),
            array(['egy'=>'kettes'],'egy','ff','kettes'),
            array('', 'egy', false , false),
            array(null, null, false , false),
            array(null, null, null ,null),
            array(),
            array('', 'egy', 'ff' , 'ff')
        );
    }
    /**
     * @param $arr
     * @param $key
     * @param $return
     * @dataProvider divisionProvider
     */
    public  function testsafe($arr=array(),$key='',$val='',$return='')
    {
        $this->assertEquals(
            $return,
          VAL::safe($arr,$key,$val)

        );
    }

}
