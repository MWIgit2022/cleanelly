<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
?>

<div id="main_slider" class="main_slider">


	<? foreach($arResult["MORE_PHOTO"] as $i => $arImage):?>
		<div>
			<?/*/?>
			<img
				class="xzoom_image"
				src="<?=$arImage["BIG"]["src"]?>"
				xoriginal="<?=$arImage["BIG"]["src"]?>"
				xpreview="<?=$arImage["BIG"]["src"]?>"
				title=" "
			/>
			<?/*/?>
			<img
				class="xzoom_image"
				<?//= $i==0 ? 'src="'.$arImage["BIG"]["src"].'"' : 'data-lazy="'.$arImage["BIG"]["src"].'"'?>
				src="<?=$arImage["BIG"]["src"]?>"
				xoriginal="<?=$arImage["BIG"]["src"]?>"
				xpreview="<?=$arImage["BIG"]["src"]?>"
				title=" "
			/>
			<?/**/?>
		</div>
	<?endforeach;?>
	<? if (!empty($arResult['VIDEO_URLS'])):?>
		<?foreach ($arResult['VIDEO_URLS'] as $key => $videoUrl):?>
			<div class='video_slider'>
				<img class='play-btn' alt='PLAY' src='<?= SITE_TEMPLATE_PATH ?>/images/arrow-play.png' style='width: 200px !important'>
				<img
						data-height="<?= $arResult['VIDEO_PREVIEWS'][$key]['HEIGHT'] ?>"
						data-width="<?=$arResult['VIDEO_PREVIEWS'][$key]['WIDTH']?>"
						class="main_slider_video"
						src="<?=$arResult['VIDEO_PREVIEWS'][$key]['URL']?>"
						title='<?=$videoUrl?>'
				/>
			</div>
		<? endforeach;?>
	<?endif;?>
</div>

<div class="wrapp_thumbs desktop_slider" style="display: block !important;">
	<div class="sliders">
		<div class="mini_slider" style="max-width: 450px; display: block !important">
			<div class="flex-viewport" style="overflow: visible; position: relative;">
				<div class="xzoom-thumbs">
					<? $arLeight = count($arResult["MORE_PHOTO"]);?>
					<?foreach($arResult["MORE_PHOTO"] as $i => $arImage):?>
						<div class="no-decoration">
							<img
								class="xzoom-gallery image"
								<?= ($i<2 || $i >= $arLeight-2) ? 'src="'.$arImage["THUMB"]["src"].'"' : 'data-lazy="'.$arImage["THUMB"]["src"].'"'?>
								title=""
							/>
						</div>
					<?endforeach;?>
					<? if (!empty($arResult['VIDEO_URLS'])):?>
						<?foreach ($arResult['VIDEO_URLS'] as $key => $videoUrl):?>
							<div class="no-decoration">
								<img class="play-btn-small" src="<?= SITE_TEMPLATE_PATH ?>/images/arrow-play.png">
								<img
										data-height="<?= $arResult['VIDEO_PREVIEWS'][$key]['HEIGHT'] ?>"
										data-width="<?=$arResult['VIDEO_PREVIEWS'][$key]['WIDTH']?>"
										class="xzoom-gallery video"
										src="<?=$arResult['VIDEO_PREVIEWS'][$key]['URL']?>"
										title='<?=$videoUrl?>'
								/>
							</div>
						<?endforeach;?>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
</div>
