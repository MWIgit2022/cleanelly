<?php
use Bitrix\Main\Diag\Debug;

class CCleanellyEvents
{
	private static $WEBHOOK = 'https://dmtextile.bitrix24.ru/rest/11/d7z57d6q28ncw1nm/';
	private static $HB_ID_QUEUE = 6;

	public static function exportAll ()
	{
		CModule::IncludeModule('sale');

		$res = CSaleOrder::GetList(array('DATE_INSERT' => 'ASC'), array(), false, false, array('ID'));
		while ($row = $res->Fetch()) {
			self::addQueue('ORDER', $row['ID']);
		}

		$res = CUser::GetList($by = 'id', $order = 'asc', array());
		while ($row = $res->Fetch()) {
			self::addQueue('USER', $row['ID']);
		}
	}

	public static function installQueue ()
	{
		global $DB;

		$res = $DB->Query('CREATE UNIQUE INDEX entity_full ON `b_bitrix24_import` (UF_ENTITY_TYPE(8), UF_ENTITY_ID)');
		echo 'ok';
	}

	private static function getQueueInstance ()
	{
		CModule::IncludeModule('highloadblock');

		$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById(self::$HB_ID_QUEUE)->fetch();	
		$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
		return $entity->getDataClass();
	}

	private static function getRestApi ()
	{
		if (!class_exists('RestApi')) {
			include __DIR__ . '/restapi.php';
		}

		return new RestApi(self::$WEBHOOK);
	}

	private static function log($message)
	{
		$string = date('d.m.Y H-i-s: ') . $message . "\n";
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/local/events.log', $string, FILE_APPEND);
	}

	private static function getSelectList ($restapi)
	{
		$arSelect = array();
		$params = array(
			'filter' => array('USER_TYPE_ID' => 'enumeration')
		);
		$restapi->batchAdd('crm.deal.userfield.list', $params, function ($res) use (&$arSelect) {
			if (!empty($res['result'])) {
				foreach ($res['result'] as $item) {
					$item['_LIST'] = array();
					foreach ($item['LIST'] as $list) {
						$item['_LIST'][trim($list['VALUE'])] = $list['ID'];
					}
					$arSelect[$item['FIELD_NAME']] = $item;
				}
			}
		});

		$restapi->batchCall();

		return $arSelect;
	}

	private static function getPropProductsListValue ($restapi, $propID, $value, $_self = false)
	{
		static $arLists = array();

		if (count($arLists) == 0) {
			$restapi->batchAdd('crm.product.property.list', array('filter' => array('PROPERTY_TYPE' => 'L')), function($res) use (&$arLists) {
				if (!empty($res['result'])) {
					foreach ($res['result'] as $item) {
						$arLists[$item['ID']] = array(
							'LIST' => array(),
							'VALUES' => $item['VALUES'],
						);
						foreach ($item['VALUES'] as $value) {
							$arLists[$item['ID']]['LIST'][strtoupper(trim($value['VALUE']))] = $value['ID'];
						}
					}
				}
			});

			$restapi->batchCall();
		}

		$return = 0;

		if (isset($arLists[$propID])) {
			$code = strtoupper(trim($value));

			if (isset($arLists[$propID]['LIST'][$code])) {
				$return = $arLists[$propID]['LIST'][$code];
			} else {
				if (!$_self) {
					$arLists[$propID]['VALUES'][] = array('VALUE' => $value);

					$restapi->batchAdd('crm.product.property.update', array('id' => $propID, 'fields' => array('VALUES' => $arLists[$propID]['VALUES'])));
					$restapi->batchCall();
					$arLists = array();

					$return = self::getPropProductsListValue ($restapi, $propID, $value, true);
				}
			}
		}

		return $return;
	}

