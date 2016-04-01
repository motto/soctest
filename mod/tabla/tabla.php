<?php
namespace mod\tabla;
defined( '_MOTTO' ) or die( 'Restricted access' );
class Tabla
{
    public  $datatomb;
    public  $dataszerk;
    public  $param;
    public  $fejlec=true;
    public  $rendez_sor=true;
    /**
     * table constructor.
     */
    public function __construct($dataszerk,$datatomb,$admin=true)
    {
        //$dataszerk=array("azonosító"=>"id","azonosító"=>"id");
        $this->admin=$admin;
        $this->dataszerk=$dataszerk;
        $this->datatomb=$datatomb;
    }
    public function mezo($data="")
    {
        $html="<td>".$data."</td>";
        return $html;
    }
    public function checkbox_mezo($id)
    {   //$checked_tomb = $_POST['sor'];
        $html="<input type=\"checkbox\" name=\"sor[]\" value=\"$id\" />";
        //  $html=$this->mezo($data);
        return $html;
    }
    public function pub_mezo($pub)
    {   //$checked_tomb = $_POST['sor'];
        if($pub>0){$src='/res/ico/16/unpublished.png';}
        else{$src='/res/ico/16/published.png';}
        $html='<image src="'.$src.'"/>';
        // $html=$this->mezo($data);
        return $html;
    }
    public function rendez_mezo($mezonev)
    {   //$checked_tomb = $_POST['sor'];
        $link=LINK::link_cserel('rendez='.$mezonev);
        $linkfel=LINK::get_cserel($link,'order=fel');
        $ico_fel='<a href="'.$linkfel.'"></a><image src="/res/ico/16/fel.png"/></a>';
        $linkle=LINK::get_cserel($link,'order=le');
        $ico_le='<a href="'.$linkle.'"></a><image src="/res/ico/16/le.png"/></a>';
        $html=$this->mezo($ico_fel.$ico_le);
        return $html;
    }
    public function sor($datasor)
    {
        $html='<tr>';
        foreach($this->dataszerk as $mezotomb)
        {
            $data=' ';
            switch($mezotomb['tipus']){
                case 'checkbox':
                    $data=$this->checkbox_mezo($datasor['id']);
                    break;
                case 'pubmezo':
                    $data=$this->pub_mezo($datasor['pub']);
                    break;
                default :
                    if(isset($datasor[$mezotomb['mezonev']]))
                    {$data=$datasor[$mezotomb['mezonev']];}


            }

            $html=$html.$this->mezo($data);
        }
        $html=$html.'</tr>';
        return $html;
    }
    public function fejlec()
    {
        $html="<tr style=\"background-color: royalblue; color: white; \" >";
        foreach($this->dataszerk as $mezotomb)
        {
            $html=$html.$this->mezo($mezotomb['cim']);
        }
        $html=$html."</tr>\n";
        return $html;
    }
    public function rendez_sor()
    {
        $html='<tr>';
        foreach($this->dataszerk as $mezotomb)
        {
            $data=$this->rendez_mezo($mezotomb['cim']);
            $html=$html.$this->mezo($data);
        }
        $html=$html.'</tr>';
        return $html;
    }
    public function __toString()
    {
        return $this->result();
    }

    public function result()
    {
        $html='<table>';
        if($this->fejlec){$html=$html.$this->fejlec();}
        //   if($this->rendez_sor){$html=$html.$this->rendez_sor();}
        foreach($this->datatomb as $datasor)
        {
            $usertomb=array('motto','admin.php');
            if(isset( $datasor['username'])&& in_array($datasor['username'],$usertomb)){}else{ $html=$html.$this->sor($datasor);}

        }
        $html=$html.'</table>';
        return $html;
    }

}