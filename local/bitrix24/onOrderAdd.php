<?php
	const REST_CALL = 'https://dmtextile.bitrix24.ru/rest/11/d7z57d6q28ncw1nm/';
	const DIR_PATH = __DIR__;

	require __DIR__ . '/restapi.php';

	$RESULT = '';

	$EVENT = isset($_REQUEST['event']) ? $_REQUEST['event'] : '';
	$ID = (isset($_REQUEST['data']) && isset($_REQUEST['data']['FIELDS']) && !empty($_REQUEST['data']['FIELDS']['ID'])) ? (int)$_REQUEST['data']['FIELDS']['ID'] : 0;
	$AUTH = (isset($_REQUEST['auth']) && !empty($_REQUEST['auth']['application_token'])) ? $_REQUEST['auth']['application_token'] : '';
	$DATA_LOG = array();

	$DATA_LOG['ID'] = $ID;

	if (!empty($EVENT)) {
		$rest = new RestApi();

		if (
			(($EVENT == 'ONCRMDEALADD' && $AUTH == 'h5r0w3n165o0udlln1xyzf2djvxqb578')
			|| ($EVENT == 'ONORDERUPDATE' && $AUTH == 'wh4fb0ys989nm76oq076aa91xwvvb8r4'))
			&& !empty($ID)
		) {
			$arDeal = false;

			if ($EVENT == 'ONCRMDEALADD') {
				$params = array(
					'filter' => array('ID' => $ID),//, 'ORIGINATOR_ID' => 67),
					'select' => array('ID', 'TITLE', 'ORIGIN_ID', 'COMPANY_ID', 'CONTACT_ID', 'UF_*'),
				);
			} elseif ($EVENT == 'ONORDERUPDATE') {
				$params = array(
					'filter' => array('ORIGIN_ID' => $ID),//, 'ORIGINATOR_ID' => 67),
					'select' => array('ID', 'TITLE', 'ORIGIN_ID', 'COMPANY_ID', 'CONTACT_ID', 'UF_*'),
				);
			}
			$rest->batchAdd('crm.deal.list', $params);

			$resBatch = $rest->batchCall();

			$res = $resBatch['crm.deal.list'];
			if (!empty($res) && !empty($res['result'])) {
				$arDeal = $res['result'][0];
			}

			if ($arDeal && $arDeal['ORIGIN_ID'] > 0) {
				define('STOP_STATISTICS', true);
				include $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

				\Bitrix\Main\Loader::IncludeModule('sale');

				$order = \Bitrix\Sale\Order::load($arDeal['ORIGIN_ID']);

				$arProps = array();
				$arPropsRaw = array();
				$props = $order->loadPropertyCollection()->getArray();
				if (!empty($props) && !empty($props['properties'])) {
					foreach ($props['properties'] as $item) {
						if ($item['TYPE'] == 'FILE') {
							$value = reset($item['VALUE']);
						} else {
							$value = trim(implode(', ', $item['VALUE']));
						}

						if ($value == '') continue;

						if ($item['TYPE'] == 'LOCATION') {
							$strValue = array();
							$res = \Bitrix\Sale\Location\LocationTable::getList(array(
								'filter' => array('=CODE' => $value, '=PARENTS.NAME.LANGUAGE_ID' => 'ru'),
								'select' => array('I_ID' => 'PARENTS.ID', 'I_NAME_RU' => 'PARENTS.NAME.NAME'),
								'order' => array('PARENTS.DEPTH_LEVEL' => 'asc')
							));
							while($loc = $res->fetch()) {
								$strValue[] = $loc['I_NAME_RU'];
							}
							$value = trim(implode(', ', $strValue));
						}

						$arProps[$item['CODE']] = $item['NAME'] . ': ' . $value;
						$arPropsRaw[$item['CODE']] = $value;
					}
				}

				$update = array();

				if ($arDeal['UF_CRM_5DC55869BECB5'] != $arPropsRaw['Coupon']) {
					$update['UF_CRM_5DC55869BECB5'] = $arPropsRaw['Coupon'];
				}

				if (count($update) > 0) {
					$params = array(
						'id' => $arDeal['ID'],
						'fields' => $update,
					);
					$res = $rest->call('crm.deal.update', $params);

					if ($arDeal['CONTACT_ID'] > 0) {
						$update = array();
						$update['UF_CRM_5DC55869A005D'] = $arPropsRaw['Coupon'];

						$params = array(
							'id' => $arDeal['CONTACT_ID'],
							'fields' => $update,
						);
						$res = $rest->call('crm.contact.update', $params);
					}

					$RESULT = 'Произведен импорт';
				}
			}
		}

		saveLog($EVENT, $DATA_LOG, $RESULT);
	}

	exit ('end.');
?> 