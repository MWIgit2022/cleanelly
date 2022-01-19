<?php
use Bitrix\Main;

\Bitrix\Main\Loader::includeModule('sale');

Main\EventManager::getInstance()->addEventHandler(
    'main',
    'OnBeforeEventAdd',
    'OnBeforeEventAddHandler'
);
function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
{
    if ($arFields['ORDER_ID']) {
        $order = \Bitrix\Sale\Order::load($arFields['ORDER_ID']);
        if ($order && ($order->getPrice() - $order->getDeliveryPrice()) < 3500) {
            $arFields['DELIVERY_PRICE'] = $order->getDeliveryPrice() . " руб.";
        } elseif ($order) {
            $arFields['DELIVERY_PRICE'] = "бесплатно";
        }
    }
}

