<?
global $arTheme, $arRegion;
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>
<div class="mobileheader-v1">
	<div class="burger pull-left">
		<?=CNext::showIconSvg("burger dark", SITE_TEMPLATE_PATH."/images/svg/Burger_big_white.svg");?>
		<?=CNext::showIconSvg("close dark", SITE_TEMPLATE_PATH."/images/svg/Close.svg");?>
	</div>
	<div class="logo-block pull-left">
		<div class="logo<?=$logoClass?>">
			<?=CNext::ShowLogo();?>
		</div>
	</div>
	<div class="right-icons pull-right">
		<div class="pull-right">
			<div class="wrap_icon">
				<button class="top-btn inline-search-show twosmallfont">
					<?=CNext::showIconSvg("search big", SITE_TEMPLATE_PATH."/images/svg/Search_big_black.svg");?>
				</button>
			</div>
		</div>
		<div class="pull-right">
			<div class="wrap_icon wrap_basket">
				<?=CNext::ShowBasketWithCompareLink('', 'big', false, false, true);?>
			</div>
		</div>
		<div class="pull-right">
			<div class="wrap_icon wrap_cabinet">
				<?=CNext::showCabinetLink(true, false, 'big');?>
			</div>
		</div>
		<div class="pull-right header-socials" >
			<a href="tel:88005115203" onclick="ym(22769200,'reachGoal','Phone-click')"><img src="<?=SITE_TEMPLATE_PATH?>/images/phone_round.svg" alt="phone_round"></a>
			<a href="https://wa.me/79613004564" onclick="ym(22769200,'reachGoal','Click-whatsapp')"><img src="<?=SITE_TEMPLATE_PATH?>/images/whatsapp_round.svg" alt="whatsapp_round"></a>
		</div>
	</div>
</div>