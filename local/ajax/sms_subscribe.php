<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $USER;

global $SEND_EMAIL_ABOUT_SMS_SUBSCRIBE;
	$SEND_EMAIL_ABOUT_SMS_SUBSCRIBE = true;
if($_POST['sms'] == 'true'){
	
	$sms = true;
} else {

	$sms = false;
}

$user = new CUser;
$user->Update($USER->getID(), array('UF_SMS'=>$sms));