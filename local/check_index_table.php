<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$tableName = 'b_iblock_17_index';
$connection = Bitrix\Main\Application::getConnection();

$file = 'index_table_log.txt';
$current = file_get_contents($file);
file_put_contents($file, $current);
if($connection->isTableExists($tableName)){
	$current .= date('H:i:s d-m-Y').' - Таблица на месте'."\r\n";
} else {
	$current .= date('H:i:s d-m-Y').' - Таблицы нет'."\r\n";
}

file_put_contents($file, $current);