<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?// intro text?>
<div class="text_before_items">
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		)
	);?>
</div>
<?
$arItemFilter = CNext::GetIBlockAllElementsFilter($arParams);

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$itemsCnt = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());?>

<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?// rss
	if($arParams['USE_RSS'] !== 'N')
		CNext::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
	?>
	<?
	// Получаем список годов (папок)
	/* $obCIBlockSection = new CIBlockSection; 
	$arOrder = [
		"SORT" => "DESC",
		"NAME" => "DESC",
	];
	
	$arFilter = [
		"IBLOCK_ID" => $arParams['IBLOCK_ID'], 
		"ACTIVE" => "Y", 
	];
	
	$arSelect = [
		"ID","NAME","CODE","SECTION_PAGE_URL",
	];
	
	$arNavParams = false;
	
	$SectList = $obCIBlockSection->GetList($arOrder, $arFilter, false, $arSelect, $arNavParams);
	$menuYear = [
		"0" => [
			"NAME" => "За всё время",
			"LINK" => "/sale/",
			"ACTIVE" => true,
		],
	];
	while($SectListGet = $SectList->GetNext())
	{
		$menuYear[$SectListGet['ID']]['ID'] = $SectListGet['ID'];
		$menuYear[$SectListGet['ID']]['CODE'] = $SectListGet['CODE'];
		$menuYear[$SectListGet['ID']]['NAME'] = $SectListGet['NAME'];
		$menuYear[$SectListGet['ID']]['LINK'] = $SectListGet['SECTION_PAGE_URL'];
	} */
	
	?>
	
	<?/*$arItems = CNextCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CNextCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arItemFilter, false, false, array('ID', 'NAME', 'ACTIVE_FROM'));
	$arYears = array();
	if($arItems)
	{
		foreach($arItems as $arItem)
		{
			if($arItem['ACTIVE_FROM'])
			{
				if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
					$arYears[$arDateTime['YYYY']] = $arDateTime['YYYY'];
			}
		}
		if($arYears)
		{
			if($arParams['USE_FILTER'] != 'N')
			{
				rsort($arYears);
				$bHasYear = (isset($_GET['year']) && (int)$_GET['year']);
				$year = ($bHasYear ? (int)$_GET['year'] : 0);?>
				<div class="head-block top">
					<div class="bottom_border"></div>
					<div class="item-link <?=($bHasYear ? '' : 'active');?>">
						<div class="title">
							<?if($bHasYear):?>
								<a class="btn-inline black" href="<?=$arResult['FOLDER'];?>"><?=GetMessage('ALL_TIME');?></a>
							<?else:?>
								<span class="btn-inline black"><?=GetMessage('ALL_TIME');?></span>
							<?endif;?>
						</div>
					</div>
					<?foreach($arYears as $value):
						$bSelected = ($bHasYear && $value == $year);?>
						<div class="item-link <?=($bSelected ? 'active' : '');?>">
							<div class="title btn-inline black">
								<?if($bSelected):?>
									<span class="btn-inline black"><?=$value;?></span>
								<?else:?>
									<a class="btn-inline black" href="<?=$APPLICATION->GetCurPageParam('year='.$value, array('year'));?>"><?=$value;?></a>
								<?endif;?>
							</div>
						</div>
					<?endforeach;?>
				</div>
				<?
				if($bHasYear)
				{
					$GLOBALS[$arParams["FILTER_NAME"]][] = array(
						">=DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".$year, "DD.MM.YYYY"),
						"<DATE_ACTIVE_FROM" => ConvertDateTime("01.01.".($year+1), "DD.MM.YYYY"),
					);
				}?>
			<?}
		}
	}*/?>

	<?global $arTheme, $isMenu;?>

	<?if(!$isMenu):?>
		<div class="sub_container fixed_wrapper">
		<div class="row">
			<div class="col-md-12">
	<?endif;?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		$APPLICATION->RestartBuffer();
	}?>
	<?// section elements?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["NEWS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
	{
		die();
	}?>
	<?// ask block?>
	<?ob_start();?>
		<div class="ask_a_question">
			<div class="inner">
				<div class="text-block">
					<?$APPLICATION->IncludeComponent(
						 'bitrix:main.include',
						 '',
						 Array(
							  'AREA_FILE_SHOW' => 'page',
							  'AREA_FILE_SUFFIX' => 'ask',
							  'EDIT_TEMPLATE' => ''
						 )
					);?>
				</div>
			</div>
			<div class="outer">
				<span><span class="btn btn-default btn-lg white animate-load" data-event="jqm" data-param-form_id="ASK" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : GetMessage('S_ASK_QUESTION'))?></span></span></span>
			</div>
		</div>
	<?$html = ob_get_contents();?>
	<?ob_end_clean();?>

	<?if(!$isMenu):?>
			</div>
			<?/*div class="col-md-3  with-padding-left hidden-xs hidden-sm">
				<div class="fixed_block_fix"></div>
				<div class="ask_a_question_wrapper">
					<?=$html;?>
				</div>
			</div*/?>
		</div>
		</div>
	<?else:?>
		<?$this->SetViewTarget('under_sidebar_content');?>
			<?=$html;?>
		<?$this->EndViewTarget();?>
	<?endif;?>
<?endif;?>
<div class="always_sales">
<p class="perc"><span >-5%</span></p>
<p>
	 При оплате картой на сайте <b>заказов от 5 000 руб </b>- будет применена скидка 5%, которая суммируется со всеми скидками и акциями! Скидка применяется автоматически при выборе способа оплаты "оплатить на сайте картой". Проверить - применилась ли эта скидка можно поменяв способ оплаты "при получении".
</p>
</div>
<hr>
<div class="always_sales">
<p class="perc"><span >-20%</span></p>
<p>
<p>
	При совершении единовременной покупки в интернет-магазине Cleanelly на сумму от 30&nbsp;000 рублей, Вы можете получить скидку 20% на этот заказ.<br>
	 Скидка не суммируется с акциями и распродажами.<br>
	  Для получения этой скидки, необходимо сделать заказ и в комментарии к заказу написать «Скидка 20%», с Вами свяжется менеджер интернет-магазина для уточнения необходимой информации.<br>
	  По вопросам получения скидки, можно связаться со службой поддержки интернет-магазина:<br>
	   <a href="tel:8-800-511-52-03">8-800-511-52-03</a> Бесплатная горячая линия с 9.00 - 18.00 по московскому времени в&nbsp;будние дни. <br>
 <a href="mailto:Help@cleanelly.ru">Help@cleanelly.ru</a>. <br>
</p>

</div>


 
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