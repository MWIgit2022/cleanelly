<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?global $arTheme, $isShowCatalogSections;?>
<?if($isShowCatalogSections):?>


	<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section", 
		"top_big_banners_custom", 
		array(
			"IBLOCK_TYPE" => "aspro_next_content",
			"IBLOCK_ID" => "28",
			"CACHE_GROUPS" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
		"TEMPLATE" => $arTheme["FRONT_PAGE_SECTIONS"]["VALUE"],
		"DISPLAY_PANEL" => "N",
		"COMPONENT_TEMPLATE" => "front_sections_theme",

							"PAGE_ELEMENT_COUNT" => "20",
							"PROPERTY_CODE" => array(
								0 => "LINK",
								1 => "",
							),
			"TITLE_BLOCK" => "Популярные категории",
			"TITLE_BLOCK_ALL" => "Весь каталог →",
			"ALL_URL" => "catalog/"
		));?>

	<?/*$APPLICATION->IncludeComponent(
	"aspro:catalog.section.list.next",
	"front_sections_theme_custom",
	array(
		"IBLOCK_TYPE" => "aspro_next_catalog",
		"IBLOCK_ID" => "17",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME" => "arrPopularSections",
		"TOP_DEPTH" => "",
		"SECTION_URL" => "",
		"VIEW_MODE" => "",
		"SHOW_PARENT_NAME" => "N",
		"HIDE_SECTION_NAME" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"SHOW_SECTIONS_LIST_PREVIEW" => "N",
		"SECTIONS_LIST_PREVIEW_PROPERTY" => "N",
		"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "N",
		"SHOW_SECTION_LIST_PICTURES" => "N",
		"TEMPLATE" => $arTheme["FRONT_PAGE_SECTIONS"]["VALUE"],
		"DISPLAY_PANEL" => "N",
		"COMPONENT_TEMPLATE" => "front_sections_theme",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"TITLE_BLOCK" => "Популярные категории",
		"TITLE_BLOCK_ALL" => "Весь каталог →",
		"ALL_URL" => "catalog/"
	),
	false
);*/?>
<?endif;?>