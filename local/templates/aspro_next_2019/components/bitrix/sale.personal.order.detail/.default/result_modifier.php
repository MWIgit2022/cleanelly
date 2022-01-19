<?
use \Bitrix\Iblock\ElementTable;

foreach ($arResult['BASKET'] as $itemKey => $basketItem) {
    $itemsIds[] = $basketItem['PRODUCT_ID'];
    $itemsKeysWithIds[$basketItem['ID']] = $basketItem['PRODUCT_ID'];
    foreach ($basketItem['PROPS']  as $prop){
        $itemsIdsWithProps[$basketItem['PRODUCT_ID']][]  = $prop['CODE'];
    }
}

$getListArray = [];
$getListArray['order'] = ['ID' => 'ASC'];
$getListArray['select'] = ['ID', 'NAME', 'IBLOCK_ID', 'ID'];
$getListArray['filter'] = ["IBLOCK_ID" => IBLOCK_PACKAGE_OF_OFFERS, "ID" => $itemsIds, "ACTIVE" => "Y"];
$rs = ElementTable::getList($getListArray);
$itemProperties = [];
while ($arItem = $rs->fetch()) {
    $dbProperty = \CIBlockElement::getProperty(
        $arItem['IBLOCK_ID'],
        $arItem['ID']
    );
    while ($arProperty = $dbProperty->fetch()) {
        if ($arProperty['CODE'] == 'SIZES' || $arProperty['CODE'] == 'ARTICLE' || $arProperty['CODE'] == 'CML2_ARTICLE' || $arProperty['CODE'] == 'COLOR_REF')
            $arItem['PROPERTIES'][$arProperty['CODE']] = $arProperty;
    }
    $itemProperties[$arItem['ID']] = $arItem;
}

foreach ($itemsKeysWithIds as $id=>$productId)
{
    foreach ($itemProperties[$productId]['PROPERTIES'] as $property)
    {   
        if (!in_array($property['CODE'], $itemsIdsWithProps[$productId]))
            $arResult['BASKET'][$id]['PROPS'][] = $property;
    }
}

foreach ($arResult["ORDER_PROPS"] as $property) {
    if ($property['CODE'] == 'STREET' && !empty($property["VALUE"]) && $property["VALUE"] != "-") {
        $street = $property["VALUE"];
    }
    if ($property['CODE'] == 'HOUSE' && !empty($property["VALUE"]) && $property["VALUE"] != "-") {
        $house = $property["VALUE"];
    }
    if ($property['CODE'] == 'APPARTMENT' && !empty($property["VALUE"]) && $property["VALUE"] != "-") {
        $apps = $property["VALUE"];
    }
}

$addAdress = "";

if (!empty($street))
{
    $addAdress .= ", ".$street;
    if (!empty($house))
        $addAdress .= ", ".$house;
    if (!empty($apps))
        $addAdress .= ", ".$apps;
}

foreach ($arResult["ORDER_PROPS"] as $property) {
    if ($property['CODE'] == 'ADDRESS') {
        $arResult['ORDER_DELIVERY_ADDRESS']['ADDRESS'] = $property['VALUE'];
    } elseif ($property['CODE'] == 'LOCATION') {
        $arResult['ORDER_DELIVERY_ADDRESS']['LOCATION'] = $property['VALUE'] . $addAdress;
    } elseif ($property['CODE'] == 'ADDRESS_PVZ') {
        $arResult['ORDER_DELIVERY_ADDRESS']['ADDRESS_PVZ'] = $property['VALUE'];
    } elseif ($property['CODE'] == 'ADRESS_PICKUP') {
        $arResult['ORDER_DELIVERY_ADDRESS']['ADRESS_PICKUP'] = $property['VALUE'];
    }
}

if ($arResult['ORDER_DELIVERY_ADDRESS']['ADRESS_PICKUP']) {
    $arResult['ORDER_DELIVERY_ADDRESS']['VALUE'] = $arResult['ORDER_DELIVERY_ADDRESS']['ADRESS_PICKUP'];
} elseif ($arResult['ORDER_DELIVERY_ADDRESS']['ADDRESS_PVZ']) {
    $arResult['ORDER_DELIVERY_ADDRESS']['VALUE'] = $arResult['ORDER_DELIVERY_ADDRESS']['ADDRESS_PVZ'];
} elseif ($arResult['ORDER_DELIVERY_ADDRESS']['ADDRESS']) {
    $arResult['ORDER_DELIVERY_ADDRESS']['VALUE'] = $arResult['ORDER_DELIVERY_ADDRESS']['ADDRESS'];
} elseif ($arResult['ORDER_DELIVERY_ADDRESS']['LOCATION']) {
    $arResult['ORDER_DELIVERY_ADDRESS']['VALUE'] = $arResult['ORDER_DELIVERY_ADDRESS']['LOCATION'];
}
