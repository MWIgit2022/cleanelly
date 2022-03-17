<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="footer_inner <?=($arTheme["SHOW_BG_BLOCK"]["VALUE"] == "Y" ? "fill" : "no_fill");?> footer-light">
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/footer/subscribe.php",
			"EDIT_TEMPLATE" => "include_area.php"
		)
	);?>
	<div class="bottom_wrapper">
		<div class="maxwidth-theme items">
			<div class="row bottom-middle">
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-3 col-sm-3">
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
								"ROOT_MENU_TYPE" => "bottom_company",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "3600000",
								"MENU_CACHE_USE_GROUPS" => "N",
								"CACHE_SELECTED_ITEMS" => "N",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MAX_LEVEL" => "1",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "Y"
								),
								false
							);?>
						</div>
						<div class="col-md-3 col-sm-3">
							<?$APPLICATION->IncludeComponent(
								"bitrix:menu", 
								"bottom", 
								array(
									"ROOT_MENU_TYPE" => "bottom_catalog",
									"MENU_CACHE_TYPE" => "A",
									"MENU_CACHE_TIME" => "3600000",
									"MENU_CACHE_USE_GROUPS" => "N",
									"CACHE_SELECTED_ITEMS" => "N",
									"MENU_CACHE_GET_VARS" => array(
									),
									"MAX_LEVEL" => "1",
									"CHILD_MENU_TYPE" => "left",
									"USE_EXT" => "Y",
									"DELAY" => "N",
									"ALLOW_MULTI_SELECT" => "Y",
									"COMPONENT_TEMPLATE" => "bottom",
									"COMPOSITE_FRAME_MODE" => "A",
									"COMPOSITE_FRAME_TYPE" => "AUTO"
								),
								false
							);?>
						</div>
						<div class="col-md-3 col-sm-3">
							<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"bottom", 
	array(
		"ROOT_MENU_TYPE" => "bottom_info",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_USE_GROUPS" => "N",
		"CACHE_SELECTED_ITEMS" => "N",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "Y",
		"COMPONENT_TEMPLATE" => "bottom",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
						</div>
						<div class="col-md-3 col-sm-3">
							<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", array(
								"ROOT_MENU_TYPE" => "bottom_help",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_TIME" => "3600000",
								"MENU_CACHE_USE_GROUPS" => "N",
								"CACHE_SELECTED_ITEMS" => "N",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MAX_LEVEL" => "1",
								"CHILD_MENU_TYPE" => "left",
								"USE_EXT" => "N",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "Y"
								),
								false
							);?>
						</div>
					</div>
	 			</div>
				<!--div class="col-md-1">
				</div-->
				<div class="col-md-3 contact-block">
					<div class="row">
						<div class="col-md-9 col-md-offset-2">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/contacts-title.php", array(), array(
									"MODE" => "html",
									"NAME" => "Title",
									"TEMPLATE" => "include_area.php",
								)
							);?>
							<div class="info">
								<div class="row">
									<div class="col-md-12 col-sm-4">
										<div class="info-txt_footer">
										<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
											array(
												"COMPONENT_TEMPLATE" => ".default",
												"PATH" => SITE_DIR."include/header/worktime.php",
												"AREA_FILE_SHOW" => "file",
												"AREA_FILE_SUFFIX" => "",
												"AREA_FILE_RECURSIVE" => "Y",
												"EDIT_TEMPLATE" => "include_area.php"
											),
											false
										);?>
										</div>
									</div>
									<div class="col-md-12 col-sm-4">
										<?CNext::ShowHeaderPhones('', true);?>
									</div>
									<div class="col-md-12 col-sm-4">
										<?CNext::showEmail('email blocks');?>
									</div>
									<div class="col-md-12 col-sm-4">
										<?CNext::showAddress('address blocks');?>
									</div>
									<div class="col-md-12 col-sm-4">
										<div class="social-block">
											<?$APPLICATION->IncludeComponent(
												"aspro:social.info.next",
												".default",
												array(
													"CACHE_TYPE" => "A",
													"CACHE_TIME" => "3600000",
													"CACHE_GROUPS" => "N",
													"COMPONENT_TEMPLATE" => ".default"
												),
												false
											);?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				<div class="bottom-under">
					<div class="row">
						<div class="col-md-12 outer-wrapper">
							<div class="inner-wrapper row">
								<div class="copy-block">
									<div class="copy">
										<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/copyright.php", Array(), Array(
												"MODE" => "php",
												"NAME" => "Copyright",
												"TEMPLATE" => "include_area.php",
											)
										);?>
									</div>
									<div class="print-block"><?=CNext::ShowPrintLink();?></div>
									<div id="bx-composite-banner"></div>
									<div class="payment-methods-block">
										<span class="payment-methods-block__text">Мы принимаем к оплате</span>
										<div class="payment-methods-block__image-wrapper">
											<img alt="payment-methods" data-src="/images/payment-methods.png" class="payment-methods-block__image lazy">
										</div>
									</div>
								</div>
								<div class="dev_wrapper">
									<div class="dev">
									   <?/* Разработка сайта&nbsp;&nbsp;—&nbsp;&nbsp;<a href="http://ruformat.ru/" rel="nofollow" target="_blank"><img class="lazy" data-src="/images/ruformat.png" alt="ruformat"></a>*/?>
									   <div>
											Техническая поддержка - <a href="https://mwi.me/" target="_blank"><img style="max-width:85px; margin-right:4em;" class="lazy" data-src="<?=SITE_TEMPLATE_PATH?>/images/logo_mwi.svg" alt="mwi"></a>
									  </div>
									  <div>
											Разработка сайта&nbsp;&nbsp;—&nbsp;&nbsp;<a href="http://ruformat.ru/" rel="nofollow" target="_blank"><img class="lazy" data-src="/images/ruformat.png" alt="ruformat"></a> 
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<!-- <link rel="preload" href="/jivosite/jivosite.css" as="style"> -->
<!-- <script src="/jivosite/jivosite.js" type="text/javascript" defer></script> -->
<script src="<?=$APPLICATION->GetTemplatePath('js/jquery.lazy.min.js')?>" type="text/javascript" defer></script>
<script src="<?=$APPLICATION->GetTemplatePath('js/custom2.js')?>" type="text/javascript" defer></script>