	public static function OnAfterUserAdd ($arFields, $fromself = false)
	{
		if (empty($arFields['ID'])) return 0;

		if (!$fromself) {
			self::log("Событие OnAfterUserAdd, ID = {$arFields['ID']}");
		}

		if (!defined('BITRIX24_QUEUE_GET') || !BITRIX24_QUEUE_GET) {
			self::addQueue('USER', $arFields['ID']);
			return true;
		}

		$restapi = self::getRestApi();

		if (false === ($arUser = CUser::GetList($by = 'ID', $order = 'ASC', array('ID' => $arFields['ID']))->Fetch())) {
			return 0;
		}

		CModule::IncludeModule('sale');

		$res = \Bitrix\Sale\Order::getList(array('select' => array('ID', 'USER_ID'), 'filter' => array('USER_ID' => $arUser['ID'])));
		$arUser['CRM_STATUS'] = ($res->fetch() ? 45 : 47);

		$arContact = false;

		$params = array(
			'filter' => array('UF_CRM_1576220649' => $arUser['ID']),
			'select' => array('ID', 'NAME', 'SECOND_NAME', 'LAST_NAME', 'PHONE', 'EMAIL', 'UF_CRM_1576220649', 'UF_CRM_1570098415010'),
		);
		$restapi->batchAdd('crm.contact.list', $params, function ($res) use (&$arContact) {
			if (!empty($res['result'])) {
				$arContact = $res['result'][0];
			}
		});

		$restapi->batchCall();

		$arUpdate = array();

		if ($arContact) {
			self::log("Найден контакт, ID = {$arContact['ID']}");

			if (empty($arContact['UF_CRM_1570098415010'])) {
				$arUpdate['UF_CRM_1570098415010'] = $arUser['CRM_STATUS'];
			}

			if (!empty($arContact['EMAIL']) && $arContact['EMAIL'][0]['VALUE'] != $arUser['EMAIL']) {
				$arContact['EMAIL'][0]['VALUE'] = $arUser['EMAIL'];
				$arUpdate['EMAIL'] = $arContact['EMAIL'];
			} elseif (!empty($arUser['EMAIL'])) {
				$arUpdate['EMAIL'][] = array('VALUE_TYPE' => 'WORK', 'VALUE' => $arUser['EMAIL']);
				$arUpdate['EMAIL'] = $arContact['EMAIL'];
			}

			if (!empty($arContact['PHONE']) && $arContact['PHONE'][0]['VALUE'] != $arUser['PERSONAL_PHONE']) {
				$arContact['PHONE'][0]['VALUE'] = $arUser['PERSONAL_PHONE'];
				$arUpdate['PHONE'] = $arContact['PHONE'];
			} elseif (!empty($arUser['PERSONAL_PHONE'])) {
				$arContact['PHONE'][] = array('VALUE_TYPE' => 'WORK', 'VALUE' => $arUser['PERSONAL_PHONE']);
				$arUpdate['PHONE'] = $arContact['PHONE'];
			}

			if ($arContact['NAME']        != $arUser['NAME'])        $arUpdate['NAME']        = $arUser['NAME'];
			if ($arContact['LAST_NAME']   != $arUser['LAST_NAME'])   $arUpdate['LAST_NAME']   = $arUser['LAST_NAME'];
			if ($arContact['SECOND_NAME'] != $arUser['SECOND_NAME']) $arUpdate['SECOND_NAME'] = $arUser['SECOND_NAME'];
		} else {
			$arUpdate = array(
				'NAME' => $arUser['NAME'],
				'LAST_NAME' => $arUser['LAST_NAME'],
				'SECOND_NAME' => $arUser['SECOND_NAME'],
				'EMAIL' => array(array('VALUE_TYPE' => 'WORK', 'VALUE' => $arUser['EMAIL'])),
				'PHONE' => array(array('VALUE_TYPE' => 'WORK', 'VALUE' => $arUser['PHONE'])),
				'UF_CRM_1576220649' => $arUser['ID'],
				'UF_CRM_1570098415010' => $arUser['CRM_STATUS'],
			);
		}

		if (count($arUpdate) > 0) {
			if ($arContact) {
				$params = array(
					'id' => $arContact['ID'],
					'fields' => $arUpdate,
				);
			} else {
				$params = array(
					'fields' => $arUpdate,
				);
			}

			$result = 0;
			$restapi->batchAdd('crm.contact.' . (isset($params['id']) ? 'update' : 'add'), $params, function ($res) use (&$result) {
				if (!empty($res['result'])) {
					$result = (int)$res['result'];
				}
			});
			$restapi->batchCall();

			if (!$arContact) {
				$arContact = array('ID' => $result);
			}

			self::log("Попытка сохранения данных, " . ($result > 0 ? 'удачная' : 'ОШИБКА'));
		}

		return ($arContact && !empty($arContact['ID'])) ? (int)$arContact['ID'] : 0;
	}

