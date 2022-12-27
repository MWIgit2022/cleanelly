<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");
	
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
?>
<?if($arResult["ID"]):?>
	<?if($arParams["USE_REVIEW"] == "Y" && IsModuleInstalled("forum")):?>
		<div id="reviews_content">
			<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("area");?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:forum.topic.reviews",
					"main",
					Array(
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],
						"USE_CAPTCHA" => $arParams["USE_CAPTCHA"],
						"FORUM_ID" => $arParams["FORUM_ID"],
						"ELEMENT_ID" => $arResult["ID"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"AJAX_POST" => $arParams["REVIEW_AJAX_POST"],
						"SHOW_RATING" => "N",
						"SHOW_MINIMIZED" => "Y",
						"SECTION_REVIEW" => "Y",
						"POST_FIRST_MESSAGE" => "Y",
						"MINIMIZED_MINIMIZE_TEXT" => GetMessage("HIDE_FORM"),
						"MINIMIZED_EXPAND_TEXT" => GetMessage("ADD_REVIEW"),
						"SHOW_AVATAR" => "N",
						"SHOW_LINK_TO_FORUM" => "N",
						"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
					),	false
				);?>
			<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("area", "");?>
		</div>
	<?endif;?>
	<?if(($arParams["SHOW_ASK_BLOCK"] == "Y") && (intVal($arParams["ASK_FORM_ID"]))):?>
		<div id="ask_block_content" class="hidden">
			<?$APPLICATION->IncludeComponent(
				"bitrix:form.result.new",
				"inline",
				Array(
					"WEB_FORM_ID" => $arParams["ASK_FORM_ID"],
					"IGNORE_CUSTOM_TEMPLATE" => "N",
					"USE_EXTENDED_ERRORS" => "N",
					"SEF_MODE" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "3600000",
					"LIST_URL" => "",
					"EDIT_URL" => "",
					"SUCCESS_URL" => "?send=ok",
					"CHAIN_ITEM_TEXT" => "",
					"CHAIN_ITEM_LINK" => "",
					"VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),
					"AJAX_MODE" => "Y",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"SHOW_LICENCE" => CNext::GetFrontParametrValue('SHOW_LICENCE'),
				)
			);?>
		</div>
	<?endif;?>
	<script type="text/javascript">
		if($("#ask_block_content").length && $("#ask_block").length){
			$("#ask_block_content").appendTo($("#ask_block"));
			$("#ask_block_content").removeClass("hidden");
		}
		if($(".gifts").length && $("#reviews_content").length){
			$(".gifts").insertAfter($("#reviews_content"));
		}
		if($("#reviews_content").length && (!$(".tabs .tab-content .active").length) || $('.product_reviews_tab.active').length){
			$(".shadow.common").hide();
			$("#reviews_content").show();
		}
		if(!$(".stores_tab").length){
			$('.item-stock .store_view').removeClass('store_view');
		}
		viewItemCounter('<?=$arResult["ID"];?>','<?=current($arParams["PRICE_CODE"]);?>');
	</script>
<?endif;?>
<?if (isset($templateData['TEMPLATE_LIBRARY']) && !empty($templateData['TEMPLATE_LIBRARY'])){
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
		$loadCurrency = Loader::includeModule('currency');
	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency){?>
		<script type="text/javascript">
			BX.Currency.setCurrencies(<? echo $templateData['CURRENCIES']; ?>);
		</script>
	<?}
}?>
<script type="text/javascript">
	var viewedCounter = {
		path: '/bitrix/components/bitrix/catalog.element/ajax.php',
		params: {
			AJAX: 'Y',
			SITE_ID: "<?= SITE_ID ?>",
			PRODUCT_ID: "<?= $arResult['ID'] ?>",
			PARENT_ID: "<?= $arResult['ID'] ?>"
		}
	};
	BX.ready(
		BX.defer(function(){
			$('body').addClass('detail_page');
			<?//if(!isset($templateData['JS_OBJ'])){?>
				BX.ajax.post(
					viewedCounter.path,
					viewedCounter.params
				);
			<?//}?>
			if( $('.stores_tab').length ){
				var objUrl = parseUrlQuery(),
				add_url = '';
				if('clear_cache' in objUrl)
				{
					if(objUrl.clear_cache == 'Y')
						add_url = '?clear_cache=Y';
				}
				$.ajax({
					type:"POST",
					url:arNextOptions['SITE_DIR']+"ajax/productStoreAmount.php"+add_url,
					data:<?=CUtil::PhpToJSObject($templateData["STORES"], false, true, true)?>,
					success: function(data){
						var arSearch=parseUrlQuery();
						$('.tab-content .tab-pane .stores_wrapp').html(data);
						if("oid" in arSearch)
							$('.stores_tab .sku_stores_'+arSearch.oid).show();
						else
							$('.stores_tab .stores_wrapp > div:first').show();

					}
				});
			}
		})
		
	);
