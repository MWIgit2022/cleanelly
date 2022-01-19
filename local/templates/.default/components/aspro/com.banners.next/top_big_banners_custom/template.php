<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<div class="top_big_banners <?=($arResult['HAS_CHILD_BANNERS'] ? 'with_childs' : '');?>">
		<div class="row dd">
			<?if($arResult['HAS_SLIDE_BANNERS'] && $arResult['HAS_CHILD_BANNERS']):?>
				<?$iSmallBannersCount = count($arResult["ITEMS"][$arParams["BANNER_TYPE_THEME_CHILD"]]["ITEMS"]);?>
				<div class="col-md-9 ">
					<?include_once('slider.php');?>
				</div>
				<div class="col-m-20 col-md-3 top_big_banners_mini"><div class="row">
					<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
						<? if ($key > 1) break; ?>
						<?if($key == 4):?>
							</div></div><div class="clearfix">
						<?endif;?>
						<?if($key > 3):?>
							</div><div class="col-m-20 col-md-3 col-xs-6 blocks">
						<?endif;?>

						<?include('float.php');?>

						<?if($key && $key < 3 && $key%2 == 1):?>
							</div></div><div class="col-m-20 col-md-3"><div class="row">
						<?endif;?>
					<?endforeach;?>
				<?if($key <= 3):?>
					</div>
				<?endif;?>
				</div>
			<?elseif($arResult['HAS_SLIDE_BANNERS']):?>
				<div class="col-md-12">
					<?include_once('slider.php');?>
				</div>
			<?elseif($arResult['HAS_CHILD_BANNERS']):?>
				<?foreach($arResult['ITEMS'][$arParams['BANNER_TYPE_THEME_CHILD']]['ITEMS'] as $key => $arItem):?>
					<div class="col-md-3">
						<?include('float.php');?>
					</div>
				<?endforeach;?>
			<?endif;?>
		</div>
	</div>
<?endif;?>