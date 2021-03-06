<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
die();
$APPLICATION->SetTitle(GetMessage('TITLE'));
?>
<? if (!empty($arResult['FAVORITES'])) { ?>
<div class='favorites_div'>
	<table class='favorites_table'>
		<tr class='favorites_tr'>
			<td class="favorites_td">
				<h3><?= GetMessage('IMAGES') ?></h3>
			</td>
			<td class="favorites_td">
				<h3><?= GetMessage('INFO') ?></h3>
			</td>
			<td class="favorites_td">
				<h3><?= GetMessage('PRICE_LABEL') ?></h3>
			</td>
			<td class="favorites_td"></td>
		</tr>
		<?php foreach ($arResult['FAVORITES'] as $key => $favorite) { ?>
			<tr class='favorites_tr'>
				<td class="favorites_td"><img src="<?= SITE_SERVER_NAME . $favorite['RESIZE']['PREVIEW_PICTURE']['src'] ?>"></td>
				<td class="favorites_td">
					<a href="<?= $favorite['DETAIL_PAGE_URL'] ?>">
						<p class='favorites_p'><?= $favorite['NAME'] ?></p>
					</a>
					<? if (!empty($favorite['HIT'])) { ?>
					<p><?= GetMessage('STOCKS') ?>:
						<? foreach ($favorite['HIT'] as $key => $hit) { ?>
						<span class='fav_sticker_<?= strtolower($key) ?> favorites_sticker '><?= $hit['VALUE_ENUM'] ?></span>
						<? } ?>	
					</p>
					<? } 
						if (!empty($favorite['OFFERS'])) { ?>
					<p><?= GetMessage('SIZES') ?>:
						<? foreach ($favorite['OFFERS'] as $key => $offers) { ?>
						<span class='favorites_sticker'><?= $offers['PROPERTY_SIZES_VALUE'] ?></span>
						<? } ?>	
					</p>
					<? } ?>			
				</td>
				<td class="favorites_td">
					<? if ($favorite['PRICE']['DISCOUNT'] == 'Y') { ?>
					<p class='favorites_p'><?= GetMessage('PRICE', ['#PRICE#' => $favorite['PRICE']['DISCOUNT_PRICE']]) ?></p>
					<p class='fav_old_price'><?= GetMessage('PRICE', ['#PRICE#' => $favorite['PRICE']['BASE_PRICE']]) ?></p>
					<? } else { ?>
					<p class='favorites_p'><?= GetMessage('PRICE', ['#PRICE#' => $favorite['PRICE']['BASE_PRICE']]) ?></p>
					<? } ?>
				</td>
				<td class="favorites_td">
					<p id='productId-<?= $favorite['ID'] ?>' class='favorites' data-id='<?= $favorite['ID'] ?>'>
						<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="30" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
							<g>
								<g>
									<path id="fav_color" class="icon_favorite" d="M376,30c-27.783,0-53.255,8.804-75.707,26.168c-21.525,16.647-35.856,37.85-44.293,53.268
										c-8.437-15.419-22.768-36.621-44.293-53.268C189.255,38.804,163.783,30,136,30C58.468,30,0,93.417,0,177.514
										c0,90.854,72.943,153.015,183.369,247.118c18.752,15.981,40.007,34.095,62.099,53.414C248.38,480.596,252.12,482,256,482
										s7.62-1.404,10.532-3.953c22.094-19.322,43.348-37.435,62.111-53.425C439.057,330.529,512,268.368,512,177.514
										C512,93.417,453.532,30,376,30z"/>
								</g>
								<g>
									<path d="M474.644,74.27C449.391,45.616,414.358,29.836,376,29.836c-53.948,0-88.103,32.22-107.255,59.25
										c-4.969,7.014-9.196,14.047-12.745,20.665c-3.549-6.618-7.775-13.651-12.745-20.665c-19.152-27.03-53.307-59.25-107.255-59.25
										c-38.358,0-73.391,15.781-98.645,44.435C13.267,101.605,0,138.213,0,177.351c0,42.603,16.633,82.228,52.345,124.7
										c31.917,37.96,77.834,77.088,131.005,122.397c19.813,16.884,40.302,34.344,62.115,53.429l0.655,0.574
										c2.828,2.476,6.354,3.713,9.88,3.713s7.052-1.238,9.88-3.713l0.655-0.574c21.813-19.085,42.302-36.544,62.118-53.431
										c53.168-45.306,99.085-84.434,131.002-122.395C495.367,259.578,512,219.954,512,177.351
										C512,138.213,498.733,101.605,474.644,74.27z M309.193,401.614c-17.08,14.554-34.658,29.533-53.193,45.646
										c-18.534-16.111-36.113-31.091-53.196-45.648C98.745,312.939,30,254.358,30,177.351c0-31.83,10.605-61.394,29.862-83.245
										C79.34,72.007,106.379,59.836,136,59.836c41.129,0,67.716,25.338,82.776,46.594c13.509,19.064,20.558,38.282,22.962,45.659
										c2.011,6.175,7.768,10.354,14.262,10.354c6.494,0,12.251-4.179,14.262-10.354c2.404-7.377,9.453-26.595,22.962-45.66
										c15.06-21.255,41.647-46.593,82.776-46.593c29.621,0,56.66,12.171,76.137,34.27C471.395,115.957,482,145.521,482,177.351
										C482,254.358,413.255,312.939,309.193,401.614z"/>
								</g>	
							</g>
						</svg>
					</p>
				</td>
			</tr>
		<?php } ?>
	</table>
</div>
<? } else { ?>
	<h2><?= GetMessage('FAVORITES_EMPTY') ?></h2>
<? } ?>