</script>
<?if($_REQUEST && isset($_REQUEST['formresult'])):?>
	<script>
	$(document).ready(function() {
		if($('#ask_block .form_result').length){
			$('.product_ask_tab').trigger('click');
		}
	});
	</script>
<?endif;?>
<?if(isset($_GET["RID"])){?>
	<?if($_GET["RID"]){?>
		<script>
			$(document).ready(function() {
				$("<div class='rid_item' data-rid='<?=htmlspecialcharsbx($_GET["RID"]);?>'></div>").appendTo($('body'));
			});
		</script>
	<?}?>
<?}?>
<?if($_GET['rev']){?>
	<script>
	$(window).load(function(){
		$('a[href="#review"]').first().click();
		$('#reviews_content').show();
		document.getElementById('reviews_content').scrollIntoView();
	})
	</script>
<?}?>

<?if($_GET['oid']){
	$res = CIBlockElement::GetProperty(20, $_GET['oid'], "sort", "asc", array("CODE" => "SIZES"));
    if ($ob = $res->GetNext())
    {
       $cur_size = $ob;
    }?>
	<script>
		$(document).ready(function(){
				$('select[data-prop-code="264"]').val('<?=$cur_size['VALUE']?>');
				$('select[data-prop-code="264"]').trigger('change');
		});
	</script>
<?}?>

<?
//$this->__template->SetViewTarget("counter");
$arFilter = Array("IBLOCK_ID"=>$arResult['IBLOCK_ID'], 'ID'=>$arResult['ID']);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, Array("ID","NAME", "SHOW_COUNTER"));
while($ar_fields = $res->GetNext())
{
	if($ar_fields['SHOW_COUNTER']>=10){?>
		<script>
			$('#counter').text('<?echo 'Товар просмотрели '.$ar_fields['SHOW_COUNTER'].' '.plural($ar_fields['SHOW_COUNTER'],'раз', 'раза','раз')?>');
		</script>
	<?}
}
//$this->__template->EndViewTarget();
?>
<form style="display: none; width: 500px;" id="giftorder_form">
			<h2>Заказ на подарок</h2>
			<p>
				Сделайте близким приятный сюрприз с продукцией Cleanelly
			</p>
			<div class="gift_form_line topest">
				<label>Ваше имя</label>
				<input type="text" required name="NAME_1">
			</div>
			<div class="gift_form_line">
				<label>Ваш телефон</label>
				<input class="phone" required type="tel" name="PHONE_1">
			</div>
			<div class="gift_form_line">
				<label>E-mail для уведомлений</label>
				<input required type="text" name="EMAIL">
			</div>
			<div class="gift_form_line">
				<label>Имя получателя</label>
				<input type="text" required name="NAME_2">
			</div>
			<div class="gift_form_line">
				<label>Телефон получателя</label>
				<input class="phone" required type="text" name="PHONE_2">
			</div>
			<input id="prod_gift_id" type="hidden" name="PRODUCT_ID">
			<div>
				<label>Способ доставки</label>
				<div class="gift_delivery_block">
					<span><input type="radio" value="ПВЗ" name="DELIVERY">ПВЗ</span>
					<span><input type="radio" checked value="Курьер" name="DELIVERY">Курьер</span>
				</div>
			</div>
			<div  class="gift_form_line">
				<label>Адрес доставки</label>
				<input type="text" required name="ADRESS">
			</div>
			<div class="gift_order">
				<span>Состав заказа: </span><p id="prod_gift_title"></p>
				<span>Стоимость заказа: </span><p id="prod_gift_price"></p>
			</div>
			<p><b>После подтверждения заказа вам будет добавлена скидка 5%</b></p>
			<div class="oferta_line">
				<input type="checkbox" name="OFERTA" required>
				<label style="margin:0;">Я согласен на обработку персональных данных и с условиями публичной оферты</label>
			</div>
			
			<div class="whatsapp_line">
				<input style="margin:0;" type="checkbox" name="WHATSAPP">
				<label style="margin:0;">Согласовать заказ через WhatsApp</label>
			</div>
			<button style="margin:1em 0;width:100%;" type="submit" class="btn btn-default aprove">Оформить</button>
	</form>
	<div style="display: none; width: 500px;" id="gift_success">
			<h2>Заказ оформлен</h2>
			<p>Номер заказа - <span id="gift_order_id"></span></p>
			<p>
				Мы с вами свяжемся в ближайшее врему и обсудим детали
			</p>
		</div>
	<script>
		$(document).on('submit', '#giftorder_form', function(e){
			e.preventDefault();
			  $.ajax({
				type: "POST",
				url: "/local/ajax/gift_order.php",
				data: $(this).serialize(),
				success: function(html){
					$('#gift_order_id').text(html);
					$.fancybox.close();
					$.fancybox.open({
						href: '#gift_success',
						type: 'inline',
						clickSlide : false,
						helpers: {
							overlay: { closeClick: false } 
						},
						touch: false,
					  });
				}
			 }) 
		})
	</script>