<?php
namespace lib\db;
defined( '_MOTTO' ) or die( 'Restricted access' );
class Connect
{
    static public function connect()
    {
        $res = true;
        try {
            $db = new \PDO("mysql:dbname=" . \CONF::$adatbazis . ";host=" . \CONF::$host, \CONF::$felhasznalonev, \CONF::$jelszo, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
            \GOB::$db = $db;
            //$db->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        } catch (PDOException $e) {
            die(\GOB::$hiba['pdo'] = "Adatbazis kapcsolodasi hiba: " . $e->getMessage());
            $res = false;
        }
        return $res;
    }
}