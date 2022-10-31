<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/dc/dc_functions.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/dc/dc_functions.php');
}
$res = Bitrix\Main\UserTable::getList([
    "select" => ["ID", "ACTIVE", "UF_SMS_DISCOUNT_CARD"],
    "filter" => array('PERSONAL_PHONE'=> getStandartPhone($_POST['phone']),
]);

while ($arRes = $res->fetch()) {
	$fildz = array(
		'UF_DISCOUNT_CARD_STATUS'=> 20,
	);
	$user = new CUser;
	if($user->Update($arRes['ID'], $fildz)){
		$update = true;
	}
}
 

if($update){
	echo '<p class="success">Смс отправлена повторно.</p>';
}