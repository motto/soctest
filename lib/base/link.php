<?php
namespace lib\base;
defined( '_MOTTO' ) or die( 'Restricted access' );
class LINK
{
    /**
     * a kép neve elé teszi a /thumb-ot (thumb elérési útját állítja elő
     * @param $src
     * @return string
     */
    static public function thumb_src($src)
    {
///$path_parts = pathinfo('/www/htdocs/inc/lib.inc.php');
        //$path_parts['dirname'] /www/htdocs/inc
        //$path_parts['basename'] lib.inc.php
        //$path_parts['extension'] php
        //$path_parts['filename'] lib.inc
        $path_parts = pathinfo($src);
        $ujsrc=$path_parts['dirname'].'/thumb/'.$path_parts['basename'];
        return  $ujsrc;
    }


    static public function getcsere($csere,$link='')
    {
        if($link==''){$link=$_SERVER['REQUEST_URI'];}
        // echo $link;
        $linktomb=explode('&',parse_url($link, PHP_URL_QUERY));
        // print_r($linktomb);
        if(empty($linktomb[0]))
        {
            return parse_url($link, PHP_URL_PATH).'?'.$csere;
        }
        else
        {
            $csereT=explode('=',$csere);
            $get_resz='';
            foreach($linktomb as $tag)
            {
                $altag = explode('=', $tag);
                if (($altag[0] != $csereT[0])&& isset($altag[1]))
                {
                    $get_resz = $get_resz . $altag[0].'='.$altag[1].'&';
                }
            }
            //$get_resz =substr($get_resz, 0, -1);
            $get_resz = $get_resz.$csere;
            return parse_url($link, PHP_URL_PATH).'?'.$get_resz;
        }
    }

}