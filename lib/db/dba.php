<?php
namespace lib\db;
defined( '_MOTTO' ) or die( 'Restricted access' );
class DBA
{
    static public function parancs($sql)
    {
        if (\CONF::$sql_log != 'no') {
            \GOB::$log['sql'][] = $sql;
        }
        $result = true;
        try {
            $stmt = \GOB::$db->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {
            \GOB::$hiba['pdo'][] = $e->getMessage();
            $result = false;
        }

        return $result;
    }
    static public function beszur($sql)
    {if(\CONF::$sql_log!='no'){\GOB::$log['sql'][]=$sql;}
        $result = 0;
        try {
            $stmt = \GOB::$db->prepare($sql);

            $stmt->execute();
            $result=\GOB::$db->lastInsertId();

        } catch (PDOException $e) {
            \GOB::$hiba['pdo'][] = $e->getMessage();
            //echo $e->getMessage();
        }
        return $result;
    }
    static public function pub ($tabla,$id,$id_nev='id')
    {
        $sql="UPDATE $tabla SET pub='0' WHERE $id_nev='$id'";
        $sth =self::alap($sql);
        return $sth;
    }
    static public function tobb_pub ($tabla,$id_tomb,$id_nev='id')
    {
        foreach($id_tomb as $id){self::pub($tabla,$id,$id_nev); }
    }
    static public function unpub ($tabla,$id,$id_nev='id')
    {
        $sql="UPDATE $tabla SET pub='1' WHERE $id_nev='$id'";
        $sth =self::alap($sql);
        return $sth;
    }
    static public function tobb_unpub ($tabla,$id_tomb,$id_nev='id')
    {
        foreach($id_tomb as $id){self::unpub($tabla,$id,$id_nev); }
    }
    static public function del($tabla,$id,$id_nev='id')
    {
        $sql="DELETE FROM $tabla WHERE $id_nev = '".$id."'";
        $sth =self::alap($sql);
        return $sth;
    }
    static public function tobb_del($tabla,$id_tomb,$id_nev='id')
    {
        foreach($id_tomb as $id){self::del($tabla,$id,$id_nev); }
    }
    /** $mezok: array(array('postnev','mezonev(ha más mit a postnév)','ellenor_func(nem kötelező)'))
     * ha az ellenőr funkció false-al tér vissza azt a mezőt kihagyja,
     * üres mezőt (illetve üres posztot)is felvisz,
     * visszatér a beszurt id-el vagy 0-val ha nem sikerult felvinni

     */
    static public function beszur_postbol($tabla,$mezok=array())
    {
        $ellenor_func='base';
        $value_string='';
        $mezo_string='';
        foreach ($mezok as $mezodata)
        {
            $mezonev=$mezodata['mezonev'];
            if(isset($mezodata['postnev'])&& $mezodata['postnev']!='')
            {
                $postnev=$mezodata['postnev'];
            }
            else
            {
                $postnev=$mezodata['mezonev'];
            }
            if(isset($mezodata['ell'])){$ellenor_func=$mezodata['ell'];}
            if(isset($_POST[$postnev]))
            {

                if($mezonev=='password')
                {
                    $value=md5($_POST[$mezonev]);

                }
                else
                {
                    $value=$_POST[$postnev];
                }
            }
            else
            {
                $value='';
            }


            if (AppEll::$ellenor_func($value))
            {
                $value_string = $value_string . "'" . $value . "',";
                $mezo_string = $mezo_string . $mezonev . ",";
            }

        }
        if($mezo_string!='')
        {
            $mezo_string2=rtrim($mezo_string,',');
            $value_string2=rtrim($value_string,',');
            $sql="INSERT INTO $tabla ($mezo_string2) VALUES ($value_string2)";
            //echo $sql;
            $result=DB::beszur($sql);
        }
        else
        {
            $result='0';
        }
        return $result;
    }
    /** $mezok: array(array('postnev','mezonev(ha más mit a postnév)','ellenor_func(nem kötelező)'))
     * ha az ellenőr funkció false-al tér vissza azt a mezőt kihagyja,
     * üres mezőt (illetve üres posztot)is felvisz.
     * @param $tabla
     * @param $id
     * @param array $mezok
     * @return PDOStatement
     */
    static public function frissit_postbol($tabla,$id,$mezok=array())
    {
        $ellenor_func='base';
        $setek='';
        foreach ($mezok as $mezodata)
        {
            $value='';
            $mezonev=$mezodata['mezonev'];
            if(isset($mezodata['postnev'])&& $mezodata['postnev']!='')
            {
                $postnev=$mezodata['postnev'];
            }
            else
            {
                $postnev=$mezodata['mezonev'];
            }
            if(isset($mezodata['ell']))
            {
                $ellenor_func=$mezodata['ell'];

            }
            if(AppEll::$ellenor_func($value))
            {
                if (isset($_POST[$postnev]))
                {
                    $value = $_POST[$postnev];
                }
                $setek = $setek . $mezonev . "='" . $value . "', ";
                //echo $setek;
            }


        }
        if($setek !='')
        {
            $setek2 = substr($setek, 0, -2);
            $sql = "UPDATE $tabla SET $setek2 WHERE id='$id'";
            //echo $sql;
            $result = DB::beszur($sql);
        }
        return $result;
    }
    static public function select_sql($tabla,$id,$mezok='*')
    {
        $sql="SELECT $mezok FROM $tabla WHERE id='$id'";
        return $sql;
    }

}