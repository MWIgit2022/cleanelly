<?php

include dirname(__FILE__) . "/install/version.php";

$SBERBANK_CONFIG = array(
	'MODULE_ID' => 'sberbank.ecom2',
	'BANK_NAME' => 'Sberbank',
	'SBERBANK_PROD_URL' => 'https://securepayments.sberbank.ru/payment/rest/',
	'SBERBANK_TEST_URL' => 'https://3dsec.sberbank.ru/payment/rest/',
	'ISO' => array(
	    'USD' => 840,
	    'EUR' => 978,
	    'RUB' => 643,
	    'RUR' => 643,
	    'BYN' => 933
	),
	'MODULE_VERSION' => $arModuleVersion['VERSION'],
	'CALLBACK_BROADCAST' => false,
	'DISCOUNT_HELPER' => false,
	'IGNORE_PRODUCT_TAX' => false,
	"MEASUREMENT_NAME" => 'шт', //FFD v.1.05
	"MEASUREMENT_CODE" => 0, //FFD v.1.2
	"RBS_ENABLE_CALLBACK" => true,
	'CANCEL_ORDER_BY_TIMEOUT' => false,
);

