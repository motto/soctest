<?php
namespace app\admin\lib\trt;
trait alap
{
    public function alap()
    {
        Data::alap(PAR::$alap_sql);
        // ADT::$datatabla=MOD::tabla(ADT::$tabla_szerk,ADT::$datatomb);
        View::alap();
    }

}
trait ment
{
    public function ment()
    {
        if (!empty(ADT::$id))
        {
            Data::ment();
        }
        else
        {
            Data::beszur();
        }
        if(empty(ADT::$hibaT))
        {$this->alap();}
        else{$this->szerk();}
    }
}
trait mentuj
{
    public function mentuj()
    {
         if(!empty(ADT::$id))
        {
            Data::ment();
        }else
        {
            Data::beszur();
        }
        $this->uj();
    }
}

trait cancel
{
    public function cancel(){$this->alap();}
}
trait uj
{
    public function uj(){View::urlap(); }
}
trait szerk
{ 
    public function szerk()
    {
    Data::datasor(ADT::$datasor_sql);
    View::urlap();
    }

}


trait torol
{
    public function torol()
    {
        DB::tobb_del(ADT::$tablanev,ADT::$idT);
        $this->alap();
    }

}

trait pub
{
    public function pub()
    {
        DB::tobb_pub(ADT::$tablanev,ADT::$idT);
        $this->alap();
    }
}
trait unpub
{
    public function unpub(){DB::tobb_unpub(ADT::$tablanev,ADT::$idT);
        $this->alap();}
}

trait joghiba
{
    public function joghiba()
    {
        if($_SESSION['userid']==0)
        {
            ADT::$view=MOD::login();
        } 
        else
        {
            ADT::$view='<h2><!--#joghiba--></h2>';}
        }


}