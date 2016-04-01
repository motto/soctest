<?php
namespace lib\base;
defined( '_MOTTO' ) or die( 'Restricted access' );
class TOMB {
    /**
     * ['id'=>'user1','nev'=>'otto']
     * ból:[user1=>['id'=>'user1','nev'=>'otto']
     * a kulcsmező értékét kiemeli sor kulcsnak;
     * ha több egyforma érték is van, felülírja az ORDER BY-nak megfelellően
     */
    static public function mezobol_kulcs(array $tomb, $kulcsmezo='id'){
        $tomb2=array();
        foreach($tomb as $sor )
        {	$sorindex= $sor[$kulcsmezo];
            $tomb2[$sorindex]=$sor;
        }

        return $tomb2;
    }
    static public function to_string($tomb)
    {
        $str = '';
        foreach ($tomb as $key => $value)
        {
            if (is_array($value))
            {
                $str =$str.self::to_string($value);
            }
            else
            {
                $str = $str . $key . ': ' . $value . '\n </br>';
            }
        }
        return $str;
    }
    static public function kiir($tomb){
        foreach($tomb as $key=>$value){
            if(is_array($value)){self::kiir($value);}else{echo $key.': '.$value.'\n </br>';}}
    }
}