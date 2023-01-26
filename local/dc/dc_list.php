<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/dc/dc_functions.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/dc/dc_functions.php');
}
//$_SERVER['PHP_AUTH_USER'] = 'admin';
//$_SERVER['PHP_AUTH_PW'] = 'sVn7u&*l#Hs2hy';
$LOG_WRITE = true; // записсывать лог
$auth = LoginByHttpAuth();
if($auth['TYPE'] == 'ERROR'){
	echo $auth['MESSAGE'];
	exit();
}
global $USER;
global $DB;
$log_mess = date('d.m.Y H:i:s').'.--Запрос к dc_list.';
if($USER->isAdmin()){
	$res = Bitrix\Main\UserTable::getList([
		"select" => ["UF_DISCOUNT_JSON", "UF_DISCOUNT_CARD_STATUS", "UF_DISCOUNT_CARD_ID", "UF_SMS_DISCOUNT_CARD"],
		"filter" => array("!UF_DISCOUNT_JSON"=>false, "UF_DISCOUNT_CARD_STATUS"=>array(24,25,28,29))
	]);

	 $rsStatus = CUserFieldEnum::GetList(array(), array("USER_FIELD_NAME" =>'UF_DISCOUNT_CARD_STATUS'));
			while($arStatus = $rsStatus->GetNext()){
				$status_arr[$arStatus['ID']] =  $arStatus["XML_ID"];
			}
	while ($arRes = $res->fetch()) {
	
		
		
		$json = json_decode($arRes['UF_DISCOUNT_JSON'],true);
		
		$json = getFormatDCFields( $json );
		foreach($json as $k=>&$val){
			if($k=='dnd_sms' || $k=='dnd_email'){
				if($val == 1){
					$val = true;
				} else {
					$val = false;
				}
			} elseif($val == false){
				$val ='';
			}
		}
		$json['birthdate'] = date("Y-m-d", strtotime($json['birthdate']));
		if($arRes['UF_DISCOUNT_CARD_ID'] == false && $arRes['UF_DISCOUNT_CARD_STATUS'] == 25){
			$arRes['UF_DISCOUNT_CARD_STATUS'] = 24;
		}
		unset($arRes['UF_DISCOUNT_JSON']);
		$add_arr = ['dcid'=>$arRes['UF_DISCOUNT_CARD_ID'] ? $arRes['UF_DISCOUNT_CARD_ID'] : '', 'status'=>$status_arr[$arRes['UF_DISCOUNT_CARD_STATUS']]];
		$result[] = array_merge($json,$add_arr);
	}
	if(count($result)>1){
		echo $lm = json_encode($result,JSON_UNESCAPED_UNICODE);
	} else {
		echo $lm = json_encode($result[0],JSON_UNESCAPED_UNICODE);	
	}
} else {
	echo $lm = 'Недостаточно прав пользователя';
}
$log_mess .= '--Ответ: '.$lm. PHP_EOL .'<br>';

if($LOG_WRITE == true){
	$file = 'log.php';
	$current = explode('------',file_get_contents($file));
	$arr = explode(PHP_EOL,str_replace('<br>','',$current[1]));
	foreach($arr as $str){
		if(stristr($str,'--')){
			$str_arr = explode('--',$str);
			if(strtotime($str_arr[0])-strtotime(date('r', strtotime('-14 day'))) >0){
				$string_to_log = implode('--',$str_arr);
				$new_arr[] = $string_to_log.'<br>'.PHP_EOL;
			}
		}
	}
	
			
	$new_arr_to_str = $current[0].'------<br>'.implode(PHP_EOL, $new_arr);
	$new_arr_to_str .=$log_mess;
	file_put_contents($file, $new_arr_to_str);
}