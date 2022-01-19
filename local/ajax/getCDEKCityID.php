<?require_once $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php";

if ($_REQUEST["cityID"])
{
    global $DB;

    $strSql = "
        SELECT
            ID, SDEK_ID, NAME, REGION
        FROM
            ipol_sdekcities 
        WHERE
            BITRIX_ID=".$_POST["cityID"];

    $res = $DB->Query($strSql);

    if($city = $res->fetch())
        echo $city["SDEK_ID"];
}else{
    echo 0;
}