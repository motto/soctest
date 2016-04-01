<?php
namespace lib\db;
class GetSql
{
    public $tabla='';
    public $id='';
    public $idnev='';
    public $mezoT=array();
    public $dataT=array();

    public function __construct($tabla='', $id='', $idnev='', $mezoT=array(), $dataT=array())
    {
     // if($tabla!=''){$this->tabla = $tabla;}else{$this->tabla =}
        $this->tabla = $tabla;
        $this->id = $id;
        $this->idnev = $idnev;
        $this->mezoT = $mezoT;
        $this->dataT = $dataT;
        if(empty($dataT)){$this->dataT =$_POST;}else{$this->dataT = $dataT;}
    }


    public function pub ($tabla,$id,$id_nev='id')
    {
        return    $sql="UPDATE $this->tabla SET pub='0' WHERE $this->id_nev='$this->id'";

    }

    function unpub ($tabla,$id,$id_nev='id')
    {
        return   "UPDATE $this->tabla SET pub='1' WHERE $this->id_nev='$this->id'";
    }

    function del($tabla,$id,$id_nev='id')
    {
        return  "DELETE FROM $this->tabla WHERE $this->id_nev = '".$this->id."'";
    }

    public function beszur_postbol()
    {
        $ellenor_func='base';
        $value_string='';
        $mezo_string='';
        $sql='';
        foreach ($this->mezoT as $mezodata)
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
                    $value=$_POST[$postnev];
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
            $sql="INSERT INTO $this->tabla ($mezo_string2) VALUES ($value_string2)";
            //echo $sql;
        }

        return $sql;
    }

   public function frissit_postbol()
    { $sql='';
        $ellenor_func='base';
        $setek='';
        foreach ($this->mezoT as $mezodata)
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
            $sql = "UPDATE $this->tabla SET $setek2 WHERE id='$this->id'";
            //echo $sql;
        }
        return $sql;
    }
    public function select_sql($tabla,$id,$mezok='*')
    {
        $sql="SELECT $mezok FROM $this->tabla WHERE id='$this->id'";
        return $sql;
    }
}
