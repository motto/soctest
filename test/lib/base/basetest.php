<?php
/**
 * Created by PhpStorm.
 * User: dolgozo
 * Date: 2016.03.26.
 * Time: 16:46
 */

namespace lib\base;
require 'G:\www\soctest\lib\base\base.php';

class BASETest extends \PHPUnit_Framework_TestCase
{
public function divisionProvider()
{
    return array(
array(['egy'=>'egyes'], 'egy' ,null, 'egyes'),
array(['egy'=>'kettes'], 'egy', null,'kettes'),
array(['egy'=>'kettes'], 'ketto',null, null),
array(['egy'=>'kettes'], 'ketto','ff','ff' ),
array(['egy'=>'kettes'], null,null, null),
array(['egy'=>'kettes'], null,'ff','ff'),
array(['egy'=>'kettes'],'egy','ff','kettes'),
array('', 'egy', false , false),
array('', 'egy', 'ff' , 'ff')
);
}

/**
 * @param $arr
 * @param $key
 * @param $return
 * @dataProvider divisionProvider
 */
public  function testtofalse($arr,$key,$val,$return)
{
    $this->assertEquals(
        $return,
        Alap::tofalse($arr,$key,$val)
    );
}
}
