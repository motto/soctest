<?php
class Rview
{//$varolista='<div id="infoablak">';public static $fejlec=

    public static $fejlec='<div class="varodiv">
                            <div class="varolink">Link</div>
                            <div class="varopont">Pont</div>
                            <div class="varopont">perc</div>
                            <div style="clear: both;"></div>
                         </div>' ;

    public static function varolista()
    {
        $varolista ='<div id="infoablak">';
        $varolista = $varolista .self::$fejlec;
        foreach (ADT::$linktomb as $varosor)
        {
        $varolista = $varolista . self::varolink($varosor['link'], $varosor['pont'], $varosor['hatravan'],$varosor['id']);
        }
        $varolista= $varolista.'</div>';
        return $varolista;
    }

   public static function varolink($link,$pont,$perc,$id)
    {
        $result='<div id="'.$id.'" class="varodiv">
                <div class="varolink"><a href="index.php?app=rotator&id='.$id.'" >'.parse_url($link, PHP_URL_HOST).'</a></div>
                <div class="varopont">'.$pont.'</div>
                <div class="varoperc">'.$perc.'</div>
                <div style="clear: both;"></div>
             </div>' ;
        return $result;
    }
}