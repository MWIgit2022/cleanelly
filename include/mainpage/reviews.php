<?
CModule::IncludeModule("forum");
$db_res = CForumMessage::GetList(array("ID"=>"DESC"), array('APPROVED'=>'Y'), false, 30);
while ($ar_res = $db_res->Fetch())
{
	if(stristr(strtolower($ar_res['AUTHOR_NAME']),'admin')==false){
		
		$ar_res['POST_MESSAGE'] = preg_replace('#:f.*:#sUi', '', $ar_res['POST_MESSAGE']);
		$arResult['REVIEWS'][] = array('NAME'=>$ar_res['AUTHOR_NAME'], 'DATE'=>$ar_res['POST_DATE'], 'REVIEW'=>str_replace(array('[',']'),array('<','>'),strtolower($ar_res['POST_MESSAGE'])), 'PRODUCT'=>$ar_res['PARAM2']);
		
		if(!in_array($ids,$ar_res['PARAM2'])){
			$ids[] = $ar_res['PARAM2'];
		}
	}
}

$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", 'ACTIVE');
$arFilter = Array("IBLOCK_ID"=>17, 'ID'=>$ids);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 $file = CFile::ResizeImageGet($arFields['PREVIEW_PICTURE'], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true); 
 $arFields['PREVIEW_PICTURE'] = $file['src'];
 $arResult['PRODUCTS'][$arFields['ID']] = $arFields;
}

?>

<div class="maxwidth-theme" style="position:relative;">
<h2>Отзывы о товарах:</h2>
<ul class="viewed_navigation slider_navigation top_big custom_flex border"></ul>
<div class="review_c content_inner tab flexslider loading_state shadow border custom_flex top_right" data-plugin-options='{"animation": "slide", "animationSpeed": 600, "directionNav": true, "controlNav" :false, "animationLoop": true, "slideshow": false, "controlsContainer": ".viewed_navigation", "counts": [2,2,1,1,1]}'>
<ul class="slides">
<?foreach($arResult['REVIEWS'] as $val){?>
	<li>
		<div class="review_container">
		<div class="product">
			<?if($arResult['PRODUCTS'][$val['PRODUCT']]["ACTIVE"] == 'Y'){?>
				<a href="<?=$arResult['PRODUCTS'][$val['PRODUCT']]["DETAIL_PAGE_URL"]?>">
			<?}?>
				<img src="<?=$arResult['PRODUCTS'][$val['PRODUCT']]['PREVIEW_PICTURE']?>">
				<p style="max-height:3em;overflow:hidden"><?=$arResult['PRODUCTS'][$val['PRODUCT']]['NAME']?></p>
			<?if($arResult['PRODUCTS'][$val['PRODUCT']]["ACTIVE"] == 'Y'){?>
				</a>
			<?}?>
		</div>
		<div class="review">
			<p><b><?=$val['NAME']?> <?=$val['DATE']?></b></p>
			<p><?=mb_strimwidth($val['REVIEW'],0,200,'...')?></p>
			<a style="right:2em;position:absolute;bottom:1em;border-bottom:1px dotted;" href="<?=$arResult['PRODUCTS'][$val['PRODUCT']]["DETAIL_PAGE_URL"]?>?rev=true">Смотреть отзыв в товаре.</a>
		</div>
		</div>
	</li>
<?}?>
</ul>

</div>
</div>