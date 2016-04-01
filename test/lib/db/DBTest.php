<?php
/**
 * Created by PhpStorm.
 * User: dolgozo
 * Date: 2016.03.29.
 * Time: 5:05
 */

namespace lib\db;
require 'G:\www\soctest\lib\db\db.php';
require 'G:\www\soctest\def.php';
class DBTest extends \PHPUnit_Framework_TestCase
{

    public  function testconnect()
    {
       \CONF::$adatbazis='test';
        $return=true;
        $return2=\GOB::$db;
       $this->assertEquals(
            $return,
            DB::connect()

        );
        $this->assertNotEquals(
            $return2,
           \GOB::$db

        );

    }
    public  function testparancs()
    {
        \CONF::$adatbazis='test'; DB::connect();

        $return=true;
        $return2=\GOB::$db;
        $this->assertEquals(
            $return,
            DB::connect()

        );
        $this->assertNotEquals(
            $return2,
            \GOB::$db

        );

    }
}
