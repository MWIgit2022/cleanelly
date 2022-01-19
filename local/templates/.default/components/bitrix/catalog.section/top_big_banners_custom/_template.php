<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult["ITEMS"]){?>
<div class="catalog_section_list row items flexbox">
	<?foreach( $arResult["ITEMS"] as $arItem ){
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "SECTION_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
	?>
		<div class="item_block col-md-6 col-sm-6">
			<div class="section_item item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<table class="section_item_inner">
					<tr>
						<?if (true || $arParams["SHOW_SECTION_LIST_PICTURES"]=="Y"):?>
							<?$collspan = 2;?>
							<td class="image">
								<?if($arItem["PREVIEW_PICTURE"]["SRC"]):?>
									<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
									<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
								<?elseif($arItem["~PICTURE"]):?>
									<?$img = CFile::ResizeImageGet($arItem["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
									<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"])?>" /></a>
								<?else:?>
									<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/catalog_category_noimage.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" /></a>
								<?endif;?>
							</td>
						<?endif;?>
						<td class="section_info">
							<ul>
								<li class="name">
									<a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="dark_link"><span><?=$arItem["NAME"]?></span></a>
								</li>
								<?if($arItem["SECTIONS"]){
									foreach( $arItem["SECTIONS"] as $arItem ){?>
										<li class="sect"><a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="dark_link"><?=$arItem["NAME"]?><? echo $arItem["ELEMENT_CNT"]?'&nbsp;<span>'.$arItem["ELEMENT_CNT"].'</span>':'';?></a></li>
									<?}
								}?>
							</ul>
						</td>
					</tr>
					<?if($arParams["SECTIONS_LIST_PREVIEW_DESCRIPTION"]!="N"):?>
						<?$arSection = $section=CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arItem["ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]));?>
						<?if ($arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]):?>
							<tr><td class="desc" <?=($collspan? 'colspan="'.$collspan.'"':"");?>><span class="desc_wrapp"><?=$arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]?></span></td></tr>
						<?elseif (!empty($arItem["DESCRIPTION"])):?>
							<tr><td class="desc" <?=($collspan? 'colspan="'.$collspan.'"':"");?>><span class="desc_wrapp"><?=$arItem["DESCRIPTION"]?></span></td></tr>
						<?endif;?>
					<?endif;?>
				</table>
			</div>
		</div>
	<?}?>
</div>
<?}?>