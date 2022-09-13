<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$res = Bitrix\Main\UserTable::getList([
    "select" => ["UF_DISCOUNT_JSON", "UF_DISCOUNT_CARD_STATUS", "UF_DISCOUNT_CARD_ID", "UF_SMS_DISCOUNT_CARD"],
	"filter" => array("!UF_DISCOUNT_JSON"=>false, "UF_DISCOUNT_CARD_STATUS"=>array(20,19,22,23))
]);

 $rsStatus = CUserFieldEnum::GetList(array(), array("USER_FIELD_NAME" =>'UF_DISCOUNT_CARD_STATUS'));
        while($arStatus = $rsStatus->GetNext()){
            $status_arr[$arStatus['ID']] =  $arStatus["XML_ID"];
		}
while ($arRes = $res->fetch()) {
	$json = json_decode($arRes['UF_DISCOUNT_JSON'],true);
	unset($arRes['UF_DISCOUNT_JSON']);
	$add_arr = ['dcid'=>$arRes['UF_DISCOUNT_CARD_ID'], 'status'=>$status_arr[$arRes['UF_DISCOUNT_CARD_STATUS']]];
	$result = array_merge($json,$add_arr);
	echo json_encode($result,JSON_UNESCAPED_UNICODE);
}