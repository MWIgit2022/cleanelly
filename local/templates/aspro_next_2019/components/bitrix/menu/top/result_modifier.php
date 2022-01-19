<?$arResult = CNext::getChilds($arResult);

$arFilter = array('IBLOCK_ID' => 17,'ACTIVE'=>'Y', '!UF_CAT_IN_MENU'=>false);
	$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter,false, array('UF_CAT_IN_MENU'));
	while ($arSect = $rsSect->GetNext())
	{
		$sect_lvls[$arSect['UF_CAT_IN_MENU']][$arSect['NAME']] = array('TEXT'=>$arSect['NAME'], 'LINK'=>$arSect['SECTION_PAGE_URL']);
	}

$arSect = false;
global $arRegion, $arTheme;
if($arResult){
	foreach($arResult as $key=>$arItem)
	{
		
		if($arItem['DEPTH_LEVEL'] == 2){
			   $arFilter = array('IBLOCK_ID' => 17,'ACTIVE'=>'Y', 'NAME'=>$arItem['TEXT']);
			   $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter,false, array('UF_CAT_IN_MENU'));
			   while ($arSect = $rsSect->GetNext())
			   {
				   
			   }
			}
		
		if($arItem['PARAMS']['MENU_ITEM_NAME'] && $arItem['MENU_ITEM_NAME']) {
			$arResult[$key]['TEXT'] = $arItem['PARAMS']['MENU_ITEM_NAME'] ? $arItem['PARAMS']['MENU_ITEM_NAME'] : $arItem['MENU_ITEM_NAME'];
		}
		if(isset($arItem['CHILD']))
		{
			foreach($arItem['CHILD'] as $key2=>$arItemChild)
			{
			

	if($arItemChild['PARAMS']['DEPTH_LEVEL'] == 2){
			   $arFilter = array('IBLOCK_ID' => 17,'ACTIVE'=>'Y', 'NAME'=>$arItemChild['TEXT']);
			   $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arFilter,false, array('UF_CAT_IN_MENU'));
			   while ($arSect = $rsSect->GetNext())
			   {
				   if($arSect['UF_CAT_IN_MENU']){
					   $uf_name = false;
					   $rsEnum = CUserFieldEnum::GetList(array(), array("USER_FIELD_NAME"=>"UF_CAT_IN_MENU", "ID" =>$arSect['UF_CAT_IN_MENU'])); 
					   $arEnum = $rsEnum->GetNext();  
					   $uf_name = $arEnum['VALUE'];
					   $arResult[$key]['CHILD'][$arSect['UF_CAT_IN_MENU']]['TEXT'] = $uf_name;
					    if($sect_lvls[$arSect['UF_CAT_IN_MENU']][$arItemChild['TEXT']] && $arItemChild['CHILD']){
							$sect_lvls[$arSect['UF_CAT_IN_MENU']][$arItemChild['TEXT']]['CHILD'] = $arItemChild['CHILD'];
					   }
					   $arResult[$key]['CHILD'][$arSect['UF_CAT_IN_MENU']]['CHILD'] = $sect_lvls[$arSect['UF_CAT_IN_MENU']];
					 
					   unset($arResult[$key]['CHILD'][$key2]);
				   }
			   }
			}

				   
				if(isset($arItemChild['PARAMS']) && $arRegion && $arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y')
				{
					// filter items by region
					if(isset($arItemChild['PARAMS']['LINK_REGION']))
					{
						if($arItemChild['PARAMS']['LINK_REGION'])
						{
							if(!in_array($arRegion['ID'], $arItemChild['PARAMS']['LINK_REGION']))
								unset($arResult[$key]['CHILD'][$key2]);
						}
						else
							unset($arResult[$key]['CHILD'][$key2]);
					}
				}
			}
		}
	}
}
?>