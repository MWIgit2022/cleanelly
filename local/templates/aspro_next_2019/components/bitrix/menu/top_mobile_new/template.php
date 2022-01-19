<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);


$arResult[] = array(
"LINK" => "/skidki.php",
"TEXT" => "Скидки",
);
?>
<?if($arResult):?>
	<div class="menu top">
		<ul class="top">
			<?foreach($arResult as $arItem):?>
				<?$bShowChilds = $arParams['MAX_LEVEL'] > 1;?>
				<?$bParent = $arItem['CHILD'] && $bShowChilds;?>
				<li<?=($arItem['SELECTED'] ? ' class="selected"' : '')?>>
					<a <?if($arItem['PARAMS']['custom_style'] == 'track_order'){?>style="color:#299d30"<?}?> class="dark-color<?=($bParent ? ' parent' : '')?>" href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>">
					<?if($arItem['PARAMS']['custom_style'] == 'track_order'){?>
						<img src="<?=SITE_TEMPLATE_PATH?>/images/track_order.png" style="max-width:20px">
					<?}?>
						<span><?=$arItem['TEXT']?></span>
						<?if($bParent):?>
							<span class="arrow"><i class="svg svg_triangle_right"></i></span>
						<?endif;?>
					</a>
					<?if($bParent):?>
						<ul class="dropdown">
							<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><i class="svg svg-arrow-right"></i><?=GetMessage('NEXT_T_MENU_BACK')?></a></li>
							<li class="menu_title"><a href="<?=$arItem['LINK'];?>"><?=$arItem['TEXT']?></a></li>
							<?$APPLICATION->IncludeComponent(
								"bitrix:menu", 
								"top_mobile_child", 
								array(
									"COMPONENT_TEMPLATE" => "top_mobile",
									"MENU_CACHE_TIME" => "3600000",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_USE_GROUPS" => "N",
									"MENU_CACHE_GET_VARS" => array(
									),
									"DELAY" => "N",
									"MAX_LEVEL" => "5",
									"ALLOW_MULTI_SELECT" => "Y",
									"ROOT_MENU_TYPE" => "top_catalog",
									"CHILD_MENU_TYPE" => "left",
									"CACHE_SELECTED_ITEMS" => "N",
									"USE_EXT" => "Y"
								),
								false
							);?>
						</ul>
					<?endif;?>
				</li>
			<?endforeach;?>
		</ul>
	</div>
<?endif;?>