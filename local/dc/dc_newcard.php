<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/dc/dc_functions.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/dc/dc_functions.php');
}
$auth = LoginByHttpAuth();
if($auth['TYPE'] == 'ERROR'){
	echo $auth['MESSAGE'];
	exit();
}
global $USER;
if($USER->isAdmin()){
	$json_code = file_get_contents('php://input');//' {"smscode":"4819","dcid":"CL000000000117","cellnum":"+7 222 222-2222"}';
	$data = json_decode($json_code,true);
	$user = getUserByPhone($data);
	$data['cellnum'] = getStandartPhone($data['cellnum']);
	file_put_contents($_SERVER["DOCUMENT_ROOT"].'/local/dc/log.txt', print_r($data, true));
	
	if ($user) {
		$fildz = array(
			'UF_DISCOUNT_CARD_ID'=> $data['dcid'],
			'UF_DISCOUNT_CARD_STATUS'=> 22,
			'UF_SMS_DISCOUNT_CARD' => $data['smscode'],
		);
		$user_upd = new CUser;
		if($user_upd->Update($user['ID'], $fildz)){
			echo $json_answer = '{"dc_status":"OK", "dcid": "'.$data['dcid'].'"}';
			$update = true;
		} else {
			$err = $user_upd->LAST_ERROR;
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