<?define("STATISTIC_SKIP_ACTIVITY_CHECK", "true");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?if(isset($_GET['term']) && $_GET['term'])
{
	if(\Bitrix\Main\Loader::includeModule('aspro.next'))
	{
		if(true)
		{
			$city = iconv('UTF-8', LANG_CHARSET, $_GET['term']);
			$arRegionsJS = array();
			$bFuncExists = (function_exists('mb_strtolower'));
			$uri = $_GET['url'];

			$arRegions = [];
			$res = \Bitrix\Sale\Location\LocationTable::getList([
				'filter' => [ "=NAME.LANGUAGE_ID" => LANGUAGE_ID, "%NAME.NAME" => $city, "TYPE.CODE" => ['CITY', 'VILLAGE', 'SUBREGION'], "COUNTRY_ID" => 1],
				'select' => [ 
					"NAME_RU" => "NAME.NAME",
					"TYPE_CODE" => "TYPE.CODE",
					'ID',
					'CODE',
					"COUNTRY_ID"
				],
				'limit' => 15
			]);
			
			while($item = $res->fetch())
			{
				$arRegions[] = $item;
			}
			foreach($arRegions as $arTmpRegion)
			{
				if($bFuncExists)
				{
					$cityNameTmp = mb_strtolower($arTmpRegion['NAME_RU']);
					$city = mb_strtolower($city);
				}
				else
				{
					$cityNameTmp = strtolower($arTmpRegion['NAME_RU']);
					$city = strtolower($city);
				}
				if(strpos($cityNameTmp, $city) !== false)
				{
					$cityName = iconv(LANG_CHARSET, 'UTF-8', $arTmpRegion['NAME_RU']);
					$href = $uri;

					$arRegionsJS[] = array(
						'label' => $cityName,
						'HREF' => $href,
						'ID' => $arTmpRegion['ID'],
					);
				}
			}
			if($arRegionsJS)
				echo json_encode($arRegionsJS);
			else
				echo json_encode(array());
		}
		else
			echo json_encode(array());
	}
	else
		echo json_encode(array());
}
?>
