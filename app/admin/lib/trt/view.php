<?php
trait alap
{
    public function alap()
    {
      ADT::$view=\lib\db\DB::assoc_tomb(PAR::$alap_sql);
    }

}