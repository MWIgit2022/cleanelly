<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Оставить отзыв");
$APPLICATION->SetPageProperty("title", "Оставить отзыв");
$APPLICATION->SetTitle("Оставить отзыв");
include('php_script.php');
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

<p>Оставьте отзыв о работе магазина или о товарах Cleanelly</p>
<div class="review_services">
	<div><a href="javascript:void(0)" onclick="$.fancybox.open('/upload/medialibrary/e88/gp86t406d5xe6kyqdtb74gwg1p5xz7yx/ooooo.png');">Оставить отзыв<br> о товаре - инструкция</a></div>
	<div><a href="https://market.yandex.ru/shop/549178/reviews/add"><img src="img/ymarket.jpg"></a></div>
	<div><a href="https://otzovik.com/?search_text=cleanelly&x=23&y=15"><img src="img/otzovik.jpg"></a></div>
</div>
<div style="position:relative;">
<h2>Отзывы о товарах:</h2>
<div  class="content_inner tab loading_state shadow border custom_flex top_right">
<?
$arPageIt = paginator($arResult['REVIEWS']);
?>
<ul class="slides">
<?foreach($arPageIt['ITEMS'] as $val){?>
	<li>
		<div class="review_container">
		<div class="product">
			<?if($arResult['PRODUCTS'][$val['PRODUCT']]["ACTIVE"] == 'Y'){?>
				<a href="<?=$arResult['PRODUCTS'][$val['PRODUCT']]["DETAIL_PAGE_URL"]?>">
			<?}?>
				<img src="<?=$arResult['PRODUCTS'][$val['PRODUCT']]['PREVIEW_PICTURE']?>">
				<p><?=$arResult['PRODUCTS'][$val['PRODUCT']]['NAME']?></p>
			<?if($arResult['PRODUCTS'][$val['PRODUCT']]["ACTIVE"] == 'Y'){?>
				</a>
			<?}?>
		</div>
		<div class="review">
			<p><b><?=$val['NAME']?> <?=$val['DATE']?></b></p>
			<p><?=$val['REVIEW']?></p>
		</div>
		</div>
	</li>
<?}?>
</ul>
</div>
<?=$arPageIt['PAGINATION']?>
</div> 

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>