<?php 	
$feladonev='admin.php';
$feladoemail='admin.php@infolapok.hu';
$cimemail='motto001@gmail.com';
$cimnev='Ottó';
$targy='proba';
$uzenet='<form enctype="multipart/form-data" action="alap.infolapok.hu" method="post">
<input class="text_area" type="text" name="felado" id="felado" size="50" maxlength="255" value="">
<input type="submit" name="submit" value="küldés"></form>';

/* "require": {
   "coinbase/coinbase": "dev-master"
 }*/
include 'class.phpmailer-lite.php';
if($_POST['task']=='mailkuld'){


$mail = new PHPMailerLite();
$mail->SetFrom($feladoemail);
$mail->AddAddress($cimemail);
//$email->AddAddress("ellen@example.com");                  // name is optional
//$email->WordWrap = 50;                                 // set word wrap to 50 characters
//$email->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$email->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);                                  // set email format to HTML
$mail->Subject = $targy;
$mail->Body    = $uzenet;

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}
echo "Message has been sent";
}





//require("fuggvenyek/class.phpmailer-lite.php");
//mail_kuld('motto001@gmail.com','sui sfuf eufuef ',$kuldocim='email@infolapok.hu',$subject='gggg',$kuldonev='',$cimnev='');
//echo marvan('userek','username','neoshin');
echo '<form enctype="multipart/form-data" action="" method="post">';
echo ' <table class="admintable">';
echo'	<tr><td  class="key">Feladó:</td>
						<td ><input class="text_area" type="text" name="feladonev" id="feladonev" size="50" maxlength="255" value="'.$feladonev.'"></td></tr>';
echo'	<tr><td  class="key">Feladó emailcím:</td>
						<td ><input class="text_area" type="text" name="felado" id="felado" size="50" maxlength="255" value="'.$feladoemail.'"></td></tr>';
						
echo'	<tr><td  class="key">Tárgy:</td>
						<td ><input class="text_area" type="text" name="subject" id="subject" size="50" maxlength="255" value="'.$targy.'"></td></tr>';						

echo'	<tr><td  class="key">E-email cím:</td>
						<td ><input class="text_area" type="text" name="cim" id="cim" size="50" maxlength="255" value="'.$cimemail.'"></td></tr>';
	
		 echo '	<tr><td  class="key"></td><td >';
         
echo ' </table >';
 echo '<br>	Ajánlás:<br>';
 
 echo '<input type="hidden" name="task" value="mailkuld">';
 echo '<br>	<br>	<input type="submit" name="submit" value="'.$gombfelirat.'">
 		<input type="button" name="Cancel" value="Mégsem" onclick="window.location = \''.$_SERVER['HTTP_REFERER'].' \' " /> 
    </form>';
























function mailkuld()
{
$recipient=JRequest::getVar('cim');
$from=JRequest::getVar('felado');
$fromname=JRequest::getVar('feladonev');
$subject=JRequest::getVar('subject');
$body=JRequest::getVar('uzenet','','','',JREQUEST_ALLOWHTML);
$mode = 1;	
	if (JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname)!== true) 
	{JError::raiseNotice( '500', JText::_('Nincs emailcím!') );}
}
function mailform($feladonev,$feladoemail,$targy,$cimemail,$uzenet,$gombfelirat='Üzenet elküldése')
{
echo '<form enctype="multipart/form-data" action="" method="post">';
echo ' <table class="admintable">';
echo'	<tr><td  class="key">Feladó:</td>
						<td ><input class="text_area" type="text" name="feladonev" id="feladonev" size="50" maxlength="255" value="'.$feladonev.'"></td></tr>';
echo'	<tr><td  class="key">Feladó emailcím:</td>
						<td ><input class="text_area" type="text" name="felado" id="felado" size="50" maxlength="255" value="'.$feladoemail.'"></td></tr>';
						
echo'	<tr><td  class="key">Tárgy:</td>
						<td ><input class="text_area" type="text" name="subject" id="subject" size="50" maxlength="255" value="'.$targy.'"></td></tr>';						

echo'	<tr><td  class="key">E-email cím:</td>
						<td ><input class="text_area" type="text" name="cim" id="cim" size="50" maxlength="255" value="'.$cimemail.'"></td></tr>';
	
		 echo '	<tr><td  class="key"></td><td >';
         
echo ' </table >';
$editor =& JFactory::getEditor();
 echo '<br>	Ajánlás:<br>';
 echo  $editor->display('uzenet', $uzenet, '450', '300', '75', '20', false, array( 'mode' => 'simple' ));
 
 echo '<input type="hidden" name="task" value="mailkuld">';
 echo '<br>	<br>	<input type="submit" name="submit" value="'.$gombfelirat.'">
 		<input type="button" name="Cancel" value="Mégsem" onclick="window.location = \''.$_SERVER['HTTP_REFERER'].' \' " /> 
    </form>';
}