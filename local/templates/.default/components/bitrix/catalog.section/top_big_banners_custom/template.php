<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<? $frame = $this->createFrame()->begin(); ?>
<?if($arResult["ITEMS"]):?>
	<div class="sections_wrapper front_sections_theme_custom">
		<?if($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]):?>
			<div class="top_block">
				<h3 class="title_block"><?=$arParams["TITLE_BLOCK"];?></h3>
				<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
			</div>
		<?endif;?>
		<?include_once($arParams['TEMPLATE'].'.php');?>
	</div>
<?endif;?>
<? $frame->beginStub(); ?>
<? $frame->end(); ?> 