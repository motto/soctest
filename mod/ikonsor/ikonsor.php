<?php
namespace mod\ikonsor;

defined( '_MOTTO' ) or die( 'Restricted access' );
trait icon_from_glyph{
    public function geticon_old($mezo){
        $gl='tasks';
        $glyphT=array('szerk'=>'edit','uj'=>'plus','torol'=>'trash','pub'=>'ok-circle','unpub'=>'ban-circle','email'=>'envelope');
        if(isset($glyphT[$mezo])){$gl=$glyphT[$mezo];}
        return '<span style="font-size: 1.6em;margin-bottom:10px;" class="glyphicon glyphicon-'.$gl.'">';
    }
    public function geticon($gl='tasks')
    {
        return '<span style="font-size: 1.6em;margin-bottom:10px;" class="glyphicon glyphicon-'.$gl.'">';
    }
}

trait icon_from_image{

    public function geticon($mezo){
        $icondir='res/ico/32/';
        $noikon='noikon.png';
        $images=array('szerk'=>'edit.png','uj'=>'plusz.png','torol'=>'torol.png','pub'=>'published.png','unpub'=>'unpublished.png','email'=>'email.png');
        $img=$mezo;
        if(isset($images[$mezo]))
        {$img=$images[$mezo];}

        if(is_file($icondir.$img.'.png'))
        {$image=$icondir.$img.'.png';}
        else
        {$image=$icondir.$noikon;}
     return '<img src="'.$image.'"/>';
    }
}
trait lt_fromdb{
 public function feltolt(){}
}
trait lt_fromLT{
    public function feltolt(){}
}
class Ikonsor
{
    use lt_fromLT; //nyelvi tömböt hogyan tölti fel
    use icon_from_glyph;

    public $lt=array('uj'=>'Új','szerk'=>'Szerk','pub'=>'Pub','unpub'=>'Unpub','torol'=>'Töröl','email'=>'Email');

    public function result($ikonsorT)
    {
        $this->feltolt();

        $res='';
        foreach ($ikonsorT as $task=>$ikon ) {
            $icon=$this->geticon($ikon);
            if(isset($this->lt[$task]))
            {$label=$this->lt[$task];}
            else{$label=$task;}
            if($task=='torol'){ $oncl='onclick="return confirmSubmit()"';}
            else{$oncl='';}
            $res.='<button class="btkep" type="submit" name="task"  value="'.$task.'" '.$oncl.'>'.$icon.'</span></br>'.$label.'</button>';
        }

        return $res;
    }
}