	public static function OnAfterUserUpdate ($arFields)
	{
		if ($arFields['RESULT']) {
			self::log("Событие OnAfterUserUpdate, ID = {$arFields['ID']}");
			return self::OnAfterUserAdd($arFields, true);
		}
	}

	public static function OnAfterIBlockElementAdd ($arFields, $fromself = false)
	{
		CModule::IncludeModule('iblock');
		CModule::IncludeModule('catalog');

		if (!is_array($arFields)) {
			$arFields = array('ID' => $arFields);
		}

		if (empty($arFields['ID'])) return 0;

		if (empty($arFields['IBLOCK_ID'])) {
			$arFields['IBLOCK_ID'] = CIBlockElement::GetIBlockByID($arFields['ID']);
		}

		if ($arFields['IBLOCK_ID'] != 20) return 0;

		if (!$fromself) {
			self::log("Событие OnAfterIBlockElementAdd, ID = {$arFields['ID']}");
		}

		if (!defined('BITRIX24_QUEUE_GET') || !BITRIX24_QUEUE_GET) {
			self::addQueue('PROODUCT_SCU', $arFields['ID']);
			return true;
		}

		$arUpdate = array();

		$arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_BARCODE', 'PROPERTY_SIZES', 'PROPERTY_COLOR_REF', 'PROPERTY_ARTICLE', 'PROPERTY_KOLLEKTSIYA');
		$arFilter = Array('IBLOCK_ID' => $arFields['IBLOCK_ID'], 'ID' => $arFields['ID']);
		$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
		if ($row = $res->Fetch()) {
			$price = CCatalogProduct::GetOptimalPrice($row['ID']);

			$arUpdate['NAME'] = $row['NAME'];
			$arUpdate['CURRENCY_ID'] = $price['PRICE']['CURRENCY'];
			$arUpdate['PRICE'] = $price['PRICE']['PRICE'];
			$arUpdate['PROPERTY_103'] = $row['ID'];
			$arUpdate['PROPERTY_105'] = $row['PROPERTY_BARCODE_VALUE'];
			$arUpdate['PROPERTY_107'] = trim($row['PROPERTY_SIZES_VALUE']);
			$arUpdate['PROPERTY_109'] = trim($row['PROPERTY_COLOR_REF_VALUE']);
			$arUpdate['PROPERTY_111'] = $row['PROPERTY_ARTICLE_VALUE'];
			$arUpdate['PROPERTY_113'] = $row['PROPERTY_KOLLEKTSIYA_VALUE'];

		} else {
			return 0;
		}

		$restapi = self::getRestApi();

		$arUpdate['PROPERTY_107'] = self::getPropProductsListValue($restapi, 107, $arUpdate['PROPERTY_107']);
		$arUpdate['PROPERTY_109'] = self::getPropProductsListValue($restapi, 109, $arUpdate['PROPERTY_109']);

		$arProduct = false;

		$params = array(
			'select' => array('*', 'PROPERTY_*'),
			'filter' => array(
				'PROPERTY_103' => $arFields['ID'],
			),
		);
		$restapi->batchAdd('crm.product.list', $params, function($res) use (&$arProduct) {
			if (!empty($res['result'])) {
				$arProduct = $res['result'][0];
			}
		});

		$restapi->batchCall();

		if ($arProduct) {
			$rewnewal = false;
			foreach ($arUpdate as $k => $v) {
				if (is_array($arProduct[$k])) {
					if ($v != $arProduct[$k]['value']) {
						$rewnewal = true;
						break;
					}
				} else {
					if ($v != $arProduct[$k]) {
						$rewnewal = true;
						break;
					}
				}
			}

			if ($rewnewal) {
				$restapi->batchAdd('crm.product.update', array('id' => $arProduct['ID'], 'fields' => $arUpdate), function($res) {
					self::log("Попытка сохранения данных товара #{$res['call']['params']['id']}, " . (!empty($res['result']) ? 'удачная' : 'ОШИБКА'));
				});
			}
		} else {
			$restapi->batchAdd('crm.product.add', array('fields' => $arUpdate), function($res) use (&$arProduct) {
				$arProduct = array('ID' => !empty($res['result']) ? (int)$res['result'] : 0);
				self::log("Попытка добавления товара, " . (!empty($res['result']) ? 'удачная' : 'ОШИБКА'));
			});
		}

		$restapi->batchCall();

		return (int)$arProduct['ID'];
	}

