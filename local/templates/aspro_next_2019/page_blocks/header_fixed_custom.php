<?
global $arTheme, $arRegion, $USER;
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>
<div class="wrapper_inner">
	<div class="logo-row v1 row margin0">
		<div class="pull-left">
			<div class="inner-table-block sep-left nopadding logo-block">
				<div class="logo<?=$logoClass?>">
					<?=CNext::ShowLogo();?>
				</div>
			</div>
		</div>
		<div class="pull-left">
			<div class="inner-table-block menu-block rows sep-left">
				<div class="title"><i class="svg svg-burger"></i><?=GetMessage("S_MOBILE_MENU")?>&nbsp;&nbsp;<i class="fa fa-angle-down"></i></div>
				<div class="navs table-menu js-nav">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"PATH" => SITE_DIR."include/menu/menu.top_fixed_field.php",
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "",
							"AREA_FILE_RECURSIVE" => "Y",
							"EDIT_TEMPLATE" => "include_area.php"
						),
						false, array("HIDE_ICONS" => "Y")
					);?>
				</div>
			</div>
		</div>
		<div class="pull-left col-md-3 nopadding hidden-sm hidden-xs search animation-width">
			<div class="inner-table-block">
				<?global $isFixedTopSearch;
				$isFixedTopSearch = true;?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
						"EDIT_TEMPLATE" => "include_area.php"
					)
				);?>
			</div>
		</div>
		<div class="pull-right" style="display:flex;">
			<?CNext::ShowBasketWithCompareLink('top-btn inner-table-block', 'big');?>
		</div>
		<div class="pull-right">
			<div class="inner-table-block small-block">
				<div class="wrap_icon wrap_cabinet">
					<?=CNext::showCabinetLink(true,$USER->IsAuthorized(),'big',!$USER->IsAuthorized(),'Личный<br>кабинет');?> 
				</div>
			</div>
		</div>
		<?if($arTheme['SHOW_CALLBACK']['VALUE'] == 'Y'):?>
			<div class="pull-right">
				<div class="inner-table-block">
					<div class="animate-load btn btn-default white btn-sm" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback">
						<span><?=GetMessage("CALLBACK")?></span>
					</div>
				</div>
			</div>
		<?endif;?>
		<?if($bPhone):?>
			<div class="pull-right logo_and_menu-row">
				<div class="inner-table-block phones">
					<?CNext::ShowHeaderPhones();?>
				</div>
			</div>
		<?endif;?>
					<div class="header-socials" >
						<a style="color:#299d30;width:auto;margin-right:1em;"  href="/track_order/">
							<img src="<?=SITE_TEMPLATE_PATH?>/images/track_order.png" style="max-width:20px">
							Отследить мой заказ
						</a>
                        <a href="tel:88005115203" onclick="ym(22769200,'reachGoal','Phone-click')"><img src="<?=SITE_TEMPLATE_PATH?>/images/phone_round.svg" width="30" height="30" alt="phone_round"></a>
					    <a onclick="ym(22769200,'reachGoal','Click-whatsapp')" href="https://wa.me/79613004564"><img src="<?=SITE_TEMPLATE_PATH?>/images/whatsapp_round.svg" width="30" height="30" alt="whatsapp_round"></a>
					</div>
	</div>
</div>