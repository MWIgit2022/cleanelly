<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<? $APPLICATION->IncludeComponent("adwex:sdek.track", "cleanelly", Array(
	"CDEK_ACCOUNT" => "70d21358204861b6e21c1c25cb1f64df",	// Идентификатор контрагента (Account)
		"CDEK_PASSWORD" => "ae81756a278d5a787664875661d63835",	// Пароль (Secure_password)
		"CALCULATE" => "Y",	// Показывать примерную дату доставки
		"SHOW_FULL_HISTORY" => "N",	// Показывать полную историю
		"SHOW_HISTORY" => "Y",	// Показывать историю отправления
		"COMPONENT_TEMPLATE" => ".default",
		"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
		"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
	),
	false
); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>