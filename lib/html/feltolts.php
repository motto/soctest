<?php
namespace lib\html;
use mod\MOD;

defined( '_MOTTO' ) or die( 'Restricted access' );

class FeltoltS{

    public static function hibakiir($hibatomb){
        $res='';
        foreach ($hibatomb as $hiba){
            $res =$res.'<h4>'.$hiba.'</h4>';
        }
        return $res;
    }
    static public function mod($view)
    {
        preg_match_all ("/<!--:([^`]*?)-->/",$view , $matches);
        $mezotomb=$matches[1];
        if(is_array($mezotomb))
        {
            foreach($mezotomb as $mezo)
            {
                $view= str_replace('<!--:'.$mezo.'-->', MOD::$mezo(), $view);
            }
        }
        return $view;
    }
    static public function data($view,$datatomb=array())
    {

        if(is_array($datatomb))
        {
            foreach($datatomb as $mezonev=>$mezoertek)
            {
                if(isset($mezonev))
                {
                 $view= str_replace('<!--#'.$mezonev.'-->',$mezoertek, $view);
                }
            }
        }
        return $view;
    }



    public static function LT($view,$datatomb)
    {
        foreach($datatomb as $datasor)
        {
            if( isset($datasor['nev']))
            {
                $csere_str='<!--##'.$datasor['nev'].'-->';
                $view= str_replace($csere_str,$datasor[\GOB::$lang] , $view);
            }

        }
        //}
        return $view;
    }

    public static function tisztit($view)
    {
        $view=self::data_tisztit($view);
        $view=self::LT_tisztit($view);
        return $view;
    }
    public static function LT_tisztit($view)
    {
        preg_match_all ("/<!--##([^`]*?)-->/",$view , $matches);
        $cseretomb=$matches[1];

        foreach($cseretomb as $mezonev)
        {

            $csere_str='<!--##'.$mezonev.'-->';
            $view= str_replace($csere_str,'', $view);


        }
        return $view;
    }
    public static function data_tisztit($view)
    {
        preg_match_all ("/<!--#([^`]*?)-->/",$view , $matches);
        $cseretomb=$matches[1];

        foreach($cseretomb as $mezonev)
        {

            $csere_str='<!--#'.$mezonev.'-->';
            $view= str_replace($csere_str,'', $view);


        }
        return $view;
    }

}