	public static function OnAfterIBlockElementUpdate ($arFields)
	{
		
		if ($arFields['RESULT']) {
			self::log("Событие OnAfterIBlockElementUpdate, ID = {$arFields['ID']}");
			
			return self::OnAfterIBlockElementAdd($arFields, true);
		} else {
			self::log("Событие OnAfterIBlockElementUpdate, {$arFields['RESULT_MESSAGE']}");
		}
	}

	public static function OnSaleOrderSaved ($event)
	{
		if ($event instanceof \Bitrix\Main\Event) {
			$order = $event->getParameter('ENTITY');
			$values = $event->getParameter('VALUES');
			$isNew = $event->getParameter('IS_NEW');
		} elseif ($event > 0) {
			$order = \Bitrix\Sale\Order::load((int)$event);
    		$values = array();
			$isNew = false;
		} else {
			return 0;
		}

		if (empty($order)) {
			return 0;
		}

		if ($isNew) {
			self::log("Событие OnSaleOrderSaved:Add, ID = " . $order->getField('ID'));
		} else {
			self::log("Событие OnSaleOrderSaved:Update, ID = " . $order->getField('ID'));
		}

		if (!defined('BITRIX24_QUEUE_GET') || !BITRIX24_QUEUE_GET) {
			self::addQueue('ORDER', $order->getField('ID'));
			return true;
		}

		$arProps = array();

		$propertyCollection = $order->getPropertyCollection();

		$arUpdate = array();

		$props = $propertyCollection->getArray();
		foreach ($props['properties'] as $prop) {
			$arProps[$prop['CODE']] = $prop['VALUE'];
		}

		$arUpdate['UF_CRM_1576133284406'] = '';
		if (!empty($arProps['LOCATION'])) {
			$arAddress = array();
			if ($arLocs = CSaleLocation::GetByID($arProps['LOCATION'][0], 'ru')) {
				if (!empty($arLocs['COUNTRY_NAME_LANG'])) $arAddress[] = $arLocs['COUNTRY_NAME_LANG'];
				if (!empty($arLocs['REGION_NAME_LANG'])) $arAddress[] = $arLocs['REGION_NAME_LANG'];
				if (!empty($arLocs['CITY_NAME_LANG'])) $arAddress[] = $arLocs['CITY_NAME_LANG'];
			}
			$arUpdate['UF_CRM_1576133284406'] = implode(', ', $arAddress);
		}

		$arUpdate['UF_CRM_1570715288'] = '';
		if (!empty($arProps['ADDRESS'])) {
			$arUpdate['UF_CRM_1570715288'] = $arProps['ADDRESS'][0];
		}

		$shipmentCollection = $order->getShipmentCollection();
		foreach ($shipmentCollection as $shipment) {
			if (!$shipment->isSystem()) {
				$crm_status = '';
				$status_id = $shipment->getField('STATUS_ID');

				    if ($status_id == 'DA') $crm_status = 'C1:PREPAYMENT_INVOICE';
				elseif ($status_id == 'DF') $crm_status = 'C1:WON';
				elseif ($status_id == 'DG') $crm_status = '';
				elseif ($status_id == 'DN') $crm_status = 'C1:PREPARATION';
				elseif ($status_id == 'DS') $crm_status = 'C1:1';
				elseif ($status_id == 'DT') $crm_status = 'C1:EXECUTING';

				if ($crm_status != '') {
					$arUpdate['STAGE_ID'] = $crm_status;
				}

				break;
			}
		}

		$arUpdate['STAGE_ID'] = 'C1:NEW';
		$arUpdate['CATEGORY_ID'] = 1;

		$arUpdate['UF_CRM_1576222825'] = '';
		$arUpdate['UF_CRM_1576222959'] = '';
		$row = $order->getDeliverySystemId();
		$row = \Bitrix\Sale\Delivery\Services\Manager::getById($row[0]);
		if ($row['PARENT_ID'] > 0) {
			$arUpdate['UF_CRM_1576222959'] = $row['NAME'];
			if ($row = \Bitrix\Sale\Delivery\Services\Manager::getById($row['PARENT_ID'])) {
				$arUpdate['UF_CRM_1576222825'] = $row['NAME'];
			}
		} else {
			$arUpdate['UF_CRM_1576222825'] = $row['NAME'];
		}

		$arUpdate['UF_CRM_1576222659'] = $order->getDeliveryPrice() . '|RUB';
		$arUpdate['UF_CRM_1570717645145'] =  $order->getField('TRACKING_NUMBER');
		$arUpdate['UF_CRM_1576223739'] =  $order->getField('USER_DESCRIPTION');
		$arUpdate['UF_CRM_5DC5226F71E14'] =  $order->getField('COMMENTS');
		$arUpdate['UF_CRM_1570719551233'] =  $order->getField('ID');
		$arUpdate['CATEGORY_ID'] =  1;
		$arUpdate['ORIGINATOR_ID'] =  1;
		$arUpdate['ORIGIN_ID'] =  $order->getField('ID');
		$arUpdate['CONTACT_ID'] = self::OnAfterUserAdd(array('ID' => $order->getUserId()), true);

		$arUpdate['UF_CRM_1576223247'] = '';
		$paymentCollection = $order->getPaymentCollection();
		foreach ($paymentCollection as $payment) {
			$arUpdate['UF_CRM_1576223247'] = $payment->getPaymentSystemName();
			break;
		}

		$arUpdate['UF_CRM_1574239281'] = array();
 		$discountData = $order->getDiscount()->getApplyResult();
		if (!empty($discountData['COUPON_LIST'])) {
			foreach ($discountData['COUPON_LIST'] as $row) {
				if ($row['APPLY'] == 'Y') {
					$arUpdate['UF_CRM_1574239281'][] = $row['DATA']['COUPON'];
				}
			}
		}
		$arUpdate['UF_CRM_1574239281'] = implode(', ', $arUpdate['UF_CRM_1574239281']);

		$restapi = self::getRestApi();

		$arSelect = self::getSelectList($restapi);

		$newSelectItems = array();
		foreach ($arSelect as $k => $item) {
			if (!empty($arUpdate[$k])) {
				if (isset($item['_LIST'][trim($arUpdate[$k])])) {
					$arUpdate[$k] = $item['_LIST'][trim($arUpdate[$k])];
				} else {
					$newSelectItems[] = $k;
					$item['LIST'][] = array('VALUE' => trim($arUpdate[$k]));
					$params = array(
						'id' => $item['ID'],
						'fields' => array('LIST' => $item['LIST'])
					);
					$restapi->batchAdd('crm.deal.userfield.update', $params, 'update' . $item['ID']);
				}
			}
		}

		if (count($newSelectItems) > 0) {
			$arSelect = self::getSelectList($restapi);
			foreach ($newSelectItems as $k) {
				$item = $arSelect[$k];
				if (isset($item['_LIST'][trim($arUpdate[$k])])) {
					$arUpdate[$k] = $item['_LIST'][trim($arUpdate[$k])];
				}
			}
		}

		$resUpdate = array();
		$dealID = 0;

		$params = array(
			'select' => array('*', 'UF_*'),
			'filter' => array(
				'UF_CRM_1570719551233' => $arUpdate['UF_CRM_1570719551233'],
			),
		);
		$restapi->batchAdd('crm.deal.list', $params, function($res) use ($arUpdate, &$resUpdate, &$dealID) {
			if (!empty($res['result'])) {
				foreach ($res['result'] as $item) {
					$dealID = $item['ID'];
					foreach ($arUpdate as $k => $v) {
						if ($item[$k] != $v) {
							if (isset($arUpdate['STAGE_ID'])) {
								unset ($arUpdate['STAGE_ID']);
							}
							if (isset($arUpdate['CATEGORY_ID'])) {
								unset ($arUpdate['CATEGORY_ID']);
							}

							$resUpdate[] = array(
								'op' => 'crm.deal.update',
								'params' => array(
									'id' => $item['ID'],
									'fields' => $arUpdate
								),
								'name' => function($res) {
									self::log("Попытка сохранения данных сделки #{$res['call']['params']['id']}, " . (!empty($res['result']) ? 'удачная' : 'ОШИБКА'));
								}
							);
							break;
						}
					}
				}
			} else {
				$resUpdate[] = array(
					'op' => 'crm.deal.add',
					'params' => array(
						'fields' => $arUpdate
					),
					'name' => function($res) use (&$dealID) {
						$dealID = !empty($res['result']) ? (int)$res['result'] : 0;

						self::log("Попытка создания сделки, " . (!empty($res['result']) ? 'удачная' : 'ОШИБКА'));
					}
				);
			}
		});
	
		$restapi->batchCall();

		if (count($resUpdate) > 0) {
			foreach ($resUpdate as $item) {
				$restapi->batchAdd($item['op'], $item['params'], $item['name']);
			}

			$restapi->batchCall();
		}

		if ($dealID > 0) {
			$arProducts = array();

			foreach ($order->getBasket() as $item) {
				$item = $item->getFields()->getValues();

				$product_id = self::OnAfterIBlockElementAdd(array('ID' => $item['PRODUCT_ID']), true);

				$arProducts[] = array(
					'PRODUCT_ID' => $product_id,
					'PRODUCT_NAME' => $item['NAME'],
					'QUANTITY' => $item['QUANTITY'],
					'PRICE' => $item['PRICE'],
					'DISCOUNT_TYPE_ID' => 1,
					'DISCOUNT_RATE' => $item['DISCOUNT_PRICE'],
					'DISCOUNT_SUM' => $item['DISCOUNT_PRICE'],
					'MEASURE_CODE' => $item['MEASURE_CODE'],
					'MEASURE_NAME' => $item['MEASURE_NAME'],
				);
			}

			if (count($arProducts) > 0) {
				$restapi->batchAdd('crm.deal.productrows.set', array('id' => $dealID, 'rows' => $arProducts), 'update_products_set_' . $dealID);
				$restapi->batchCall();
			}
		}
	}

