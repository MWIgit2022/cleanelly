<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Mail\Event;

if($_POST){
	Event::send(array(
	  "EVENT_NAME" => "DISCOUNT_CARD_MANAGER",
	  "LID" => "s1",
	  "C_FIELDS" => array(
		"ORDER" => $_POST['order'],
		"PHONE" => $_POST['phone'],
		"NAME" => $_POST['name'],
	  ),
	));
}