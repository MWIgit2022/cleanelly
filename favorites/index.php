<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:favorites",
	"",
	[]
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>