<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $arTheme, $arRegion, $USER;
$arRegions = CNextRegionality::getRegions();
if($arRegion)
    $bPhone = ($arRegion['PHONES'] ? true : false);
else
    $bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>
<div class="top-block top-block-v1 <? if ($USER->IsAuthorized()) { ?> is-authorized<? } ?>">
    <div class="maxwidth-theme">
        <div class="row">
            <div class="col-md-8">
                <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "PATH" => SITE_DIR."include/menu/menu.topest.php",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "include_area.php"
                    ),
                    false
                ); ?>
            </div>
            <div class="top-block-item pull-right show-fixed top-ctrl">
                <div class="personal_wrap">
                    <div class="personal top login twosmallfont">
                        <? if($USER->IsAuthorized()){
                            $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "cabinet",
                                Array(
                                    "COMPONENT_TEMPLATE" => "cabinet",
                                    "MENU_CACHE_TIME" => "3600000",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "MENU_CACHE_GET_VARS" => array(
                                    ),
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => '2',
                                    "ALLOW_MULTI_SELECT" => "Y",
                                    "CACHE_SELECTED_ITEMS" => "N",
                                    "ROOT_MENU_TYPE" => "cabinet",
                                    "CHILD_MENU_TYPE" => "left",
                                    "USE_EXT" => "Y"
                                )
                            );
                        }else{
                            CNext::showCabinetLink(true,$USER->IsAuthorized(),'',true,'Личный<br>кабинет');
                        } ?>
                    </div>
                </div>
            </div>
            <? if($arTheme['ORDER_BASKET_VIEW']['VALUE'] == 'NORMAL'): ?>
                <div class="top-block-item pull-right">
                    <div class="phone-block">
                        <? if($bPhone): ?>
                            <div class="inline-block">
                                <? CNext::ShowHeaderPhones(); ?>
                            </div>
                        <? endif ?>
                        <? if($arTheme['SHOW_CALLBACK']['VALUE'] == 'Y'): ?>
                            <div class="inline-block">
                                <span class="callback-block animate-load " data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?= GetMessage("CALLBACK") ?></span>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
            <? endif;
			$arPopUp = HBUtils::GetSettings("settings");
			$showPopUp = $arPopUp["SHOW_POPUP_NEW_CLIENT"]["VALUE_XML_ID"];
			$showTime = $arPopUp["TIME_NEW_CLIENT"]["VALUE"]/1000;
            $showTimeSecond = $arPopUp["SECOND_TIME_NEW_CLIENT"]["VALUE"]/1000;
			
			$showTime = ($_COOKIE["new-client-form"] + $showTime) * 1000;
            $showTimeSecond = ($_COOKIE["new-client-form"] + $showTimeSecond) * 1000;

            if (!($USER->IsAuthorized()) && !($_COOKIE["new-client-form"]) && $showPopUp == "Y") { ?>
                <span class="callback-block animate-load new-client-form" data-showtime="<?= $showTime?>" data-showtimeSecond="<?= $showTimeSecond?>" data-event="jqm" data-param-form_id="NEW_CLIENT" data-name="NEW_CLIENT"></span>
            <? } ?>
            <div class="top-block-item pull-right top-block-worktime">
                <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                    array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "PATH" => SITE_DIR."include/header/worktime.php",
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "",
                        "AREA_FILE_RECURSIVE" => "Y",
                        "EDIT_TEMPLATE" => "include_area.php"
                    ),
                    false
                ); ?>
            </div>
        </div>
    </div>
</div>
<div class="header-v6 header-wrapper header-v6_custom">
    <div class="logo_and_menu-row">
        <div class="logo-row">
            <div class="maxwidth-theme col-md-12">
                <div class="row">
                    <? if($arRegions): ?>
                        <div class="inline-block pull-left regions_padding">
                            <div class="top-description">
                                <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                    array(
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "PATH" => SITE_DIR."include/top_page/regionality.list.php",
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "",
                                        "AREA_FILE_RECURSIVE" => "Y",
                                        "EDIT_TEMPLATE" => "include_area.php"
                                    ),
                                    false
                                ); ?>
                            </div>
                        </div>
                    <? endif; ?>
                    <div class="col-lg-3 search_title_wrap col-md-2 <?= ($_COOKIE['current_region'])?'':'city_selection' ?>">
                        <div class="search-block inner-table-block">
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
                                    "EDIT_TEMPLATE" => "include_area.php"
                                )
                            ); ?>
                        </div>
                    </div>
                    <div class="logo-block col-md-2 col-lg-3 text-center <?= ($arRegions ? '' : 'col-md-offset-1'); ?>">
                        <div class="logo<?= $logoClass ?>">
                            <? /*=CNext::ShowLogo();*/ ?>
                            <a href="/"><img src="/images/logo-cleanelly.jpg" alt="Cleanelly - домашний текстиль" title="Cleanelly - домашний текстиль" width="190" height="63"></a>
                        </div>
                    </div>
                    <div class="right-icons pull-right">
                        <div class="pull-right">
                            <?= CNext::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon wrap_basket baskets'); ?>
                        </div>
                    </div>
                    <div class="header-socials">
                        <a href="tel:88005115203" onclick="ym(22769200,'reachGoal','Phone-click')"><img src="<?= SITE_TEMPLATE_PATH ?>/images/phone_round.svg" width="30" height="30" alt="phone_round"></a>
                        <a onclick="ym(22769200,'reachGoal','Click-whatsapp')" href="https://wa.me/79613004564"><img src="<?= SITE_TEMPLATE_PATH ?>/images/whatsapp_round.svg" width="30" height="30" alt="whatsapp_round"></a>
                    </div>
                </div>
            </div>
        </div><? // class=logo-row ?>
    </div><div class="menu-row middle-block bg<?= strtolower($arTheme["MENU_COLOR"]["VALUE"]); ?>">
        <div class="maxwidth-theme">
            <div class="row">
                <div class="col-md-12">
                    <div class="menu-only">
                        <nav class="mega-menu sliced">
                            <? $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
                                array(
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "PATH" => SITE_DIR."include/menu/menu.top_sections.php",
                                    "AREA_FILE_SHOW" => "file",
                                    "AREA_FILE_SUFFIX" => "",
                                    "AREA_FILE_RECURSIVE" => "Y",
                                    "EDIT_TEMPLATE" => "include_area.php"
                                ),
                                false, array("HIDE_ICONS" => "Y")
                            ); ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="line-row visible-xs"></div>
</div>