<?php
namespace lib\db;
defined( '_MOTTO' ) or die( 'Restricted access' );
class DB
{
    static public function assoc_tomb($sql)
    {
        if(\CONF::$sql_log='full'){\GOB::$log['sql'][]=$sql;};
        $result = array();
        try {
            $stmt = \GOB::$db->prepare($sql);

            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                $result[] = $row;
            }
        } catch (PDOException $e) {
            \GOB::$hiba['pdo'][] = $e->getMessage();
        }
        return $result;
    }

    static public function assoc_sor($sql)
    {
        if(\CONF::$sql_log='full'){\GOB::$log['sql'][]=$sql;}
        $result = array();
        try {
            $stmt = \GOB::$db->prepare($sql);

            $stmt->execute();
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $stmt->fetch();
            if (!empty($row)) {
                $result = $row;
            }

        } catch (PDOException $e) {
            \GOB::$hiba['pdo'][] = $e->getMessage();
        }
        return $result;
    }

}