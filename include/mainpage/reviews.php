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
<style>
	.product{max-width:150px;}
	.review_container{display:flex;height:100%;}
	.review{margin-left:2em;text-align:left;background:#eee;flex-grow:1;padding:1em;}
	.review_services{display:flex;align-items:center;justify-content:space-around;margin:2em;flex-wrap:wrap;}
	.review_services div{margin:1em;max-width:250px;text-align:center;}
	.review_services div a{font-size:1.5em;font-weight:600;}
	.review_services div a:hover{color:#000}
	.review_services div a img{max-width:150px;}
	.content_inner:not(.flexslider) ul{
		display:flex; flex-wrap:wrap;
	}
	.content_inner ul li{
		padding: 0 1em;
	}
	.content_inner:not(.flexslider) ul li{
		flex-basis:45%;flex-grow:1;margin:1em;
	}
	 .review_services div:first-child {
		border-left:1px dotted;
		border-right:1px dotted;
		padding:1em;
		
	} 
	 .review_services div:first-child:hover {
		 border:0;
		 border-top:1px dotted;
		border-bottom:1px dotted;
		
	 }
	@media(max-width:600px){
		.product{max-width:100%;margin:0 auto;text-align:center}
		.review_container{
			flex-wrap:wrap;
		}
		.content_inner:not(.flexslider) ul li{
			flex-basis:100%
		}
	}
	
</style>
<div class="maxwidth-theme" style="position:relative;">
<h2>Отзывы о товарах:</h2>
<ul class="viewed_navigation slider_navigation top_big custom_flex border"></ul>
<div class="content_inner tab flexslider loading_state shadow border custom_flex top_right" data-plugin-options='{"animation": "slide", "animationSpeed": 600, "directionNav": true, "controlNav" :false, "animationLoop": true, "slideshow": false, "controlsContainer": ".viewed_navigation", "counts": [2,2,1,1,1]}'>
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