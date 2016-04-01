<?php
trait alap
{
    public function alap()
    {
        ADT::$dataT=\lib\db\DB::assoc_tomb(PAR::$alap_sql);
    }

}
