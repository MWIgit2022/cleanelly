<?require_once $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php";

if (\Bitrix\Main\Loader::includeModule('iblock'))
{
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_ADDRESS", "PROPERTY_CITY");
    $arFilter = Array("IBLOCK_ID"=>IBLOCK_SHOPS, "PROPERTY_CITY" => $_POST["city"]);

    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($arItems = $res->fetch())
    {
        $adress[] = $arItems["PROPERTY_ADDRESS_VALUE"];  
    }

    if (is_array($adress))
        print json_encode($adress);
    else
        print false;
}