	private static function addQueue ($type, $id)
	{
		/*
		$obj = self::getQueueInstance();

		$obj::add(array(
			'UF_ENTITY_TYPE' => $type,
			'UF_ENTITY_ID'   => (int)$id,
			'UF_DATE'        => \Bitrix\Main\Type\DateTime::createFromTimestamp(time()),
		));

		*/

		global $DB;
		$res = $DB->Query("INSERT IGNORE INTO `b_bitrix24_import` SET `UF_ENTITY_TYPE` = '" . $DB->ForSql($type) . "', `UF_ENTITY_ID` = " . (int)$id . ", `UF_DATE` = '" . date('Y-m-d H:i:s') . "'", true);

		if ($_SERVER['SERVER_NAME'] == 'cleanelly.dev-avtomatizatory.ru') {
			CCleanellyEvents::agentBitrix24Queue(1);
		}
	}

	public static function agentBitrix24Queue ($count = 1)
	{
		if (!defined('BITRIX24_QUEUE_GET')) {
			define('BITRIX24_QUEUE_GET', true);
		}

		$obj = self::getQueueInstance();

		$res = $obj::getList(array(
   			'order' => array('UF_DATE'=>'ASC'),
			'limit' => $count,
		));
		while ($row = $res->fetch()) {
			self::log("Отложенное инициирование события {$row['UF_ENTITY_TYPE']} [{$row['UF_ENTITY_ID']}]");

			if ($row['UF_ENTITY_TYPE'] == 'ORDER') {
				self::OnSaleOrderSaved((int)$row['UF_ENTITY_ID']);
			} elseif ($row['UF_ENTITY_TYPE'] == 'USER') {
				self::OnAfterUserUpdate(array('RESULT' => $row['UF_ENTITY_ID'], 'ID' => $row['UF_ENTITY_ID']));
			} elseif ($row['UF_ENTITY_TYPE'] == 'PROODUCT_SCU') {
				self::OnAfterIBlockElementUpdate(array('RESULT' => $row['UF_ENTITY_ID'], 'ID' => $row['UF_ENTITY_ID']));
			}

			$obj::delete($row['ID']);
		}

		return __METHOD__ . '(' . $count . ');';
	}
}

?>