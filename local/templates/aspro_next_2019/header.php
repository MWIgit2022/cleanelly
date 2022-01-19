<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($GET["debug"] == "y")
	error_reporting(E_ERROR | E_PARSE);
IncludeTemplateLangFile(__FILE__);
use Bitrix\Main\Page\Asset; 
global $APPLICATION, $arRegion, $arSite, $arTheme;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
$bIncludedModule = (\Bitrix\Main\Loader::includeModule("aspro.next"));?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-584QHN2');</script>
<!-- End Google Tag Manager -->
	<link rel="preload" href="/local/templates/aspro_next_2019/css/fonts/BODONIC_2.woff2" as="font" crossorigin="anonymous">
	<link rel="preload" href="/local/templates/aspro_next_2019/css/fonts/FuturaBookC_0.woff2" as="font" crossorigin="anonymous">
	<link rel="preload" href="/local/templates/aspro_next_2019/css/fonts/FuturaDemiC_0.woff2" as="font" crossorigin="anonymous">
	 
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/vendor/css/bootstrap.min.css', true);?>
	<!-- <link href="<?//=SITE_TEMPLATE_PATH.'/css/custom.min.css'?>" type="text/css"  rel="stylesheet" />-->
	<!-- <link href="<?//=SITE_TEMPLATE_PATH.'/css/template_styles.min.css'?>" type="text/css"  rel="stylesheet" />-->

	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowMeta("viewport");?>
	<?$APPLICATION->ShowMeta("HandheldFriendly");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
	<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
	<?$APPLICATION->ShowHead();?>
	<?$APPLICATION->AddHeadString('<script>var myTime = window.performance.now();</script>', true);?>
	<?$APPLICATION->AddHeadString('<script>var myTime = window.performance.now();</script>', true);?>
	<?$APPLICATION->AddHeadString('<script>var myTime = window.performance.now();</script>', true);?>
	<?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/custom_mwi.js');?>
	<?if($bIncludedModule)
		CNext::Start(SITE_ID);?>
	
	<meta name="yandex-verification" content="5485301c6d2c9321" />
	<meta name="cmsmagazine" content="b74d1ea861a2702905f8c30e430ef5aa" />
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-584QHN2"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-57179076-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-57179076-1');
</script>
<meta name="google-site-verification" content="XuNS_kJvvH-gxqsrUnvaOS9pi0ol_Vxg7XtKzPDjYAk" />



<meta name="yandex-verification" content="7b491d3d1fc54702" />

