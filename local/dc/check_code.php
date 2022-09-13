<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$res = Bitrix\Main\UserTable::getList([
    "select" => ["ID", "ACTIVE", "UF_SMS_DISCOUNT_CARD"],
    "filter" => array('PERSONAL_PHONE'=>'+'.trim($_POST['phone'])),
]);

while ($arRes = $res->fetch()) {
	if($_POST['code'] == $arRes['UF_SMS_DISCOUNT_CARD']){
		$fildz = array(
			'UF_DISCOUNT_CARD_STATUS'=> 23,
		);
		$user = new CUser;
		if($user->Update($arRes['ID'], $fildz)){
			$update = true;
		}
	} else {
		$update = false;
	}
} 

if($update){
	echo '<p class="success">Карта активирована, и будет доступна для использования в ближайшее время.</p>';
} else {
	echo '<p class="error">Не верный код</p>';
}