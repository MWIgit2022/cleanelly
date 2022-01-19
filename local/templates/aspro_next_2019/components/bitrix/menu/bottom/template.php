<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$this->setFrameMode(true);
$colmd = 12;
$colsm = 12;
?>
<?if($arResult):?>
	<?
	if(!function_exists("ShowSubItems2")){
		function ShowSubItems2($arItem){
			?>
			<?if($arItem["CHILD"]):?>
				<?$noMoreSubMenuOnThisDepth = false;
				$count = count($arItem["CHILD"]);?>
				<?$lastIndex = count($arItem["CHILD"]) - 1;?>
				
				<?foreach($arItem["CHILD"] as $i => $arSubItem):?>
					<?if(!$i):?>
						<div class="wrap">
					<?endif;?>
						<?$bLink = strlen($arSubItem['LINK']);?>
						<div class="item-link">
							<div class="item<?=($arSubItem["SELECTED"] ? " active" : "")?>">
								<div class="title">
									<?if($bLink):?>
										<a
											<? foreach($arItem['PARAMS'] as $key => $val) { ?>
												<?= "$key=$val" ?>
											<? } ?> 
											href="<?=$arSubItem['LINK']?>">
											<?=$arSubItem['TEXT']?>
										</a>
									<?else:?>
										<span><?=$arSubItem['TEXT']?></span>
									<?endif;?>
								</div>
							</div>
						</div>
						<?/*if(!$noMoreSubMenuOnThisDepth):?>
							<?ShowSubItems($arSubItem);?>
						<?endif;*/?>
						<?$noMoreSubMenuOnThisDepth |= CNext::isChildsSelected($arSubItem["CHILD"]);?>
					<?if($i && $i === $lastIndex || $count == 1):?>
						</div>
					<?endif;?>
				<?endforeach;?>
				
			<?endif;?>
			<?
		}
	}
	?>
	<div class="bottom-menu">
		<div class="items">
			<?$lastIndex = count($arResult) - 1;?>
			<?foreach($arResult as $i => $arItem):?>
				<?if($i === 1):?>
					<div class="wrap">
				<?endif;?>
					<?$bLink = strlen($arItem['LINK']);?>
					<div class="item-link">
						<div class="item<?=($arItem["SELECTED"] ? " active" : "")?>">
							<div class="title">
								<?if($bLink):?>
									<a
									<?if($arItem['PARAMS']['custom_style'] == 'track_order'){?>style="color:#299d30"<?}?>
									
										<? foreach($arItem['PARAMS'] as $key => $val) { ?>
												<?= "$key=$val" ?>
											<? } ?>
										href="<?=$arItem['LINK']?>">
										<?if($arItem['PARAMS']['custom_style'] == 'track_order'){?>
											<img src="<?=SITE_TEMPLATE_PATH?>/images/track_order.png" style="max-width:20px">
										<?}?>
										<?=$arItem['TEXT']?>
									</a>
								<?else:?>
									<span><?=$arItem['TEXT']?></span>
								<?endif;?>
							</div>
						</div>
					</div>
				<?if($i && $i === $lastIndex):?>
					</div>
				<?endif;?>
				<?ShowSubItems2($arItem);?>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>