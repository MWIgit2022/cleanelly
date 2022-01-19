<?
class HBUtils
{
    
    /**
     * Функция возвращает массив свойств элемента инфоблока Настройки сайта
     *
     * @param string iblockCode
     *
     * @return array 
     */
    public static function GetSettings($iblockCode) {
        if(!CModule::IncludeModule("iblock")) {   
            return -1;
        }
        $result = [];

        $dbResult = CIBlockElement::GetList(["SORT" => "ASC"], ["IBLOCK_CODE" => $iblockCode], false, false,array());
        if($namespace_data = $dbResult->GetNextElement()){
            $f = $namespace_data->GetFields();
            $prop = $namespace_data->GetProperties();
            
            $result = $f + $prop;
        }
        return $result;
    }

    public static function getIblockId($iblockCode = '') {
        $result = 0;

        if (!$iblockCode) {
            return $result;
        }

        $res = CIBlock::GetList(
            [],
            ['CODE' => $iblockCode],
            false
        );

        if ($iblock = $res->fetch()) {
            $result = $iblock['ID'];
        }

        return $result;
    }
}
?>