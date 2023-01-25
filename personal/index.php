<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Коллекции Cleanelly - возможность купить махровые и вафельные женские, мужские и детские халаты, полотенца, пледы и продукцию для кухни - весь домашний текстиль, выдержанный в едином стиле.");
$APPLICATION->SetPageProperty("keywords", "домашний текстиль, текстиль для кухни, махровый халат, детский махровый халат, мужской махровый халат, женский махровый халат, вафельный халат, женский вафельный халат, мужской вафельный халат, кухонные полотенца, вафельные полотенца, махровые полотенца, махровые полотенца оптом, полотенца оптом, купить халат, купить полотенце, Cleanelly Collection, Cleanelly Perfetto.");
$APPLICATION->SetTitle("Личный кабинет");
?>
<?
global $USER;
if(!$USER->isAuthorized()){
	LocalRedirect(SITE_DIR.'auth/');
}
else{
	//LocalRedirect(SITE_DIR.'personal/personal-data');?>
	<?
	
	$customPagesList[] = array(
		"/personal/loyalty-program/",
		"Программа лояльности",
		'<i class="contact"></i>'
	);
	$customPages = CUtil::PhpToJSObject($customPagesList);
	
	$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.section", 
	"main", 
	array(
		"ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS" => array(
			0 => "0",
		),
		"ACCOUNT_PAYMENT_PERSON_TYPE" => "1",
		"ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES" => "Y",
		"ACCOUNT_PAYMENT_SELL_TOTAL" => array(
			0 => "100",
			1 => "200",
			2 => "500",
			3 => "1000",
			4 => "5000",
			5 => "",
		),
		"ACCOUNT_PAYMENT_SELL_USER_INPUT" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_RIGHTS_PRIVATE" => "N",
		"COMPATIBLE_LOCATION_MODE_PROFILE" => "N",
		"CUSTOM_PAGES" => $customPages,
		"CUSTOM_SELECT_PROPS" => array(
		),
		"NAV_TEMPLATE" => "",
		"ORDER_HISTORIC_STATUSES" => array(
			0 => "P",
			1 => "F",
		),
		"PATH_TO_BASKET" => "/basket/",
		"PATH_TO_CATALOG" => "/catalog/",
		"PATH_TO_CONTACT" => "/contacts",
		"PATH_TO_PAYMENT" => "/order/payment/",
		"PER_PAGE" => "20",
		"PROP_1" => array(
		),
		"PROP_2" => "",
		"SAVE_IN_SESSION" => "Y",
		"SEF_FOLDER" => "/personal/",
		"SEF_MODE" => "Y",
		"SEND_INFO_PRIVATE" => "N",
		"SET_TITLE" => "Y",
		"SHOW_ACCOUNT_COMPONENT" => "Y",
		"SHOW_ACCOUNT_PAGE" => "Y",
		"SHOW_ACCOUNT_PAY_COMPONENT" => "Y",
		"SHOW_BASKET_PAGE" => "Y",
		"SHOW_CONTACT_PAGE" => "Y",
		"SHOW_ORDER_PAGE" => "Y",
		"SHOW_PRIVATE_PAGE" => "Y",
		"SHOW_PROFILE_PAGE" => "Y",
		"SHOW_SUBSCRIBE_PAGE" => "Y",
		"USER_PROPERTY_PRIVATE" => "",
		"USE_AJAX_LOCATIONS_PROFILE" => "N",
		"COMPONENT_TEMPLATE" => "main",
		"ACCOUNT_PAYMENT_SELL_CURRENCY" => "RUB",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"ORDER_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"ORDER_RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "P",
			1 => "F",
		),
		'SHOW_PROPS'=>array(
			'',
		),
		"ORDER_DEFAULT_SORT" => "STATUS",
		"ORDER_REFRESH_PRICES" => "N",
		"ORDERS_PER_PAGE" => "20",
		"PROFILES_PER_PAGE" => "20",
		"MAIN_CHAIN_NAME" => "Мой кабинет",
		"ORDER_DISALLOW_CANCEL" => "N",
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"orders" => "orders/",
			"account" => "account/",
			"subscribe" => "subscribe/",
			"profile" => "profiles/",
			"profile_detail" => "profiles/#ID#/",
			"private" => "private/",
			"order_detail" => "orders/#ID#/",
			"order_cancel" => "cancel/#ID#/",
		)
	),
	false
);?>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>