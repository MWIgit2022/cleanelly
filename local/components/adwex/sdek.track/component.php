<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = [
    'TRACK_CODE' => '', 
    'TRACK_INFO' => [], 
    'SHOW_CALCULATE' => true, 
    'SHOW_HISTORY' => false, 
    'SHOW_FULL_HISTORY' => false, 
    'WARNING' => [], 
    'ERROR' => []
];

if (!Cmodule::includeModule('adwex.sdektrack')) {
    $arResult['ERROR']['MISS_CORE_MODULE'] = GetMessage('MISS_CORE_MODULE');
}

$ipolAuth = [];
if (Cmodule::includeModule('ipol.sdek')) {
    $ipolAuth = \sdekHelper::defineAuth();
}

$arParams['CDEK_ACCOUNT'] = trim($arParams['CDEK_ACCOUNT']);
if (empty($arParams['CDEK_ACCOUNT'])) {
    if (isset($ipolAuth['ACCOUNT'])) {
        $arParams['CDEK_ACCOUNT'] = $ipolAuth['ACCOUNT'];
    }
    else {
        $arResult['ERROR']['CDEK_ACCOUNT_REQURED'] = GetMessage('CDEK_ACCOUNT_REQURED');
    }
}
$arParams['CDEK_PASSWORD'] = trim($arParams['CDEK_PASSWORD']);
if (empty($arParams['CDEK_PASSWORD'])) {
    if (isset($ipolAuth['SECURE'])) {
        $arParams['CDEK_PASSWORD'] = $ipolAuth['SECURE'];
    }
    else {
        $arResult['ERROR']['CDEK_PASSWORD_REQURED'] = GetMessage('CDEK_PASSWORD_REQURED');
    }
}
if (!empty($arParams['SHOW_HISTORY']) && $arParams['SHOW_HISTORY'] === 'Y') {
    $arResult['SHOW_HISTORY'] = true;
}
if (!empty($arParams['SHOW_FULL_HISTORY']) && $arParams['SHOW_FULL_HISTORY'] === 'Y') {
    $arResult['SHOW_FULL_HISTORY'] = true;
}
if (empty($arParams['CALCULATE']) || $arParams['CALCULATE'] === 'N') {
    $arResult['SHOW_CALCULATE'] = false;
}

// $baseStatuses = [1, 13, 19, 21, 22, 8, 9, 11, 18];
$baseStatuses = [1, 13, 21, 22, 8, 9, 11, 18];

