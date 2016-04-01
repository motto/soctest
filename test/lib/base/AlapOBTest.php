<?php
/**
 * Created by PhpStorm.
 * User: dolgozo
 * Date: 2016.03.29.
 * Time: 20:20
 */

namespace lib\base;
class POB{
    static  public $par1='egyes';
    static  public $par2=2;
    static  public $par3=null;
}

require 'G:\www\soctest\lib\base\alap_ob.php';

class ob extends AlapOB
{
    public $par1;
    public $par2;
}

class tr1 extends ob
{
    public $par1;
    public $par2;
    use egyes;
}
class tr2 extends ob
{
    public $par1;
    public $par2;

}

class AlapOBTest extends \PHPUnit_Framework_TestCase
{
    public  function testob()
    {   $paramT=['gobT'=>POB::class,'par1'=>'hh'];
        $ob=new ob($paramT);

        $this->assertEquals(
            $ob->par1,
            'hh'

        );
        $this->assertEquals(
            $ob->par2,
            POB::$par2

        );


    }
    public  function test_trait()
    {   $paramT=['gobT'=>POB::class,'par1'=>'hh'];
        $ob=new tr1($paramT);

        $this->assertEquals(
            $ob->res(),
            'hh'

        );
        $ob2=new tr2($paramT);
        $this->assertEquals(
            $ob2->res(),
            POB::$par2

        );


    }




}
