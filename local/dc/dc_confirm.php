<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/dc/dc_functions.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/dc/dc_functions.php');
}
$LOG_WRITE = true; // записсывать лог
$auth = LoginByHttpAuth();
if($auth['TYPE'] == 'ERROR'){
	echo $auth['MESSAGE'];
	exit();
}
global $USER;
$log_mess = date('d.m.Y H:i:s').'.--Запрос к dc_confirm:'.file_get_contents('php://input');
if($USER->isAdmin()){
	$json_code = file_get_contents('php://input');//'{"cellnum":"+7 (918) 111-11-11"}';
	$data = json_decode($json_code,true);
	$data['cellnum'] = getStandartPhone($data['cellnum']);
	$user = getUserByPhone($data);
	if ($user) {
		$fildz = array(
			'UF_DISCOUNT_CARD_STATUS'=> 26,
		);
		$user_upd = new CUser;
		if($user_upd->Update($user['ID'], $fildz)){
			echo $lm = $json_answer = '{"dc_status":"OK", "dcid":"'.$user['UF_DISCOUNT_CARD_ID'].'", "message":""}';
		} else {
			$err = true;
			$mess = 'Пользователь найден, но обновление не удалось';
		}
	} else {
		$err = true;
		$mess = 'Пользователь не найден';
	}

	if($err){
		echo $lm = $json_answer = '{"dc_status":"error", "dcid":"'.$user['UF_DISCOUNT_CARD_ID'].'", "message":"'.$mess.'"}';
	}
} else {
	echo $lm = 'Недостаточно прав пользователя';
}
$log_mess .= '--Ответ: '.$lm. PHP_EOL.'<br>';

if($LOG_WRITE == true){
	$file = 'log.php';
	$current = file_get_contents($file);
	$current .=$log_mess;
	file_put_contents($file, $current);
}