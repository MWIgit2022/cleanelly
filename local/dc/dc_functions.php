<?php
use Bitrix\Sale;
global $DB;
	
	
function getFormatDCFields( $arData ) {

	if ( $order = Sale\Order::load($arData['order']) ){
		$userID = $order->getUserId();
		$propertyCollection = $order->getPropertyCollection();
		if ( !$arData['cellnum'] ){
			$arData['cellnum'] = $phonePropValue = $propertyCollection->getPhone()->getValue();
		}
		$arData['cellnum'] = getStandartPhone( $arData['cellnum'] );
		$arData['birthdate'] = getStandartDate( $arData['birthdate'] );
		if ( !$arData['dnd_email'] ){
			$arData['dnd_email'] = false;
		}
	}
	
	return $arData;
}	

function getUserFromData( $arData ) {

	if ( $order = Sale\Order::load($arData['order']) ){
		$userID = $order->getUserId();
	}
	
	return $userID;
}
function getUserByPhone( $arData ) {
	
	$res = Bitrix\Main\UserTable::getList([
		"select" => ["ID", "PERSONAL_PHONE", "UF_DISCOUNT_JSON", "UF_DISCOUNT_CARD_STATUS", "UF_DISCOUNT_CARD_ID", "UF_SMS_DISCOUNT_CARD"],
		"filter" => array("!UF_DISCOUNT_JSON"=>false, "UF_DISCOUNT_CARD_STATUS"=>array(24,25,28))
	]);
	while ($arRes = $res->fetch()) {
		$data = json_decode($arRes['UF_DISCOUNT_JSON'],true);
		
		if(getStandartPhone($arRes['PERSONAL_PHONE']) == getStandartPhone($arData['cellnum'])){
			return $arRes;
			break;
		}
	}
}

function getStandartPhone( $phone = '', $format = '', $mask = '#' ) {
	
	$phone = preg_replace('/[^0-9]/', '', $phone);
	$format = '+# ### ###-####'; 
    $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

    $format = preg_replace_callback(
        str_replace('#', $mask, '/([#])/'),
        function () use (&$counter) {
            return '${' . (++$counter) . '}';
        },
        $format
    );
	
    return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
}	

function phoneBlocks($number){
    $add='';
    if (strlen($number)%2)
    {
        $add = $number[ 0];
        $add .= (strlen($number)<=5? "-": "");
        $number = substr($number, 1, strlen($number)-1);
    }
    return $add.implode("-", str_split($number, 2));
}


function getStandartDate( $date ) {
	global $DB;
	return $DB->FormatDate($date, "DD.MM.YYYY", "YYYY-MM-DD");
}
?>