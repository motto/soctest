<?php
namespace mod\zaszlo;
use lib\base\LINK;

defined( '_MOTTO' ) or die( 'Restricted access' );
class Zaszlo
{
    public $hu_img ='tmpl/flat/images/hu.png';
    public $en_img = 'tmpl/flat/images/en.png';

    function eng_hu()
    {
        $nyelvcsere = 'hu';
        $img = $this->hu_img;
        if (\GOB::$lang == 'hu') {
            $nyelvcsere = 'en';
            $img = $this->en_img;
        }
        $link = LINK::getcsere('lang=' . $nyelvcsere);
        $result = '<a href="' . $link . '" ><img src="'.$img.'" height="30px;"></a>';
        return $result;
    }

    function eng_hu_link()
    {
        $result='<li>'.$this->eng_hu().'</li>';
        return $result;
    }
}