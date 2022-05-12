<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
ini_set('memory_limit', '512M');
 updateDiscountFile();
 unzipDiscountFile();
 processDiscounts();
updateDiscounts(); 