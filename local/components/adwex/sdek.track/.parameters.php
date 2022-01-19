<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$ipolAuth = [
    'ACCOUNT' => '',
    'SECURE' => ''
];

if (Cmodule::includeModule('ipol.sdek')) {
    // $ipolAuth = \sdekHelper::defineAuth();
}

$arComponentParameters = Array(
	'PARAMETERS' => array(
		'CDEK_ACCOUNT' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('CDEK_ACCOUNT'),
			'TYPE' => 'TEXT',
			'DEFAULT' => $ipolAuth['ACCOUNT']
		),
		'CDEK_PASSWORD' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('CDEK_PASSWORD'),
			'TYPE' => 'TEXT',
			'DEFAULT' => $ipolAuth['SECURE']
		),
		'SHOW_HISTORY' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SHOW_HISTORY'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		),
		'SHOW_FULL_HISTORY' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SHOW_FULL_HISTORY'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N'
		),
		'CALCULATE' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('CALCULATE'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		)
    )
);
?>