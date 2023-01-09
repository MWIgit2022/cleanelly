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
			// расписание акций
            if($prop['ACTION_3_2']['VALUE']){
				if(HBUtils::activeAction('ACTION_3_2') == false){
					unset($prop['ACTION_3_2']);
				}
			}
			if($prop['ACTION_2_1']['VALUE']){
				if(HBUtils::activeAction('ACTION_2_1') == false){
					unset($prop['ACTION_2_1']);
				}
			}
			if($prop['DOP_DISCOUNT']['VALUE']){
				if(HBUtils::activeAction('DOP_DISCOUNT') == false){
					unset($prop['DOP_DISCOUNT']);
				}
			}
			if($prop['GIFTS_LOGIC']['VALUE']){
				if(HBUtils::activeAction('GIFTS_LOGIC') == false){
					unset($prop['GIFTS_LOGIC']);
				}
			}
			
			if($prop['ACTION_BANNER']['VALUE']){
				$banner = HBUtils::activePopupBanner();
				$prop['ACTION_BANNER']['VALUE'] = $banner['IMG'];
				$prop['ACTION_BANNER']['DESCRIPTION'] = $banner['HREF'];
			}
			
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
	
	
	 public static function activeAction($code){
		$action = false;
		$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
		$arFilter = Array("IBLOCK_ID"=>33, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "CODE"=>$code);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$action = true;
		}
		return $action;
	 }
	 
	  public static function activePopupBanner(){
		$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
		$arFilter = Array("IBLOCK_ID"=>37, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(Array('SORT'=>'DESC'), $arFilter, false, false, $arSelect);
		if($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$arProps = $ob->GetProperties();
			
			$banner = array('IMG'=>Cfile::getPath($arProps['BANNER']['VALUE']), 'HREF'=>$arProps['HREF']['VALUE']);
		}
		return $banner;
	 }
}
?>