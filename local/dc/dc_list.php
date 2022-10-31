<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/dc/dc_functions.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/dc/dc_functions.php');
}
//$_SERVER['PHP_AUTH_USER'] = 'admin';
//$_SERVER['PHP_AUTH_PW'] = 'sVn7u&*l#Hs2hy';
$auth = LoginByHttpAuth();
if($auth['TYPE'] == 'ERROR'){
	echo $auth['MESSAGE'];
	exit();
}
global $USER;
if($USER->isAdmin()){
	$res = Bitrix\Main\UserTable::getList([
		"select" => ["UF_DISCOUNT_JSON", "UF_DISCOUNT_CARD_STATUS", "UF_DISCOUNT_CARD_ID", "UF_SMS_DISCOUNT_CARD"],
		"filter" => array("!UF_DISCOUNT_JSON"=>false, "UF_DISCOUNT_CARD_STATUS"=>array(20,19,23))
	]);

	 $rsStatus = CUserFieldEnum::GetList(array(), array("USER_FIELD_NAME" =>'UF_DISCOUNT_CARD_STATUS'));
			while($arStatus = $rsStatus->GetNext()){
				$status_arr[$arStatus['ID']] =  $arStatus["XML_ID"];
			}
	while ($arRes = $res->fetch()) {
	
		
		
		$json = json_decode($arRes['UF_DISCOUNT_JSON'],true);
		
		$json = getFormatDCFields( $json );

		
		unset($arRes['UF_DISCOUNT_JSON']);
		$add_arr = ['dcid'=>$arRes['UF_DISCOUNT_CARD_ID'], 'status'=>$status_arr[$arRes['UF_DISCOUNT_CARD_STATUS']]];
		$result[] = array_merge($json,$add_arr);
	}
	if(count($result)>1){
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	} else {
		echo json_encode($result[0],JSON_UNESCAPED_UNICODE);	
	}
} else {
	echo 'Недостаточно прав пользователя';
}