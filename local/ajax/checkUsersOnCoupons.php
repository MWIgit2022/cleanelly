<?require_once $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php";

if (\Bitrix\Main\Loader::includeModule('iblock'))
{
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_USER_ID");
    $arFilter = Array("IBLOCK_ID"=>IBLOCK_USERS_WITH_COUPONS, "ACTIVE" => "Y", "PROPERTY_USER_ID" => $_POST["userId"]);

    $result = "N";

    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    if($arItems = $res->fetch())
    {
        if (!empty($arItems["PROPERTY_USER_ID_VALUE"]))
            $result = "Y";
    }

    echo $result;
}