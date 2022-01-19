<?php
	const WEBHOOK = 'https://dmtextile.bitrix24.ru/rest/11/d7z57d6q28ncw1nm/';

	require $_SERVER['DOCUMENT_ROOT'] . '/local/bitrix24/restapi2.php';

	$restapi = new RestApi(WEBHOOK);

	$log = date('Y-m-d H:i:s') . ') ';

	if (!empty($_REQUEST['event'])) {
		$ID = (int)$_REQUEST['data']['FIELDS']['ID'];
		$log .= "событие: {$_REQUEST['event']}, ID - {$ID}";
		$data = false;

		if ($ID > 0) {
			$params = array(
				'FILTER' => array('ID' => $ID),
				'SELECT' => array('ID', 'CONTACT_ID', 'ORIGINATOR_ID', 'SOURCE_ID', 'CATEGORY_ID'),
			);
			$restapi->batchAdd('crm.deal.list', $params);

			$resbatch = $restapi->batchCall();

			if (!empty($resbatch['crm.deal.list']['result'])) {
				$data = $resbatch['crm.deal.list']['result'][0];
			}
		}

		if ($data && $_REQUEST['event'] == 'ONCRMDEALUPDATE' &&  $_REQUEST['auth']['application_token'] == 'bq19s1l4u6hhvhcz7qqrja654n044aav') {
			if (!empty($data['CONTACT_ID'])) {
				$params = array(
					'ID' => $data['CONTACT_ID'],
					'FIELDS' => array('UF_CRM_1570098415010' => 45),
				);
				$restapi->batchAdd('crm.contact.update', $params);
			}
			$resbatch = $restapi->batchCall();

			//$_REQUEST['event'] = 'ONCRMDEALADD';
			//$_REQUEST['auth']['application_token'] = 'b0jmm21bpyuujd8g1130oj7kg767ucm5';
		}

		if ($data && $_REQUEST['event'] == 'ONCRMDEALADD' &&  $_REQUEST['auth']['application_token'] == 'b0jmm21bpyuujd8g1130oj7kg767ucm5') {
			if (!empty($data['CONTACT_ID'])) {
				$params = array(
					'ID' => $data['CONTACT_ID'],
					'FIELDS' => array('UF_CRM_1570098415010' => 45),
				);
				$restapi->batchAdd('crm.contact.update', $params);
			}

			if ($data['ORIGINATOR_ID'] > 0) {
				$update = array();

				if ($data['SOURCE_ID'] != 'STORE') {
					$update['SOURCE_ID'] = 'STORE';
				}
				if ($data['CATEGORY_ID'] != '1') {
					$params = array(
						'TEMPLATE_ID' => 81,
						'DOCUMENT_ID' => array('crm', 'CCrmDocumentDeal', $ID),
					);
					$restapi->batchAdd('bizproc.workflow.start', $params);

					$log .= ', сделка перенесена в направление "Интернет-магазин"';
				}

				if (count($update) > 0) {
					$params = array(
						'ID' => $ID,
						'FIELDS' => $update,
					);
					$restapi->batchAdd('crm.deal.update', $params);

					$log .= ', изменен источник на "Интернет-магазин"';
				}

			}
			$resbatch = $restapi->batchCall();

			file_put_contents(__DIR__ . '/rest.log', print_r($resbatch,1), FILE_APPEND);
		}
	}

	$log = trim($log) . "\n";
	file_put_contents(__DIR__ . '/../bitrix24.log', $log, FILE_APPEND);

	exit ('ok');
?>