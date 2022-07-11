<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подписка на новости");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:subscribe.edit",
	"main",
	Array(
		"AJAX_MODE" => "N",
		"SHOW_HIDDEN" => "N",
		"ALLOW_ANONYMOUS" => "Y",
		"SHOW_AUTH_LINKS" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"SET_TITLE" => "N",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N"
	),
false
);?>
<?
global $USER;
$rsUser = CUser::GetByID($USER->GetID()); 
$arUser = $rsUser->Fetch();

?>
<h3>SMS уведомления</h3>
<div class="form">
	<div class="licence_block filter label_block">
		<input type="checkbox" id="licenses_popup_OCB" name="sms" required="" <?if($arUser['UF_SMS'] == '1'){?>checked<?}?> aria-required="true">
		<label for="licenses_popup_OCB" class="license">
			Я согласен получать смс- рассылку с номером заказа, информированием об акциях и тп.
		</label>
	</div>
</div>

<script>
$('input[name="sms"]').on('change', function(){
	$.ajax({
			type: "POST",
			url: "/local/ajax/sms_subscribe.php",
			data: 'sms='+$(this).prop('checked'),
			success: function(html){
				console.log(html);
			}
		 })
});
</script>
<?if((CNext::checkVersionModule('16.5.3', 'catalog') && !$GLOBALS['USER']->isAuthorized()) || $GLOBALS['USER']->isAuthorized()):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.product.subscribe.list",
		"main",
		Array(
			"PRICE_CODE" => array(
				0 => "BASE",
			),
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => "Y",
			"PRICE_VAT_SHOW_VALUE" => "N",
			"CONVERT_CURRENCY" => "Y",
			"CURRENCY_ID" => "RUB",
			"SHOW_OLD_PRICE" => "Y",
			"OFFER_HIDE_NAME_PROPS" => "N",
			"SHOW_MEASURE" => "Y",
			"DISPLAY_COMPARE" => "Y",
			"CACHE_TIME" => "3600",
			"CACHE_TYPE" => "A",
			"LINE_ELEMENT_COUNT" => "3"
		),
		false
	);?>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>