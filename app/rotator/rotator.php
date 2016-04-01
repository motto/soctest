<?php
class AppS{
        static public function mod_feltolt($view)
        {
                preg_match_all("/<!--:([^`]*?)-->/", $view, $matches);
                $mezotomb = $matches[1];
                if (is_array($mezotomb)) {
                        foreach ($mezotomb as $mezo) {
                                $view = str_replace('<!--:' . $mezo . '-->', MOD::$mezo(), $view);
                        }
                }
                return $view;
        }
}
GOB::$html=file_get_contents('tmpl/flat/rotator.html', true);
GOB::$html=FeltoltS::from_LT(GOB::$html);
GOB::$html=FeltoltS::mod(GOB::$html);
if($_SESSION['userid']>0)
{
        $tartalom=MOD::rotator();
}
else
{
        $tartalom=MOD::login();
}



//lap generálás a tartalommal-----------------------------------------
GOB::$html= str_replace('<!--|tartalom|-->',$tartalom ,GOB::$html);
echo GOB::$html;