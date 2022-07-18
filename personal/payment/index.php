<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;

$APPLICATION->SetTitle("Оплата заказа");
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment",
	"",
	Array(
	)
);?>
<?
$request = Application::getInstance()->getContext()->getRequest();
$hash->getQuery("HASH");
?>
<script>
<?if($hash){?>
	document.querySelector('form').setAttribute('action', document.querySelector('form').getAttribute('action')+'&HASH=<?=$hash?>');
<?}?>
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>