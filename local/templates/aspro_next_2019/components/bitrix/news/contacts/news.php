<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

$arItemsCities = [];

$arItemFilter = CNext::GetIBlockAllElementsFilter($arParams);
$arItemSelect = array('ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'PROPERTY_MAP', 'PROPERTY_CITY');
$arItems = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, false, false, $arItemSelect);
if(empty($arItemsCities)) {
    $arItemsCities = $arItems;
}

$arAllSections = array();
if($arItems) {
    $arAllSections = CNext::GetSections($arItems, $arParams);
}
$favouritecitiesOrder = ['Москва' => 0, 'Санкт-Петербург' => 1, 'Ростов-на-Дону' => 2];
$favouriteCities = [];
$itemsCities = [];
$itemsForSearch = [];
foreach($arItemsCities as $arItemsCity) {
    if($arItemsCity['PROPERTY_CITY_VALUE']) {
        $arItem = [];
        $arItem['VALUE'] = trim($arItemsCity['PROPERTY_CITY_VALUE']);
        $arItem['NAME'] = trim($arItemsCity['PROPERTY_CITY_VALUE']);
        $arItem['TYPE'] = 'CITY';
        if(array_key_exists($arItem['VALUE'], $favouritecitiesOrder)) {
            $favouriteCities[$favouritecitiesOrder[$arItem['VALUE']]] = $arItem;
        } else {
            $itemsCities[$arItem['VALUE']] = $arItem;
        }
    }
}
ksort($favouriteCities);
ksort($itemsCities);
$itemsForSearch = array_merge($favouriteCities, $itemsCities);

Asset::getInstance()->addCss($this->getFolder() . '/select2.min.css?v=3');
Asset::getInstance()->addJs($this->getFolder() . '/select2.min.js?v=2');
Asset::getInstance()->addJs($this->getFolder() . '/ru.js?v=2');
Asset::getInstance()->addJs($this->getFolder() . '/jquery-ui.min.js?v=1');
?>
<?if($arParams['SHOW_TOP_MAP'] != 'Y'):?>
    <div class="contacts-page-top">
        <div class="contacts maxwidth-theme">
            <div class="row">
                <?$bHasSections = (isset($arAllSections['ALL_SECTIONS']) && $arAllSections['ALL_SECTIONS']);?>
                <?if($bHasSections):?>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <select class="region" id ="region">
                                    <option value="0" selected><?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => (Loc::getMessage('REGION'))))?></option>
                                    <?foreach($arAllSections['ALL_SECTIONS'] as $arSection):?>
                                        <option value="<?=$arSection['SECTION']['ID'];?>" title="<?=$arSection['SECTION']['NAME']?>" ><?=$arSection['SECTION']['NAME'];?></option>
                                    <?endforeach;?>
                                </select>
                            </div>
                            <?if(!empty($arItems)):?>
                                <div class="col-md-4 col-sm-4">
                                    <select class="city" id="city">
                                        <option value="0" selected data-type="CITY" title="<?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CITY')))?>">
                                            <?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CITY')))?>
                                        </option>
                                        <?foreach($itemsForSearch as $searchItem) {
                                            $title = $searchItem['TYPE'] == 'SHOP' ? $searchItem['CITY'] : $searchItem['VALUE']?>
                                            <option value="<?=$searchItem['VALUE']?>" title="<?=$title?>" data-type="<?=$searchItem['TYPE']?>">
                                                <?=$searchItem['NAME']?>
                                            </option>
                                        <?}?>
                                    </select>
                                </div>
                            <?endif;?>
                            <div class="col-md-4 col-sm-4">
                                <div class="normal-container">
                                    <div class="round-contacts-container">
                                        <div class="contacts-map-toggle-container">
                                            <form class="submit-contacts">
                                                <input id="type_list" value="type_list" name="contacts-toggle" type="radio" checked /> 
                                                <input id="type_map" value="type_map" name="contacts-toggle" type="radio"  /> 
                                                <label for="type_map" class="contacts-label contacts-label-map">Карта</label>
                                                <div class="toggle-contacts-pill">
                                                    <div class="round-contacts-toggle"></div>
                                                </div>                                            
                                                <label for="type_list" class="contacts-label contacts-label-list">Список</label>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?endif;?>
                <div class="col-md-<?=($bHasSections ? 4 : 12);?>">
                    <div class="row">
                        <div class="col-md-6 print-6">
                            <table>
                                <tr>
                                    <td class="icon"><i class="fa big-icon grey s45 fa-phone"></i></td>
                                    <td> <span class="dark_table"><?=Loc::getMessage('SPRAVKA');?></span>
                                        <br />
                                        <span itemprop="telephone"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-phone-one.php", Array(), Array("MODE" => "html", "NAME" => "Phone"));?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 print-6">
                            <table>
                                <tr>
                                    <td class="icon"><i class="fa big-icon grey s45 fa-envelope"></i></td>
                                    <td> <span class="dark_table">E-mail</span>
                                        <br />
                                        <span itemprop="email"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif;?>