$requestOrder = $_REQUEST['actc'];
if(strlen($requestOrder)<10 && $requestOrder){
	$order = Bitrix\Sale\Order::load($requestOrder);
	$comment = $order->getField('COMMENTS');
	$requestOrder = trim(explode('/',$comment)[0]);
}
$requestOrderInt = preg_replace('~\D+~', '', $requestOrder);
$order = intval($requestOrderInt);
if (count($arResult['ERROR']) == 0 && $order > 0) {
    $arResult['TRACK_CODE'] = $order;
    $account = $arParams['CDEK_ACCOUNT'];
    $password = $arParams['CDEK_PASSWORD'];
    $obCache = new CPHPCache();
    if ($obCache->InitCache(10 * 60, $order . $account . $arResult['SHOW_HISTORY'], '/adwexsdektrack/' . $order)) {
        $vars = $obCache->GetVars();
        $arResult = $vars['arResult'];
    }
    elseif ($obCache->StartDataCache()) {
        $answer = \AdwexSdek::getStatus($account, $password, $order, $arResult['SHOW_HISTORY']);
        $httpCode = $answer['RESPONCE_CODE'];
        $urlResponse = $answer['RESPONCE_BODY'];
        if ($httpCode !== 200) {
            $arResult['ERROR']['CDEK_SHUTDOWN'] = GetMessage('CDEK_SHUTDOWN');
        }
        else {
            $urlResponse = \AdwexSdek::convertBeforeSend($urlResponse);
            $xmlAnswer = new SimpleXMLElement($urlResponse);
            if (isset($xmlAnswer->attributes()
                ->ErrorCode)) {
                $errorCode = \AdwexSdek::convertAfterSend($xmlAnswer->attributes()->ErrorCode->__toString());
                $errorMsg = \AdwexSdek::convertAfterSend($xmlAnswer->attributes()->Msg->__toString());
                $regDeletePersonal = '/(.*)(: Account=.*)/m';
                $errorMsgClear = preg_replace($regDeletePersonal, '$1', $errorMsg);
                $arResult['ERROR'][$errorCode] = $errorMsgClear;
            }
			else if (!isset($xmlAnswer->Order)) {
				$arResult['ERROR']['CDEK_NOORDERINFO'] = GetMessage('CDEK_NOORDERINFO');
			}
            else {
                $xmlOrder = $xmlAnswer->Order;
                $xmlOrderStatus = $xmlAnswer->Order->Status;
                foreach ($xmlOrder->attributes() as $key => $value) {
                    $valueString = \AdwexSdek::convertAfterSend($value->__toString());
                    if ($key === 'DeliveryDate') {
                        if (strlen($valueString) > 0) {
                            $arResult['TRACK_INFO']['ORDER'][$key] = \AdwexSdek::createDateArr($valueString);
                        }
                    }
                    else {
                        $arResult['TRACK_INFO']['ORDER'][$key] = $valueString;
                    }
                }
                if (isset($arResult['TRACK_INFO']['ORDER']['ErrorCode'])) {
                    $arResult['WARNING']['ERR_INVALID_DISPATCHNUMBER'] = $arResult['TRACK_INFO']['ORDER']['Msg'];
                }
                else {
                    foreach ($xmlOrderStatus->attributes() as $key => $value) {
                        $valueString = \AdwexSdek::convertAfterSend($value->__toString());
                        if ($key === 'Code') {
                            $arResult['TRACK_INFO']['STATUS'][$key] = [
                                'VALUE' => $valueString, 
                                'NAME' => GetMessage('CDEK_STATUS_' . $valueString . '_NAME'), 
                                'DESC' => GetMessage('CDEK_STATUS_' . $valueString . '_DESC') 
                            ];
                        }
                        elseif ($key === 'Date') {
                            $arResult['TRACK_INFO']['STATUS'][$key] = \AdwexSdek::createDateArr($valueString);
                        }
                        else {
                            $arResult['TRACK_INFO']['STATUS'][$key] = $valueString;
                        }
                    }
                    if ($arResult['SHOW_HISTORY']) {
                        $history = [];
                        foreach ($xmlOrderStatus->State as $state) {
                            if (in_array($state->attributes()->Code, $baseStatuses) || $arResult['SHOW_FULL_HISTORY']) {
                                $historyState = [];
                                foreach ($state->attributes() as $key => $value) {
                                    $valueString = \AdwexSdek::convertAfterSend($value->__toString());
                                    if ($key === 'Code') {
                                        $historyState[$key] = [
                                            'VALUE' => $valueString, 
                                            'NAME' => GetMessage('CDEK_STATUS_' . $valueString . '_NAME'), 
                                            'DESC' => GetMessage('CDEK_STATUS_' . $valueString . '_DESC')
                                        ];
                                    }
                                    elseif ($key === 'Date') {
                                        $historyState[$key] = \AdwexSdek::createDateArr($valueString);
                                    }
                                    else {
                                        $historyState[$key] = $valueString;
                                    }
                                }
                                $history[] = $historyState;
                            }
                        }
                        array_pop($history);
                        $arResult['TRACK_INFO']['HISTORY'] = array_reverse($history);
                    }
                }
            }
			$orderInfoResponse = \AdwexSdek::getInfo($account, $password, $order, $arResult['SHOW_HISTORY']);
			$orderInfo = \AdwexSdek::convertBeforeSend($orderInfoResponse['RESPONCE_BODY']);
            $xmlOrderInfo = new SimpleXMLElement($orderInfo);
            if (isset($xmlOrderInfo->attributes()
                ->ErrorCode)) {
                $errorCode = \AdwexSdek::convertAfterSend($xmlOrderInfo->attributes()->ErrorCode->__toString());
                $errorMsg = \AdwexSdek::convertAfterSend($xmlOrderInfo->attributes()->Msg->__toString());
				if ($errorCode === 'ERR_AUTH' && empty($errorMsg)) {
					$errorMsg = GetMessage('CDEK_ERR_AUTH');
				}
                $arResult['ERROR'][$errorCode] = $errorMsg;
            } elseif (isset($xmlOrderInfo->InfoRequest->attributes()->ErrorCode)) {
                $errorCode = \AdwexSdek::convertAfterSend($xmlOrderInfo->InfoRequest->attributes()->ErrorCode->__toString());
                $errorMsg = \AdwexSdek::convertAfterSend($xmlOrderInfo->InfoRequest->attributes()->Msg->__toString());
				if ($errorCode === 'ERR_AUTH' && empty($errorMsg)) {
					$errorMsg = GetMessage('CDEK_ERR_AUTH');
				}
				$arResult['ERROR'][$errorCode] = $errorMsg;
            } else {
                $xmlOrderFrom = $xmlOrderInfo->Order->SendCity;
                $xmlOrderTo = $xmlOrderInfo->Order->RecCity;
                foreach ($xmlOrderFrom->attributes() as $key => $value) {
                    $valueString = \AdwexSdek::convertAfterSend($value->__toString());
                    $arResult['TRACK_INFO']['ORDER']['FROM'][$key] = $valueString;
                }
                foreach ($xmlOrderTo->attributes() as $key => $value) {
                    $valueString = \AdwexSdek::convertAfterSend($value->__toString());
                    $arResult['TRACK_INFO']['ORDER']['TO'][$key] = $valueString;
                }
            }
            $arResult['CALCULATE'] = [];
            $orderDate = $xmlOrderInfo->Order->attributes()->Date;
            if ($arResult['SHOW_CALCULATE'] && !is_null($orderDate) && !empty($orderDate)) {
                $calculate = \AdwexSdek::calculate(
                    $account,
                    $password,
                    $xmlOrderInfo->Order->attributes()->Date->__toString(),
                    $arResult['TRACK_INFO']['ORDER']['FROM']['Code'], 
                    $arResult['TRACK_INFO']['ORDER']['TO']['Code'],
                    $xmlOrderInfo->Order->attributes()->TariffTypeCode,
                    $xmlOrderInfo->Order->Package->attributes()->Weight,
                    $xmlOrderInfo->Order->Package->attributes()->SizeA,
                    $xmlOrderInfo->Order->Package->attributes()->SizeB,
                    $xmlOrderInfo->Order->Package->attributes()->SizeC,
                    $xmlOrderInfo->Order->Package->attributes()->VolumeWeight
                );
                if (is_array($calculate) && isset($calculate['RESPONCE_BODY']['result'])) {
                    $arResult['CALCULATE'] = $calculate['RESPONCE_BODY']['result'];
                    if (!empty($arResult['CALCULATE']['deliveryDateMin'])) {
                        $arResult['CALCULATE']['deliveryDateMin'] = \AdwexSdek::createDateArrShort($arResult['CALCULATE']['deliveryDateMin']);
                    }
                    if (!empty($arResult['CALCULATE']['deliveryDateMax'])) {
                        $arResult['CALCULATE']['deliveryDateMax'] = \AdwexSdek::createDateArrShort($arResult['CALCULATE']['deliveryDateMax']);
                    }
                }
            }
        }
        $obCache->EndDataCache(['arResult' => $arResult]);
    }
}

$this->IncludeComponentTemplate();