</head>
<body class="<?=($bIncludedModule ? "fill_bg_".strtolower(CNext::GetFrontParametrValue("SHOW_BG_BLOCK")) : "");?>" id="main">
<noscript><img src="https://vk.com/rtrg?p=VK-RTRG-499160-6jqsC" style="position:fixed; left:-999px;" alt=""/></noscript> 


	<div id="panel"><?$APPLICATION->ShowPanel();?></div>
	<?if(!$bIncludedModule):?>
		<?$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_ASPRO_NEXT_TITLE"));?>
		<center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?>
	<?endif;?>
	<?$settings = HBUtils::GetSettings("settings");?>
	<?if($_COOKIE["check_cookie"] != "Y" && $settings['MESSAGE_TEXT']['VALUE']['TEXT']){?>
		<div class='maxwidth-theme'>
		<div class="js-hide">
			<span class="info-message"><?=$settings['MESSAGE_TEXT']['VALUE']['TEXT']?></span>
			<div class="js-close"></div>
		</div>	
		</div>
	<?}?>
	<?$arTheme = $APPLICATION->IncludeComponent("aspro:theme.next", ".default", array("COMPONENT_TEMPLATE" => ".default"), false, array("HIDE_ICONS" => "Y"));?>
	<?include_once('defines.php');?>
	<?CNext::SetJSOptions();?>

	<div class="wrapper1 <?=($isIndex && $isShowIndexLeftBlock ? "with_left_block" : "");?> <?=CNext::getCurrentPageClass();?> <?=CNext::getCurrentThemeClasses();?>">
		<?CNext::get_banners_position('TOP_HEADER');?>

		<div class="header_wrap visible-lg visible-md title-v<?=$arTheme["PAGE_TITLE"]["VALUE"];?><?=($isIndex ? ' index' : '')?>">
			<header id="header">
				<?CNext::ShowPageType('header');?>
			</header>
		</div>
		<?CNext::get_banners_position('TOP_UNDERHEADER');?>

		<?if($arTheme["TOP_MENU_FIXED"]["VALUE"] == 'Y'):?>
			<div id="headerfixed">
			<?if($_COOKIE["check_cookie"] != "Y" && $settings['MESSAGE_TEXT']['VALUE']['TEXT']){?>
				<div class='maxwidth-theme'>
				<div class="js-hide">
					<span class="info-message"><?=$settings['MESSAGE_TEXT']['VALUE']['TEXT']?></span>
					<div class="js-close"></div>
				</div>	
				</div>
			<?}?>
				<?CNext::ShowPageType('header_fixed');?>
			</div>
		<?endif;?>

		<div id="mobileheader" class="visible-xs visible-sm">
			<?CNext::ShowPageType('header_mobile');?>
			<div id="mobilemenu" class="<?=($arTheme["HEADER_MOBILE_MENU_OPEN"]["VALUE"] == '1' ? 'leftside':'dropdown')?>">
				<?CNext::ShowPageType('header_mobile_menu');?>
			</div>
		</div>

		<?/*filter for contacts*/
		if($arRegion)
		{
			if($arRegion['LIST_STORES'] && !in_array('component', $arRegion['LIST_STORES']))
			{
				if($arTheme['STORES_SOURCE']['VALUE'] != 'IBLOCK')
					$GLOBALS['arRegionality'] = array('ID' => $arRegion['LIST_STORES']);
				else
					$GLOBALS['arRegionality'] = array('PROPERTY_STORE_ID' => $arRegion['LIST_STORES']);
			}
		}
		if($isIndex)
		{
			$GLOBALS['arrPopularSections'] = array('UF_POPULAR' => 1);
			$GLOBALS['arrFrontElements'] = array('PROPERTY_SHOW_ON_INDEX_PAGE_VALUE' => 'Y');
		}?>

		<div class="wraps hover_<?=$arTheme["HOVER_TYPE_IMG"]["VALUE"];?>" id="content">
			<?if(!$is404 && !$isForm && !$isIndex):?>
				<?$APPLICATION->ShowViewContent('section_bnr_content');?>
				<?if($APPLICATION->GetProperty("HIDETITLE") !== 'Y'):?>
					<!--title_content-->
					<?CNext::ShowPageType('page_title');?>
					<!--end-title_content-->
				<?endif;?>
				<?$APPLICATION->ShowViewContent('top_section_filter_content');?>
			<?endif;?>

			<?if($isIndex):?>
				<div class="wrapper_inner front <?=($isShowIndexLeftBlock ? "" : "wide_page");?>">
			<?elseif(!$isWidePage):?>
				<div class="wrapper_inner <?=($isHideLeftBlock ? "wide_page" : "");?>">
			<?endif;?>

				<?if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
					<div class="right_block <?=(defined("ERROR_404") ? "error_page" : "");?> wide_<?=CNext::ShowPageProps("HIDE_LEFT_BLOCK");?>">
				<?endif;?>
					<div class="middle <?=($is404 ? 'error-page' : '');?>">
						<?CNext::get_banners_position('CONTENT_TOP');?>
						<?if(!$isIndex):?>
							<div class="container">
								<?//h1?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									<div class="maxwidth-theme">
								<?endif;?>
								<?if($isBlog):?>
									<div class="row">
										<div class="col-md-9 col-sm-12 col-xs-12 content-md <?=CNext::ShowPageProps("ERROR_404");?>">
								<?endif;?>
						<?endif;?>
						<?CNext::checkRestartBuffer();?>