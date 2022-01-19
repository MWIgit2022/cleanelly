<?php
	const WEBHOOK = 'https://dmtextile.bitrix24.ru/rest/11/d7z57d6q28ncw1nm/';

	require $_SERVER['DOCUMENT_ROOT'] . '/local/bitrix24/restapi2.php';

	$restapi = new RestApi(WEBHOOK);

	$hasContacts = array();

	$params = array(
		'FILTER' => array('>CONTACT_ID' => 0),
		'SELECT' => array('ID', 'CONTACT_ID'),
		'ALL' => 'Y',
	);
	$restapi->batchAdd('crm.deal.list', $params, function($res) use (&$hasContacts) {
		if (!empty($res['result'])) {
			foreach ($res['result'] as $row) {
				$hasContacts[$row['CONTACT_ID']] = $row['CONTACT_ID'];
			}
		}
	});

	$resbatch = $restapi->batchCall();

	$params = array(
		'FILTER' => array(),
		'SELECT' => array('ID', 'UF_CRM_1570098415010'),
		'ALL' => 'Y',
	);
	$restapi->batchAdd('crm.contact.list', $params);

	$resbatch = $restapi->batchCall();

	if (!empty($resbatch['crm.contact.list']['result'])) {
		foreach ($resbatch['crm.contact.list']['result'] as $row) {
			$type = isset($hasContacts[$row['ID']]) ? 45 : 47;
			if ($type != $row['UF_CRM_1570098415010']) {
				$params = array(
					'ID' => $row['ID'],
					'FIELDS' => array('UF_CRM_1570098415010' => $type),
				);
				$restapi->batchAdd('crm.contact.update', $params, 'update' . $row['ID']);
				echo "Обновлен контакт №" . $row['ID'] . "\n";
			}
		}
	}

	$resbatch = $restapi->batchCall();
?>