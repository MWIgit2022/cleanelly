<div class="list items">
	<div class="row margin0 flexbox">
		<?foreach($arResult["ITEMS"] as $arItem):
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div class="col-m-25 col-md-3 col-sm-4 col-xs-6">
				<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="img shine">
						<?if($arItem["PREVIEW_PICTURE"]["SRC"]):?>
							<?$img = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array( "width" => 430, "height" => 430 ), BX_RESIZE_IMAGE_EXACT, true, false, false, 75 );?>
							<a href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>" class="thumb"><img class="lazy" data-src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"])?>" <?/* width="360" height="360" */?>/></a>
						<?elseif($arItem["~PICTURE"]):?>
							<?$img = CFile::ResizeImageGet($arItem["~PICTURE"], array( "width" => 430, "height" => 430 ), BX_RESIZE_IMAGE_EXACT, true, false, false, 75 );?>
							<a href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>" class="thumb"><img class="lazy" data-src="<?=$img["src"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ? $arItem["PREVIEW_PICTURE"]["ALT"] : $arItem["NAME"])?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ? $arItem["PREVIEW_PICTURE"]["TITLE"] : $arItem["NAME"])?>" <?/*width="360" height="360"*/?> /></a>
						<?else:?>
							<a href="<?=$arItem['PROPERTIES']['LINK']['VALUE']?>" class="thumb"><img class="lazy" data-src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" height="90" /></a>
						<?endif;?>
					</div>
					<div class="name">
						<a href="<?=$arItem['PROPERTIES']['LINK']['VALUE'];?>" class="dark_link"><?=$arItem['NAME'];?></a>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>
</div>