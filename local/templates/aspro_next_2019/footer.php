						<?CNext::checkRestartBuffer();?>
						<?IncludeTemplateLangFile(__FILE__);?>
							<?if(!$isIndex):?>
								<?if($isBlog):?>
									</div> <?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
									<div class="col-md-3 col-sm-3 hidden-xs hidden-sm right-menu-md">
										<div class="sidearea">
											<?$APPLICATION->ShowViewContent('under_sidebar_content');?>
											<?CNext::get_banners_position('SIDE', 'Y');?>
											<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "sect", "AREA_FILE_SUFFIX" => "sidebar", "AREA_FILE_RECURSIVE" => "Y"), false);?>
										</div>
									</div>
								</div><?endif;?>
								<?if($isHideLeftBlock):?>
									</div> <?// .maxwidth-theme?>
								<?endif;?>
								</div> <?// .container?>
							<?else:?>
								<?CNext::ShowPageType('indexblocks');?>
							<?endif;?>
							<?CNext::get_banners_position('CONTENT_BOTTOM');?>
						</div> <?// .middle?>
					<?//if(!$isHideLeftBlock && !$isBlog):?>
					<?if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
						</div> <?// .right_block?>				
						<?if($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404")):?>
							<div class="left_block">
								<?CNext::ShowPageType('left_block');?>
							</div>
						<?endif;?>
					<?endif;?>
				<?if($isIndex):?>
					</div>
				<?elseif(!$isWidePage):?>
					</div> <?// .wrapper_inner?>				
				<?endif;?>
			</div> <?// #content?>
			<?CNext::get_banners_position('FOOTER');?>
		</div><?// .wrapper?>
		<footer id="footer">
			<?if($APPLICATION->GetProperty("viewed_show") == "Y" || $is404):?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include", 
					"basket", 
					array(
						"COMPONENT_TEMPLATE" => "basket",
						"PATH" => SITE_DIR."include/footer/comp_viewed.php",
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "",
						"AREA_FILE_RECURSIVE" => "Y",
						"EDIT_TEMPLATE" => "standard.php",
						"PRICE_CODE" => array(
							0 => "BASE",
						),
						"STORES" => array(
							0 => "",
							1 => "",
						),
						"BIG_DATA_RCM_TYPE" => "bestsell"
					),
					false
				);?>					
			<?endif;?>
			<?CNext::ShowPageType('footer');?>
		</footer>
		<?$settings = HBUtils::GetSettings("settings");?>
		<?global $USER;
		if($settings['ACTION_BANNER']['VALUE']  && $_COOKIE['action_banner_show']==false){ ?>
			<div class="overl_action_banner" style="display:none;"> 
				<div class="closdiv">
					<span class="clos">✖</span>
					<a href="<?=$settings['ACTION_BANNER']['DESCRIPTION']?>" onclick="ym(22769200,'reachGoal','clikck-popup_banner')">
						<img src="<?=$settings['ACTION_BANNER']['VALUE']?>">
					</a>
				</div>
			</div>
		<?}?>
		
		<div class="bx_areas">
			<?CNext::ShowPageType('bottom_counter');?>
		</div>
		<?
		CNext::ShowPageType('search_title_component');
		CNext::setFooterTitle();
		CNext::showFooterBasket();
		?>
		<input type="submit" class="show_popup_add_to_cart">
		<div class="jqmOverlay" style="display:none"></div>
		<div class="popup popup_add_to_cart">
			<a href="#" class="close">
				<i></i>
			</a>
			<div class="form">
				<div class="form_head">
					<span class="form_title">Товар добавлен в корзину</span>
				</div>
				<div class="form_body">
					<a href="#" onclick="return false;" class="btn btn-default btn_to_close">Продолжить</a>
					<a href="/basket/" class="btn btn-default btn_to_basket">В корзину</a>
				</div>
			</div>
		</div>
	</body> 
	<div class="overflow"><!-- Затемнение --></div>
		<!-- Bothelp.io widget -->
<script type="text/javascript">!function(){var e={"token":"+79613004564","position":"left","bottomSpacing":"20px","callToActionMessage":"","displayOn":"everywhere","subtitle":"Пн - Пт  с 9:00  по 18:00","message":{"name":"Cleanelly","content":"Здравствуйте, чем мы можем помочь?"}},t=document.location.protocol+"//bothelp.io",o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=t+"/widget-folder/widget-whatsapp-chat.js",o.onload=function(){BhWidgetWhatsappChat.init(e)};var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(o,n)}();</script>
<!-- /Bothelp.io widget -->
</html>