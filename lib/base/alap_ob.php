<?php
namespace lib\base;
defined( '_MOTTO' ) or die( 'Restricted access' );

class AlapOB
{
   // use kettes;
    function __construct($parT)
    {
        $this->feltolt($parT);

    }

    public function feltolt($param = array())
    {
        $class_vars = get_class_vars(get_class($this));
        if(isset($param['gobT'])){$this->gob_feltolt($param['gobT']);}
        foreach ($class_vars as $name => $value)
        {
            if(isset($param[$name])){$this->$name=$param[$name];}
        }
    }
    public function gob_feltolt($gob)
    {
        $class_vars = get_class_vars(get_class($this));
     //$class = new \ReflectionClass($gob);
        foreach ($class_vars as  $name => $value)
        {
          // $value=$gob->getStaticPropertyValue($name);
         if(isset($gob::$$name)){$this->$name=$gob::$$name;}
        }
    }

}
 trait egyes
 {
     public function res(){return $this->par1;}

 }
trait kettes
{
    public function res(){return $this->par2;}

}

