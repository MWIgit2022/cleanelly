<div class="mobilemenu-v1 scroller">
	<div class="wrap">
		<?$APPLICATION->IncludeComponent(
			"bitrix:menu", 
			"top_mobile_new", 
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
				"ROOT_MENU_TYPE" => "top_content_multilevel",
				"CHILD_MENU_TYPE" => "left",
				"CACHE_SELECTED_ITEMS" => "N",
				"USE_EXT" => "Y"
			),
			false
		);?>
		<?
		// show regions
		CNext::ShowMobileRegions();

		// show cabinet item
		CNext::ShowMobileMenuCabinet();

		// show basket item
		CNext::ShowMobileMenuBasket();

		// use module options for change contacts
		CNext::ShowMobileMenuContacts();
		?>

		<?$APPLICATION->IncludeComponent(
			"aspro:social.info.next",
			"mobile",
			array(
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600000",
				"CACHE_GROUPS" => "N",
				"COMPONENT_TEMPLATE" => ".default"
			),
			false
		);?>
		
		<script>
			if($('.region_wrapper').find('.js_city_chooser.colored').length > 0 && $('.mobile_regions').find('a:first').length > 0) {
				let cityChooserText = $('.region_wrapper').find('.js_city_chooser.colored').find('span:first').text();
				if(cityChooserText != '') {
					$('.mobile_regions').find('a:first').find('span:first').text(cityChooserText);
				}
			}
		</script>
	</div>
</div>