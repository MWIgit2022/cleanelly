<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Скидки");
?>
<style>
.loyal_underheader p{
	font-style:italic;
	margin:0;
}
</style>
<div style="display:flex;gap:1em;align-items:center">
<p style="padding: 1em;
    border: 1px solid;
    position: relative;
    border-radius: 50%;min-width:150px;height:150px;"><span style="position: absolute;
    font-size: 5em;
    font-weight: 600;
    top: 50%;
    right: 20%;">-5%</span></p>
<p>
	 При оплате картой на сайте <b>заказов от 5 000 руб </b>- будет применена скидка 5%, которая суммируется со всеми скидками и акциями! Скидка применяется автоматически при выборе способа оплаты "оплатить на сайте картой". Проверить - применилась ли эта скидка можно поменяв способ оплаты "при получении".
</p>
</div>
<hr>
<div style="display:flex;gap:1em;align-items:center;margin-top:2em;">
<p>
	При совершении единовременной покупки в интернет-магазине Cleanelly на сумму от 30&nbsp;000 рублей, Вы можете получить скидку 20% на этот заказ.<br>
	 Скидка не суммируется с акциями и распродажами.<br>
	  Для получения этой скидки, необходимо сделать заказ и в комментарии к заказу написать «Скидка 20%», с Вами свяжется менеджер интернет-магазина для уточнения необходимой информации.<br>
	  По вопросам получения скидки, можно связаться со службой поддержки интернет-магазина:<br>
	   <a href="tel:8-800-511-52-03">8-800-511-52-03</a> Бесплатная горячая линия с 9.00 - 18.00 по московскому времени в&nbsp;будние дни. <br>
 <a href="mailto:Help@cleanelly.ru">Help@cleanelly.ru</a>. <br>
</p>
<p style="padding: 1em;
    border: 1px solid;
    position: relative;
    border-radius: 50%;min-width:150px;height:150px;"><span style="position: absolute;
    font-size: 5em;
    font-weight: 600;
    top: 50%;
    right: 20%;">-20%</span></p>
<p>
</div>


<h2 style="margin:2em 0;">Временно действующие акции</h2>
 <?
 global $pageFilter;
 $pagefilter=array('PROPERTY_ON_SALE_PAGE_VALUE'=>'да');
 $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"front_blog",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("PREVIEW_PICTURE", "NAME"),
		"FILTER_NAME" => "pagefilter",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "19",
		"IBLOCK_TYPE" => "aspro_next_content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array("", ""),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_DETAIL_LINK" => "Y",
		"SHOW_GOODS" => "Y",
		"SHOW_SECTIONS" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"S_ORDER_PRODUCT" => ""
	)
);?>
<h2 style="margin-top:2em;">Программа лояльности</h2>
<div class="loyal_underheader">
	<p>
		 В интернет-магазине также действует Ваша дисконтная карта Cleanelly.
	</p>
	<p>
		 Совершая единовременную покупку от 2 000 руб. в любом фирменном магазине или в интернет-магазине Cleanelly, Вы получаете дисконтную карту Cleanelly с 5% скидкой.
	</p>
	<p>
		 Данная дисконтная карта является накопительной.
	</p>
</div>
<style>
.loyal_container{display:flex;gap:1em;flex-wrap:wrap;argin-top:1em}
.loyal_container .block {display:flex;gap:1em;align-items:center;margin:1em 0;border: 1px solid;padding: 2em;border-radius: 1em; flex-wrap:wrap;width:420px}
.card_fake{height:2em;width:100%;background:#000;flex-basis:100%;}
.abs_loyal_cont{padding: 1em; border: 1px solid; position: relative;border-radius: 50%;min-width:100px;height:100px;}
.abs_loyal_val{position: absolute;font-size: 3em;font-weight: 600;top:50%;left:50%;transform:translate(-50%, -50%);}
.abs_loyal_desc{font-size:1.25em;}
</style>
<div class="loyal_container">
<div class="block">
<div class="card_fake"></div>
<p class="abs_loyal_cont"><span class="abs_loyal_val">5%</span></p>
<p class="abs_loyal_desc">
	2 000 – 9 999 рублей
</p>
</div>
<div class="block">
<div class="card_fake"></div>
<p class="abs_loyal_cont"><span class="abs_loyal_val">10%</span></p>
<p class="abs_loyal_desc">
	10 000 – 19 999 рублей
</p>
</div>
<div class="block">
<div class="card_fake"></div>
<p class="abs_loyal_cont"><span class="abs_loyal_val">15%</span></p>
<p class="abs_loyal_desc">
	20 000 – 29 999 рублей
</p>
</div>
<div class="block">
<div class="card_fake"></div>
<p class="abs_loyal_cont"><span class="abs_loyal_val">20%</span></p>
<p class="abs_loyal_desc">
	30 000 рублей и выше
</p>
</div>
</div>
<p style="margin-top:2em;">
	* Дисконтная карта не действует на специальные акции и распродажи.
</p>
<p>
	* Дисконтная карта действует во всех фирменных магазинах Cleanelly.
</p>
<p>
	* Дисконтная карта Cleanelly действует в интернет-магазине <a href="/">Cleanelly.ru</a>.
</p>
<p>* Чтобы использовать дисконтную карту в интернет-магазине, необходимо при совершении заказа в корзине интернет-магазина полностью ввести штрих-код карты, указанный на обороте в поле Код промокода\скидочной карты. Скидка применится автоматически.</p>


<p>
*  По дисконтной карте Cleanelly за 2 недели до Дня Рождения и 2 недели после, к скидке по карте добавляется +10%**!<br>
</p>
<p>
* Для этого в комментарии к заказу необходимо указать "доп скидка ко дню рождения".<br>
</p>
<p style="margin-top:2em;">
	 **Обращаем внимание, что скидки по карте, дню рождения, и оплата на сайте применяются поэтапно (сначала применяется одна скидка, от получившейся суммы применяется вторая скидка, и от получившейся суммы- третья).
</p>
<p>
</p>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>