<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); 	
$APPLICATION->IncludeComponent(
	"goodde:yandex.turbo",
	"",
	Array(
		"IBLOCK_TYPE" => $_REQUEST['type'] ? htmlspecialcharsbx($_REQUEST['type']) : "", 
		"IBLOCK_ID" => $_REQUEST['id'] ? htmlspecialcharsbx($_REQUEST['id']) : "", 
		"SECTION_ID" => $_REQUEST['section_id'] ? htmlspecialcharsbx($_REQUEST['section_id']) : "",
		"SECTION_CODE" => $_REQUEST['section_code'] ? htmlspecialcharsbx($_REQUEST['section_code']) : "",
		"PROPERTY_CODE" => array(),
		"NUM_NEWS" => "1000", 
		"NUM_DAYS" => "", 
		"RSS_TTL" => "60",		
		"SORT_BY1" => "created_date", 
		"SORT_ORDER1" => "DESC", 
		"SORT_BY2" => "SORT", 
		"SORT_ORDER2" => "ASC", 
		"FILTER_NAME" => "",
		"CACHE_TYPE" => "A", 
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y", 
		"CACHE_FILTER" => "Y",
	)
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>