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
				'SELECT' => array('ID', 'UF_CRM_1570098415010'),
			);
			$restapi->batchAdd('crm.contact.list', $params);

			$resbatch = $restapi->batchCall();

			if (!empty($resbatch['crm.contact.list']['result'])) {
				$data = $resbatch['crm.contact.list']['result'][0];
			}
		}

		if (
			$data
			&& (
				($_REQUEST['event'] == 'ONCRMCONTACTADD' &&  $_REQUEST['auth']['application_token'] == 'in20bkmy0lf5lpalfs5ckq3q18w4gsqd')
				|| ($_REQUEST['event'] == 'ONCRMCONTACTUPDATE' &&  $_REQUEST['auth']['application_token'] == '9zratrqkxz0sv2ikl2q8q3pjej6a5tek')
			)
		) {
			$hasDeal = false;
			$params = array(
				'FILTER' => array('CONTACT_ID' => $data['ID']),
				'SELECT' => array('ID', 'CONTACT_ID'),
			);
			$restapi->batchAdd('crm.deal.list', $params, function($res) use (&$hasDeal) {
				if (!empty($res['result'])) {
					$hasDeal = true;
				}
			});

			$resbatch = $restapi->batchCall();

			$newValue = $hasDeal ? 45 : 47;

			if ($data['UF_CRM_1570098415010'] != $newValue) {
				$params = array(
					'ID' => $data['ID'],
					'FIELDS' => array('UF_CRM_1570098415010' => $newValue),
				);
				$restapi->batchAdd('crm.contact.update', $params);

				$resbatch = $restapi->batchCall();
			}
		}

	}

	$log = trim($log) . "\n";
	file_put_contents(__DIR__ . '/../bitrix24.log', $log, FILE_APPEND);

	exit ('ok');
?>