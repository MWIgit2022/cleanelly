<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/dc/dc_functions.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/dc/dc_functions.php');
}
$json_code = file_get_contents('php://input');//'{"cellnum":"+7 (918) 111-11-11"}';
$data = json_decode($json_code,true);
$data['cellnum'] = getStandartPhone($data['cellnum']);
$user = getUserByPhone($data);
if ($user) {
	$fildz = array(
		'UF_DISCOUNT_CARD_STATUS'=> 21,
	);
	$user_upd = new CUser;
	if($user_upd->Update($user['ID'], $fildz)){
		echo $json_answer = '{"dc_status":"OK", "dcid":"'.$user['UF_DISCOUNT_CARD_ID'].'", "message":""}';
	} else {
		$err = true;
		$mess = 'Пользователь найден, но обновление не удалось';
	}
} else {
	$err = true;
	$mess = 'Пользователь не найден';
}

if($err){
	echo $json_answer = '{"dc_status":"error", "dcid":"'.$user['UF_DISCOUNT_CARD_ID'].'", "message":"'.$mess.'"}';
}