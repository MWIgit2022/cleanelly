<?php

use Bitrix\Main;
use Bitrix\Sale;

/**
 * Класс для работы с событиями заказа
 */
class OrderEvent
{
    /**
     * Изменяет город СДЭК, по местоположению
     * 
     * @param Main\Event $event
     * 
     */
    public static function setOrderBeforeSdekId(Bitrix\Main\Event $event) {
        // получим объект заказа
        $order = $event->getParameter("ENTITY");
        //Получим коллекцию свойств заказа
        $propertyCollection = $order->getPropertyCollection();
        $locPropValue = $propertyCollection->getDeliveryLocation();
        if ($locPropValue) {
            $locationCode = $locPropValue->getValue();
            $res = \Bitrix\Sale\Location\LocationTable::getList(array(
                'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID, 'CODE' => $locationCode),
                'select' => array('*', 'NAME_RU' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE')
            ));
            if ($item = $res->fetch())
            {
                if($item['CITY_ID']) {
                    $arSdek = \CDeliverySDEK::getCity($item['CITY_ID'], true);
                }
            }
        }
        if ($arSdek["SDEK_ID"]) {
            /** @var \Bitrix\Sale\PropertyValue $obProp */
            foreach ($propertyCollection as $obProp) {
                $arProp = $obProp->getProperty();
                if (!in_array($arProp["CODE"], ["CITY_CDEK"])) {
                    continue;
                }
                if (!($obProp->getValue() === $arSdek["SDEK_ID"])) {
                    $obProp->setValue($arSdek["SDEK_ID"]);
                } 
            }
        }
    }
}