<?require_once $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php";
use Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();
$cityID = $request->getPost("cityID");

if ($cityID)
{
    global $DB;

    $strSql = "
        SELECT
            ID, SDEK_ID, NAME, REGION
        FROM
            ipol_sdekcities 
        WHERE
            BITRIX_ID=".$cityID;

    $res = $DB->Query($strSql);

    if($city = $res->fetch())
        echo $city["SDEK_ID"];
}else{
    echo 0;
}