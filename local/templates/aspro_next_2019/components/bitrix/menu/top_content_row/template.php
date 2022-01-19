<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if( !empty( $arResult ) ){?>
	<ul class="menu topest">
		<?foreach( $arResult as $key => $arItem ){?>
			<li <?if( $arItem["SELECTED"] ):?> class="current"<?endif?> >
				<a href="<?=$arItem["LINK"]?>" <?if($arItem['PARAMS']['custom_style'] == 'track_order'){?>style="color:#299d30"<?}?>>
					<?if($arItem['PARAMS']['custom_style'] == 'track_order'){?>
						<img src="<?=SITE_TEMPLATE_PATH?>/images/track_order.png" style="max-width:20px">
					<?}?>
					<span><?=$arItem["TEXT"]?></span></a>
			</li>
		<?}?>
		<li class="more hidden">
			<span>...</span>
			<ul class="dropdown"></ul>
		</li>
	</ul>
<?}?>