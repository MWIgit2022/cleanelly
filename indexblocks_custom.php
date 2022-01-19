<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
use HBUtils;

global $isShowSale, $isShowCatalogSections, $isShowCatalogElements, $isShowMiddleAdvBottomBanner, $isShowBlog;
?>

	<div class="maxwidth-theme">
		<?$APPLICATION->IncludeComponent(
			"aspro:com.banners.next", 
			"top_big_banners_custom", 
			array(
				"IBLOCK_TYPE" => "aspro_next_adv",
				"IBLOCK_ID" => "3",
				"TYPE_BANNERS_IBLOCK_ID" => "1",
				"SET_BANNER_TYPE_FROM_THEME" => "N",
				"NEWS_COUNT" => "10",
				"NEWS_COUNT2" => "4", 
				"SORT_BY1" => "SORT",
				"SORT_ORDER1" => "ASC",
				"SORT_BY2" => "ID",
				"SORT_ORDER2" => "DESC",
				"PROPERTY_CODE" => array(
					0 => "TEXT_POSITION",
					1 => "TARGETS",
					2 => "TEXTCOLOR",
					3 => "URL_STRING",
					4 => "BUTTON1TEXT",
					5 => "BUTTON1LINK",
					6 => "BUTTON2TEXT",
					7 => "BUTTON2LINK",
					8 => "",
				),
				"CHECK_DATES" => "Y",
				"CACHE_GROUPS" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "36000000",
				"BANNER_TYPE_THEME" => "TOP",
				"BANNER_TYPE_THEME_CHILD" => "TOP_SMALL_BANNER",
			),
			false
		);?>
		<?/*$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
			array(
				"COMPONENT_TEMPLATE" => ".default",
				"PATH" => SITE_DIR."include/mainpage/comp_tizers.php",
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "",
				"AREA_FILE_RECURSIVE" => "Y",
				"EDIT_TEMPLATE" => "standard.php"
			),
			false
		);*/?>
		<h1><?echo($APPLICATION->GetTitle(false));?></h1>
		<div class="teasers-main">
			<div class="columns-badges">
				<div class="teasers-main-icos">
					<?
					$advantagesBadgesIblock = HBUtils::getIblockId('advantages-badges');
					if ($advantagesBadgesIblock) {
						$APPLICATION->IncludeComponent("bitrix:news.list", "advantages-badges",
							array(
								"DISPLAY_DATE" => "Y",
								"DISPLAY_NAME" => "Y",
								"DISPLAY_PICTURE" => "Y",
								"DISPLAY_PREVIEW_TEXT" => "Y",
								"AJAX_MODE" => "Y",
								"IBLOCK_TYPE" => "news",
								"IBLOCK_ID" => $advantagesBadgesIblock,
								"NEWS_COUNT" => "20",
								"SORT_BY1" => "ID",
								"SORT_ORDER1" => "ASC",
								"SORT_BY2" => "SORT",
								"SORT_ORDER2" => "ASC",
								"FILTER_NAME" => "",
								"FIELD_CODE" => array("ID"),
								"PROPERTY_CODE" => array("DESCRIPTION"),
								"CHECK_DATES" => "Y",
								"DETAIL_URL" => "",
								"PREVIEW_TRUNCATE_LEN" => "",
								"ACTIVE_DATE_FORMAT" => "d.m.Y",
								"SET_TITLE" => "Y",
								"SET_BROWSER_TITLE" => "Y",
								"SET_META_KEYWORDS" => "Y",
								"SET_META_DESCRIPTION" => "Y",
								"SET_LAST_MODIFIED" => "Y",
								"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
								"ADD_SECTIONS_CHAIN" => "Y",
								"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
								"PARENT_SECTION" => "",
								"PARENT_SECTION_CODE" => "",
								"INCLUDE_SUBSECTIONS" => "Y",
								"CACHE_TYPE" => "A",
								"CACHE_TIME" => "3600",
								"CACHE_FILTER" => "Y",
								"CACHE_GROUPS" => "Y",
								"DISPLAY_TOP_PAGER" => "Y",
								"DISPLAY_BOTTOM_PAGER" => "Y",
								"PAGER_TITLE" => "Новости",
								"PAGER_SHOW_ALWAYS" => "Y",
								"PAGER_TEMPLATE" => "",
								"PAGER_DESC_NUMBERING" => "Y",
								"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
								"PAGER_SHOW_ALL" => "Y",
								"PAGER_BASE_LINK_ENABLE" => "Y",
								"SET_STATUS_404" => "Y",
								"SHOW_404" => "Y",
								"MESSAGE_404" => "",
								"PAGER_BASE_LINK" => "",
								"PAGER_PARAMS_NAME" => "arrPager",
								"AJAX_OPTION_JUMP" => "N",
								"AJAX_OPTION_STYLE" => "Y",
								"AJAX_OPTION_HISTORY" => "N",
								"AJAX_OPTION_ADDITIONAL" => ""
							),
							false
						);
					}?>
				</div>
			</div>
			<div class="year-column">
				<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"PATH" => SITE_DIR."include/mainpage/teaser-right.php",
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "",
							"AREA_FILE_RECURSIVE" => "Y",
							"EDIT_TEMPLATE" => "standard.php"
						),
						false
				);?>
			</div>
			<div class="clearboth"></div>
		</div>
	</div>

<? include $_SERVER['DOCUMENT_ROOT'].'/ajax/main-lazy.php'?>