<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->SetTitle("Оплата заказа");
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment",
	"",
	Array(
	)
);?>
<script>
<?if($_GET['HASH']){?>
	document.querySelector('form').setAttribute('action', document.querySelector('form').getAttribute('action')+'&HASH=<?=$_GET['HASH']?>');
<?}?>
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>