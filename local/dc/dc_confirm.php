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
	if($user_upd->Update($user, $fildz)){
		echo $json_answer = '{"dc_status":"OK"}';
	}
}