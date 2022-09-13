<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$json_code = '{"phone":"+7 (918) 111-11-11"}';
$data = json_decode($json_code,true);
$res = Bitrix\Main\UserTable::getList([
    "select" => ["ID", "ACTIVE"],
    "filter" => array('PERSONAL_PHONE'=>$data['phone']),
]);
while ($arRes = $res->fetch()) {
	$fildz = array(
		'UF_DISCOUNT_CARD_STATUS'=> 21,
	);
	$user = new CUser;
	if($user->Update($arRes['ID'], $fildz)){
		echo $json_answer = '{"dc_status":"OK"}';
	}
}