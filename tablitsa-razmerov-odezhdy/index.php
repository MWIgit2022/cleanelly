<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Таблица соответствия размеров одежды, размерная сетка халатов в соответствии с российскими и международными стандартами");
$APPLICATION->SetPageProperty("title", "Таблица размеров одежды, размерная сетка");
$APPLICATION->SetTitle("Таблица размеров одежды");
?><div>
 <b>Наши халаты не являются большемерными или маломерными. Соответствуют&nbsp; российским и международным стандартам.</b>
</div>
<div>
 <br>
</div>
 <style>
.cdr, .sdr td{
padding: 3px;
text-align: center;
}
.cdr tr:nth-child(even){
background-color:#f1f1f1;
}
.sdrtr{
background-color:#f1f1f1;
}
</style>
<div>
	<h4>Таблица соответствия<u> <a href="https://www.cleanelly.ru/catalog/khalaty/zhenskie_khalaty/">женских</a></u><a href="https://www.cleanelly.ru/catalog/khalaty/zhenskie_khalaty/"> </a>размеров:</h4>
</div>
 <b>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "include_area.php",
		"PATH" => SITE_DIR."include/table_sizes/women.php"
	)
);?>
<h4>Таблица соответствия <u><a href="/catalog/khalaty/muzhskie_khalaty/">мужских</a></u> размеров:</h4>
 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "include_area.php",
		"PATH" => SITE_DIR."include/table_sizes/men.php"
	)
);?>
<h4>Таблица для определения размера <u><a href="/catalog/khalaty/detskie/">детских</a></u> халатов:</h4>
<p>
	 Обратите внимание, для некоторых размеров предлагается 2 варианта роста, т.к. детки бывают высокие и невысокого роста, а по сложению могут быть одинаковые.<br>
	 Найти информацию о росте халата можно в карточке товара после описания в <strong>характеристиках</strong>.
</p>
 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_RECURSIVE" => "Y",
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "include_area.php",
		"PATH" => SITE_DIR."include/table_sizes/kids.php"
	)
);?> </b><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>