<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

foreach($arResult["ITEMS"] as $key => $arItem)
{
	if($arItem["CODE"]=="POL"){
		$arResult["ITEMS"][$key]["NAME"] = 'Для кого';
		
		foreach ($arResult["ITEMS"][$key]["VALUES"] as $k => $v)
		{
			if ($v["VALUE"] == 'Детский')
			{
				$arResult["ITEMS"][$key]["VALUES"][$k]["VALUE"] = 'Для детей';
			}
			elseif ($v["VALUE"] == 'Женский')
			{
				$arResult["ITEMS"][$key]["VALUES"][$k]["VALUE"] = 'Для женщин';
			}
			elseif ($v["VALUE"] == 'Мужской')
			{
				$arResult["ITEMS"][$key]["VALUES"][$k]["VALUE"] = 'Для мужчин';
			}
		}
		
		


	}
	elseif($arItem["CODE"]=="IN_STOCK"){
		sort($arResult["ITEMS"][$key]["VALUES"]);
		if($arResult["ITEMS"][$key]["VALUES"])
			$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"]=$arItem["NAME"];
	}
}