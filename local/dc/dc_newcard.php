<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$auth = LoginByHttpAuth();
if($auth['TYPE'] == 'ERROR'){
	echo $auth['MESSAGE'];
	exit();
}
global $USER;
if($USER->isAdmin()){
	$json_code = file_get_contents('php://input');//'{"phone":"+7 (918) 111-11-11", "smscode": "1235", "dcid": "BD0000025"}';
	$data = json_decode($json_code,true);
	$res = Bitrix\Main\UserTable::getList([
		"select" => ["ID", "ACTIVE"],
		"filter" => array('PERSONAL_PHONE'=>$data['cellnum']),
	]);
	while ($arRes = $res->fetch()) {
		$fildz = array(
			'UF_DISCOUNT_CARD_ID'=> $data['dcid'],
			'UF_DISCOUNT_CARD_STATUS'=> 22,
			'UF_SMS_DISCOUNT_CARD' => $data['smscode'],
		);
		$user = new CUser;
		if($user->Update($arRes['ID'], $fildz)){
			echo $json_answer = '{"dc_status":"OK", "dcid": "'.$data['dcid'].'"}';
			$update = true;
		} else {
			$err = $user->LAST_ERROR;
		}
		
	}

	if(!$update){
		if($err){
			echo $json_answer = '{"dc_status":"ERROR", "dcid": "'.$data['dcid'].'", "message": "'.$err.'"}';
		} else {
			echo $json_answer = '{"dc_status":"ERROR", "dcid": "'.$data['dcid'].'", "message": "Ошибка при обновлении"}';
		}
	}
} else {
	echo 'Недостаточно прав пользователя';
}