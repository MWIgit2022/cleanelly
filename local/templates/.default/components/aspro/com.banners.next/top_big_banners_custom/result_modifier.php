<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?
if($arResult['ITEMS'])
{
	foreach($arResult['ITEMS'] as $key => $arItem)
	{
		if (!empty($arItem['PREVIEW_PICTURE']))
		{
			$arResult['ITEMS'][$key]['PREVIEW_PICTURE']['SRC'] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array(
			"width" => 1240,
			"height" => 1240,
			),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			false,
			false,
			false,
			75
			)['src'];
		}
		if (!empty($arItem['DETAIL_PICTURE']))
		{
			$arResult['ITEMS'][$key]['DETAIL_PICTURE']['SRC'] = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array(
			"width" => 1240,
			"height" => 1240,
			),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			false,
			false,
			false,
			75
			)['src'];
		}
	}
	
	
	$arTmpItems = array();
	foreach($arResult['ITEMS'] as $key => $arItem)
	{
		$arTmpItems[$arItem['TYPE_BANNER']]['ITEMS'][] = $arItem;
	}
	if($arParams['BANNER_TYPE_THEME'] && $arTmpItems[$arParams['BANNER_TYPE_THEME']])
		$arResult['HAS_SLIDE_BANNERS'] = true;
	if($arParams['BANNER_TYPE_THEME_CHILD'] && $arTmpItems[$arParams['BANNER_TYPE_THEME_CHILD']])
		$arResult['HAS_CHILD_BANNERS'] = true;
	$arResult['ITEMS'] = $arTmpItems;

}?>
