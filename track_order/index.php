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
<style>
.sdek_block_additional{
	display:flex;
	flex-direction:column;
}
.sdek_block_additional .head{
	font-size:1.25em;
	font-weight:700;
}
.sdek_block_additional .underhead{
	font-weight:600;
	font-style:italic;
}
.sdek_block_additional .list{
	display:flex;
	flex-direction:column;
	gap:0.5em;
	margin:1em 0;
	margin-left:0.5em;
}
.sdek_block_additional .list span:before{
	content: '\✓';
    margin-right: 0.5em;
    border: 1px solid;
    border-radius: 50%;
    padding: 0.125em 0.25em;
    color: green;
}
.sdek_block_additional .btn{
	max-width: 300px;
    margin-top: 1em;
}
</style>
<hr>
<div class="sdek_block_additional">
	<p class="head">Подключите CDEK ID — получайте посылки без паспорта!</p>
	<p class="underhead">Это ваш личный ID — аналог электронной подписи в системе СДЭК,   который поможет получить посылку максимально быстро:</p>
	<div class="list">
		<span>без предъявления документов;</span>
		<span>без заполнения накладной;</span>
		<span>по SMS-коду.</span>
	</div>
	<p class="underhead">Подключиться к системе можно  онлайн за 1 минуту.  </p>
	<a href="https://cdekid.cdek.ru/?utm_medium=email&utm_source=UniSender&utm_campaign=266192536&utm_content=162350758" class="btn btn-default button is-primary button-default">Подключиться </a>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>