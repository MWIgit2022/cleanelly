<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Общие контакты компании");
?>
<div class="general-map__container">
	<div class="general-map__address">
		<h3>Головной офис:</h3>
		<div itemscope itemtype="http://schema.org/LocalBusiness">
			<div><span itemprop="name">АО ТПК "ДМ Текстиль Менеджмент"</span></div>
			<br>
			<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<meta itemprop="streetAddress" content="ул. Лермонтовская, 197\73">
				<meta itemprop="postalCode" content="344000">
				<meta itemprop="addressLocality" content="г. Ростов-на-Дону">
				<span> 344000, г. Ростов-на-Дону, ул. Лермонтовская, 197\73 </span>
			</div>
			<span>+7 (863) 255-53-20, 255-53-45</span>
			<meta itemprop="telephone" content="+7 (863) 255-53-20">
			<meta itemprop="telephone" content="255-53-45">
			<br>
			<time itemprop="openingHours" datetime="Mo-Fr, 9:00−18:00">с 9.00 - 18.00 по московскому времени в будние дни</time>
		</div>
		<br>
		<div itemscope itemtype="http://schema.org/LocalBusiness">
			<h3 itemprop="name">Фабрика ОАО «Донецкая Мануфактура М»</h3>
			<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<meta itemprop="streetAddress" content="пр-кт Ленина, 29 ">
				<meta itemprop="addressRegion" content="Ростовская область">
				<meta itemprop="addressLocality" content="г. Донецк">
				<span>Ростовская область, г. Донецк, пр-кт Ленина, 29</span>
			</div>
			<span itemprop="telephone">+7 (86368) 2-23-53</span>
			<br>
			<time itemprop="openingHours" datetime="Mo-Fr, 9:00−18:00">с 9.00 - 18.00 по московскому времени в будние дни</time>
		</div>
		<br>
		<div itemscope itemtype="http://schema.org/LocalBusiness">
			<h3 itemprop="name"> ООО "Клинелли Онлайн"</h3>
			<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
				<meta itemprop="streetAddress" content="ул. Лермонтовская, 197\73">
				<meta itemprop="postalCode" content="344000">
				<meta itemprop="addressLocality" content="г. Ростов-на-Дону">
				<span> 344000, г. Ростов-на-Дону, ул. Лермонтовская, 197\73 </span>
			</div>
			<span>+7 (863) 255-53-20, 255-53-45</span>
			<meta itemprop="telephone" content="+7 (863) 255-53-20">
			<meta itemprop="telephone" content="255-53-45">
			<br>
			<time itemprop="openingHours" datetime="Mo-Fr, 9:00−18:00">с 9.00 - 18.00 по московскому времени в будние дни</time>
		</div>
	</div>
	<div class="general-map__yandex">
		<?
		$arPlacemark = [];
		$arCoords = HBUtils::GetSettings("settings")["GENERAL_COORDS"];
		foreach ($arCoords["VALUE"] as $key => $item) {
			$coords = explode(";", $item);
			$arPlacemark[] = [
				"LAT" => $coords[0],
				"LON" => $coords[1],
				"TEXT" => $arCoords["DESCRIPTION"][$key]
			];
		}
		$APPLICATION->IncludeComponent("bitrix:map.yandex.view", 
			"general_map",
			 Array(
				"API_KEY" => "",	// Ключ API
				"CONTROLS" => array(	// Элементы управления
					0 => "ZOOM",
					1 => "SMALLZOOM",
					2 => "MINIMAP",
				),
				"INIT_MAP_TYPE" => "MAP",	// Стартовый тип карты
				"MAP_DATA" => serialize(["yandex_scale"=>12,"PLACEMARKS"=>$arPlacemark]),	// Данные, выводимые на карте
				"MAP_HEIGHT" => "600",	// Высота карты
				"MAP_ID" => "MAP_v33",	// Идентификатор карты
				"MAP_WIDTH" => "auto",	// Ширина карты
				"OPTIONS" => array(	// Настройки
					0 => "ENABLE_SCROLL_ZOOM",
					1 => "ENABLE_DBLCLICK_ZOOM",
					2 => "ENABLE_DRAGGING",
				),
				"COMPONENT_TEMPLATE" => "map"
			),
			false
		);?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>