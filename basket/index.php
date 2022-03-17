<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
$APPLICATION->SetPageProperty("viewed_show", "Y");

$basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

foreach ($basket as $basketItem) {
	$pid = $basketItem->getField('PRODUCT_ID');
	   if($basketItem->isDelay() == true){
		   $inwished_arr[$pid] = $pid;
	   } else {
		 $basket_arr[$pid] = $pid; 
	   }
}
if(count($basket_arr)>0){
	OnBasketUpdateHandler();
}

?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket",
	"not_default_1",
	Array(
		"ACTION_VARIABLE" => "action",
		"AJAX_MODE_CUSTOM" => "Y",
		"AUTO_CALCULATION" => "Y",
		"COLUMNS_LIST" => array(0=>"NAME",1=>"DISCOUNT",2=>"PROPS",3=>"DELETE",4=>"DELAY",5=>"TYPE",6=>"PRICE",7=>"QUANTITY",8=>"SUM",),
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_CONVERT_CURRENCY" => "N",
		"GIFTS_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_HIDE_NOT_AVAILABLE" => "N",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		"GIFTS_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_PLACE" => "BOTTOM",
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "undefined",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_TEXT_LABEL_GIFT" => "Подарок",
		"HIDE_COUPON" => "N",
		"OFFERS_PROPS" => array(0=>"SIZES",1=>"COLOR_REF",),
		"PATH_TO_ORDER" => SITE_DIR."order/",
		"PICTURE_HEIGHT" => "100",
		"PICTURE_WIDTH" => "100",
		"PRICE_VAT_SHOW_VALUE" => "Y",
		"QUANTITY_FLOAT" => "N",
		"SET_TITLE" => "N",
		"SHOW_FAST_ORDER_BUTTON" => "Y",
		"SHOW_FULL_ORDER_BUTTON" => "Y",
		"SHOW_MEASURE" => "Y",
		"TEMPLATE_THEME" => "blue",
		"USE_GIFTS" => "Y",
		"USE_PREPAYMENT" => "N"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"basket",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"BIG_DATA_RCM_TYPE" => "bestsell",
		"COMPONENT_TEMPLATE" => "basket",
		"EDIT_TEMPLATE" => "standard.php",
		"PATH" => SITE_DIR."include/comp_basket_bigdata.php",
		"PRICE_CODE" => array(0=>"BASE",1=>"OPT",),
		"SALE_STIKER" => "SALE_TEXT",
		"STIKERS_PROP" => "HIT",
		"STORES" => array(0=>"1",1=>"2",2=>"",)
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>