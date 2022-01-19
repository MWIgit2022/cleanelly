<?$arResult = CNext::getChilds($arResult);
global $arRegion, $arTheme;

if ($arResult) {
    $arParams["CACHE_TIME"] = 36000000;
    $cache = Bitrix\Main\Data\Cache::createInstance();
    if ($cache->initCache($arParams["CACHE_TIME"], 'sectionResult', '/')) {
        $sectionResult = $cache->getVars();
    } elseif ($cache->startDataCache()) {
        $arParams["IBLOCK_ID"] = IBLOCK_CATALOG_ID;
        $arParams["DEPTH_LEVEL"] = intval($arParams["MAX_LEVEL"]);
        if ($arParams["DEPTH_LEVEL"] <= 0) {
            $arParams["DEPTH_LEVEL"] = 1;
        }
        $sectionResult = [];
        $arFilter = array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "GLOBAL_ACTIVE" => "Y",
            "IBLOCK_ACTIVE" => "Y",
            "<=" . "DEPTH_LEVEL" => $arParams["DEPTH_LEVEL"],
        );
        $arOrder = array(
            "left_margin" => "asc",
        );

        $rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, array(
            "ID",
            "NAME",
            "UF_MENU_ITEM",
        ));

        while ($arSection = $rsSections->fetch()) {
            if (!$sectionResult[$arSection["NAME"]]['UF_MENU_ITEM']) {
                $sectionResult[$arSection["NAME"]] = $arSection['UF_MENU_ITEM'];
            }
        }
        $cache->endDataCache($sectionResult);
    }

    foreach ($arResult as $key => $arItem) {
        if ($arItem['PARAMS']['MENU_ITEM_NAME'] || $arItem['MENU_ITEM_NAME']) {
            $arResult[$key]['TEXT'] = $arItem['PARAMS']['MENU_ITEM_NAME'] ? $arItem['PARAMS']['MENU_ITEM_NAME'] : $arItem['MENU_ITEM_NAME'];
        } else {
            if ($sectionResult[$arItem['TEXT']]) {
                $arResult[$key]['TEXT'] = $sectionResult[$arItem['TEXT']];
            }
        }
        if (isset($arItem['CHILD'])) {
            foreach ($arItem['CHILD'] as $key2 => $arItemChild) {
                if ($arItemChild['PARAMS']['MENU_ITEM_NAME'] || $arItemChild['MENU_ITEM_NAME']) {
                    $arResult[$key]['CHILD'][$key2]['TEXT'] = $arItemChild['PARAMS']['MENU_ITEM_NAME'] ? $arItemChild['PARAMS']['MENU_ITEM_NAME'] : $arItemChild['MENU_ITEM_NAME'];
                } else {
                    if ($sectionResult[$arItemChild['TEXT']]) {
                        $arResult[$key]['CHILD'][$key2]['TEXT'] = $sectionResult[$arItemChild['TEXT']];
                    }
                }
                if (isset($arItemChild['PARAMS']) && $arRegion && $arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y') {
                    // filter items by region
                    if (isset($arItemChild['PARAMS']['LINK_REGION'])) {
                        if ($arItemChild['PARAMS']['LINK_REGION']) {
                            if (!in_array($arRegion['ID'], $arItemChild['PARAMS']['LINK_REGION'])) {
                                unset($arResult[$key]['CHILD'][$key2]);
                            }

                        } else {
                            unset($arResult[$key]['CHILD'][$key2]);
                        }

                    }
                }
            }
        }
    }
}