<h1 class='title_contacts small-h1'><?= Loc::getMessage('CONTACTS') ?></h1>
<div class="ajax_items">
    <?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y')){
        $APPLICATION->RestartBuffer();?>
    <?}?>
    <?if($arItems):?>
        <?
        $bPost['VALUE'] = isset($_POST['VALUE']) ? $_POST['VALUE'] : false;
        $bPost['TYPE'] = isset($_POST['TYPE']) ? $_POST['TYPE'] : false;
        
        $bUseMap = CNext::GetFrontParametrValue('CONTACTS_USE_MAP', SITE_ID) != 'N';
        $mapLAT = $mapLON = $iCountShops =0;
        $arPlacemarks = array();
        if($bPost['VALUE'] && $bPost['TYPE'])
        {
            switch ($bPost['TYPE']) {
                case 'region':
                    $arItems = CNextCache::CIblockElement_GetList(["CACHE" => ["TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"])]], array_merge($arItemFilter, ['SECTION_ID' => intval($bPost['VALUE'])]), false, false, $arItemSelect);
                    $GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID'] = $bPost['VALUE'];
                    break;
                case 'city':
                    $arItems = CNextCache::CIblockElement_GetList(["CACHE" => ["TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"])]], array_merge($arItemFilter, ['PROPERTY_CITY' => $bPost['VALUE']]), false, false, $arItemSelect);
                    $GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_CITY'] = $bPost['VALUE'];
                    break;
                case 'shop':
                    $arItems = CNextCache::CIblockElement_GetList(["CACHE" => ["TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"])]], array_merge($arItemFilter, ['ID' => $bPost['VALUE']]), false, false, $arItemSelect);
                    $GLOBALS[$arParams['FILTER_NAME']]['ID'] = $bPost['VALUE'];
                    break;
            }
        }

        foreach($arItems as $arItem)
        {
            if($arItem['PROPERTY_MAP_VALUE']){
                $arCoords = explode(',', $arItem['PROPERTY_MAP_VALUE']);
                $mapLAT += $arCoords[0];
                $mapLON += $arCoords[1];
                $str_phones = '';
                if($arItem['PHONE'])
                {
                    foreach($arShop['PHONE'] as $phone)
                    {
                        $str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
                    }
                }
                $arPlacemarks[] = array(
                    "ID" => $arItem["ID"],
                    "LAT" => $arCoords[0],
                    "LON" => $arCoords[1],
                    "TEXT" => $arItem["NAME"],
                    "HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
                );
                ++$iCountShops;
            }
        }
        if($iCountShops && $bUseMap)
        { 
            $mapLAT = floatval($mapLAT / $iCountShops);
            $mapLON = floatval($mapLON / $iCountShops);?>
            <?if($arParams['SHOW_TOP_MAP'] == 'Y'):?>
                <?$this->SetViewTarget('yandex_map');?>
            <?endif;?>
            <div class="contacts-page-map">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:map.yandex.view",
                    "map",
                    array(
                        "INIT_MAP_TYPE" => "MAP",
                        "MAP_DATA" => serialize(array("yandex_lat" => $mapLAT, "yandex_lon" => $mapLON, "yandex_scale" => 19, "PLACEMARKS" => $arPlacemarks)),
                        "MAP_WIDTH" => "100%",
                        "MAP_HEIGHT" => "650",
                        "CONTROLS" => array(
                            0 => "ZOOM",
                            1 => "TYPECONTROL",
                            2 => "SCALELINE",
                        ),
                        "OPTIONS" => array(
                            0 => "ENABLE_DBLCLICK_ZOOM",
                            1 => "ENABLE_DRAGGING",
                        ),
                        "MAP_ID" => "MAP_v33",
                        "COMPONENT_TEMPLATE" => "map"
                    ),
                    false
                );?>
            </div>
            <?if($arParams['SHOW_TOP_MAP'] == 'Y'):?>
                <?$this->EndViewTarget();?>
            <?endif;?>
        <?}?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "contacts",
            Array(
                "COUNT_IN_LINE" => $arParams["COUNT_IN_LINE"],
                "SHOW_SECTION_PREVIEW_DESCRIPTION" => $arParams["SHOW_SECTION_PREVIEW_DESCRIPTION"],
                "VIEW_TYPE" => $arParams["VIEW_TYPE"],
                "SHOW_TABS" => $arParams["SHOW_TABS"],
                "IMAGE_POSITION" => $arParams["IMAGE_POSITION"],
                "IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
                "IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
                "NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
                "SORT_BY1"	=>	$arParams["SORT_BY1"],
                "SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
                "SORT_BY2"	=>	$arParams["SORT_BY2"],
                "SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
                "FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
                "PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
                "DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
                "SET_TITLE"	=>	$arParams["SET_TITLE"],
                "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                "INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
                "ADD_SECTIONS_CHAIN"	=>	$arParams["ADD_SECTIONS_CHAIN"],
                "CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
                "CACHE_TIME"	=>	$arParams["CACHE_TIME"],
                "CACHE_FILTER"	=>	"Y",
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
                "DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
                "PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
                "PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
                "PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
                "PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
                "PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                "DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
                "DISPLAY_NAME"	=>	$arParams["DISPLAY_NAME"],
                "DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
                "DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
                "PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
                "ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
                "USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
                "GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
                "FILTER_NAME"	=>	$arParams["FILTER_NAME"],
                "HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
                "CHECK_DATES"	=>	$arParams["CHECK_DATES"],
                "PARENT_SECTION"	=>	$arResult["VARIABLES"]["SECTION_ID"],
                "PARENT_SECTION_CODE"	=>	$arResult["VARIABLES"]["SECTION_CODE"],
                "DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
                "SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
                "INCLUDE_SUBSECTIONS" => "Y",
                "SHOW_DETAIL_LINK" => $arParams["SHOW_DETAIL_LINK"],
            ),
            $component
        );?>
        <?CNext::checkRestartBuffer();?>
    <?endif;?>
</div>