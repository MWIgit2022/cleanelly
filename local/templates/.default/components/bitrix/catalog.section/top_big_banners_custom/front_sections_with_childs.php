<?
$arRootItems = $arChildItems = array();
foreach($arResult["ITEMS"] as $key => $arItem)
{
	if($arItem['DEPTH_LEVEL'] == 1)
		$arRootItems[$arItem['ID']] = $arItem;
	else
		$arChildItems[$arItem['ID']] = $arItem;
	unset($arResult["ITEMS"][$key]);
}
if($arChildItems)
{
	foreach($arChildItems as $key => $arItem)
	{
		$arRootSection = CNextCache::CIBlockSection_GetList(array('CACHE' => array('MULTI' =>'N', 'TAG' => CNextCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), array('GLOBAL_ACTIVE' => 'Y', '<=LEFT_BORDER' => $arItem['LEFT_MARGIN'], '>=RIGHT_BORDER' => $arItem['RIGHT_MARGIN'], 'DEPTH_LEVEL' => 1, 'IBLOCK_ID' => $arParams['IBLOCK_ID']), false, array('ID', 'NAME', 'SORT', 'SECTION_PAGE_URL', 'PICTURE'));
		if(!isset($arRootItems[$arRootSection['ID']]))
			$arRootItems[$arRootSection['ID']] = $arRootSection;
	}
}
\Bitrix\Main\Type\Collection::sortByColumn($arRootItems, array('SORT' => array(SORT_NUMERIC, SORT_ASC), 'ID' => array(SORT_NUMERIC, SORT_ASC)));
foreach($arRootItems as $key => $arItem)
{
	$arItems = CNextCache::CIBlockSection_GetList(array('SORT' => 'ASC', 'ID' => 'ASC', 'CACHE' => array('MULTI' =>'Y', 'TAG' => CNextCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), array('GLOBAL_ACTIVE' => 'Y', 'SECTION_ID' => $arItem['ID'], 'DEPTH_LEVEL' => 2, 'IBLOCK_ID' => $arParams['IBLOCK_ID']), $arParams['COUNT_ELEMENTS'], array('ID', 'NAME', 'SORT', 'SECTION_PAGE_URL'));
	$arRootItems[$key]['ITEMS'] = $arItems;
}
global $arTheme;
$iVisibleItemsMenu = ($arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] ? $arTheme['MAX_VISIBLE_ITEMS_MENU']['VALUE'] : 10);
?>
<div class="list items catalog_section_list">
	<div class="row margin0 flexbox">
		<?foreach($arRootItems as $arItem):
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<div class="item section_item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="section_item_inner">
						<div class="img">
							<?if(is_array($arItem["PREVIEW_PICTURE"]) && $arItem["PREVIEW_PICTURE"]['SRC']):?>
								<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]['ID'], array( "width" => 430, "height" => 430 ), BX_RESIZE_IMAGE_EXACT, true, false, false, 75 );?>
								<a href="<?=$arItem['SECTION_PAGE_URL']?>" class="thumb"><img class="lazy" data-src="<?=$img['src']?>" alt="<?=($arItem["PREVIEW_PICTURE"]['ALT'] ? $arItem["PREVIEW_PICTURE"]['ALT'] : $arItem['NAME'])?>" title="<?=($arItem["PREVIEW_PICTURE"]['TITLE'] ? $arItem["PREVIEW_PICTURE"]['TITLE'] : $arItem['NAME'])?>" /></a>
							<?elseif($arItem['~PICTURE']):?>
								<?$img = CFile::ResizeImageGet($arItem['~PICTURE'], array( "width" => 430, "height" => 430 ), BX_RESIZE_IMAGE_EXACT, true, false, false, 75 );?>
								<a href="<?=$arItem['SECTION_PAGE_URL']?>" class="thumb"><img class="lazy" data-src="<?=$img['src']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" /></a>
							<?else:?>
								<a href="<?=$arItem['SECTION_PAGE_URL']?>" class="thumb"><img class="lazy" data-src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" /></a>
							<?endif;?>
						</div>
						<div class="section_info toggle">
							<ul>
								<li class="name">
									<a href="<?=$arItem['SECTION_PAGE_URL']?>" class="dark_link"><span><?=$arItem['NAME']?></span></a>
								</li>
								<?if($arItem['ITEMS']):
									$iCountChilds = count($arItem['ITEMS']);
									foreach($arItem['ITEMS'] as $key => $arItem):?>
										<li class="sect <?=(++$key > $iVisibleItemsMenu ? 'collapsed' : '');?>"><a href="<?=$arItem['SECTION_PAGE_URL']?>" class="dark_link"><?=$arItem['NAME']?><? echo $arItem['ELEMENT_CNT']?'&nbsp;<span>'.$arItem['ELEMENT_CNT'].'</span>':'';?></a></li>
									<?endforeach;?>
									<?if($iCountChilds > $iVisibleItemsMenu):?>
										<li class="sect"><span class="colored more_items with_dropdown" data-resize="Y"><?=\Bitrix\Main\Localization\Loc::getMessage('S_MORE_ITEMS');?></span></li>
									<?endif;?>
								<?endif;?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>
</div>