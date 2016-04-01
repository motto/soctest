<?php
namespace mod;
namespace email;
class MLT
{
    public  static $elkuldve=[['hu'=>'Elküldve!'],
                               ['en'=>'Send succesfull!']];
    public  static $sikertelen=[['hu'=>'Levél küldés nem sikerült!'],
                                ['en'=>'Send error!']];


}
class MPAR
{
    public  static $viewFT=[['alap'=>'mod/email/view/contact.html'],
                            ['contact'=>'mod/email/view/contact.html']];
    public  static $viewT=[['elkuldve'=>'<h4><!--##elkuldve--></h4>'],
                           ['Hiba'=>'<h4><!--##sikertelen--></h4>']];

}
class MGOB
{
public  static $view='';
}
class view
{
public static function betolt($view)
{
    MGOB::$view=MPAR::$viewT[$view];
}
public static function betoltF($viewF)
{
    MGOB::$view=file_get_contents(MPAR::$viewFT[$viewF], true);
}

}
class Email
{
    public function kuld()
    {
        return MGOB::$view;
    }
    public function alap()
    {
        return MGOB::$view;
    }

    public function result()
    {
     return MGOB::$view;
    }
}