<?php
include 'include/events/mail_events.php';

global $USER;

use Bitrix\Main;
use Bitrix\Sale;
use Bitrix\Main\EventManager;
use Bitrix\Sale\Internals;

//файл с константами
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/s1/consts.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/s1/consts.php');
}

//файл с константами

if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/s1/classes/HBUtils.php') && !in_array('HBUtils', get_declared_classes())) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/s1/classes/HBUtils.php');
}


//работа с брошенной корзиной пользователя
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/s1/classes/UserBasket.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/s1/classes/UserBasket.php');
}

//работа с избранными
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/s1/classes/Favorites.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/s1/classes/Favorites.php');
}

//работа с форумом
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/s1/classes/Events/ForumEvents.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/s1/classes/Events/ForumEvents.php');
}

//работа с заказои
if (file_exists($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/s1/classes/Events/OrderEvent.php')) {
    include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/s1/classes/Events/OrderEvent.php');
}

function getPropertyByCode($propertyCollection, $code)
    {
        foreach ($propertyCollection as $property)
        {
            if($property->getField("CODE") == $code)
                return $property;
        }
    }

AddEventHandler('sale', 'OnShipmentTrackingNumberChange', array('OrderTrackingNumberMessage', 'SendMail'));
AddEventHandler('form', 'onBeforeResultAdd', array('PromocodeFirstBuy', 'newPromocodeFirstBuy'));
//событие вызывается при добавлении, редактировании и удалении записей из корзины
AddEventHandler('sale', 'OnSaleBasketSaved', array('UserBasket', 'updateBasketHL'));
//событие вызывается при оформлении заказа
AddEventHandler('sale', 'OnSaleOrderSaved', array('UserBasket', 'updateBasketHL'));

$eventManager = EventManager::getInstance();
$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', array('OrderProccessing', 'CheckOrderOnCoupon'));
$eventManager->addEventHandler('main', 'OnAfterSetOption_secure_1c_exchange', array('OptionSetHandler', 'onAfterSetOption'));
$eventManager->addEventHandler('main', 'OnAfterSetOption_DEFAULT_SKIP_SOURCE_CHECK', array('OptionSetHandler', 'onAfterSetOption'));
$eventManager->addEventHandler('sale', 'OnBasketDelete', 'clearCoupons');
$eventManager->addEventHandler('sale', 'OnOrderSave', 'clearCoupons');
// добавление пользователя в группу "сделал первый заказ" 
$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', 'madeFirstOrder');
$eventManager->addEventHandler('sale', 'OnSaleOrderEntitySaved', 'addUserGroupSpasibo5orS10');
$eventManager->addEventHandler('forum', 'onAfterMessageAdd', array('ForumEvents', 'notifyNewItemFeedback'));
$eventManager->addEventHandler('sale', 'OnSaleOrderBeforeSaved', array('OrderEvent', 'setOrderBeforeSdekId'));

class OrderTrackingNumberMessage 
{
    static function SendMail($entity) {
		$arOrderVals = $entity->getFields()->getValues();

		$order = Sale\Order::load($arOrderVals["ORDER_ID"]);
		$propertyCollection = $order->getPropertyCollection();

		$property = getPropertyByCode($propertyCollection, "FIO");
		$arOrderVals["FIO"] = $property->getValue();

		$property = getPropertyByCode($propertyCollection, "EMAIL");
		$arOrderVals["EMAIL"] = $property->getValue();

		$arOrderVals["DATE"] = $order->getDateInsert();

		$arEventFields = array(
			"SALE_EMAIL" => "help@cleanelly.ru",
			"EMAIL" => $arOrderVals["EMAIL"],
            "ORDER_ID" => $arOrderVals["ORDER_ID"],
            "ORDER_USER" => $arOrderVals["FIO"],
            "ORDER_DATE" => $arOrderVals["DATE"],
			"ORDER_TRACKING_NUMBER" => $arOrderVals["TRACKING_NUMBER"],
		);
		
		CEvent::SendImmediate("SALE_ORDER_TRACKING_NUMBER", "s1", $arEventFields);
    }
}

class PromocodeFirstBuy
{
    static function newPromocodeFirstBuy($WEB_FORM_ID, &$arFields, &$arrVALUES) {
        global $APPLICATION;
        //проверяем, что именно форма с ID=9 передает результат
        if ($WEB_FORM_ID == 10) {
            $email = $arrVALUES['form_email_49']; 
            $arFilter["FIELDS"][] = [
                "CODE"              => "EMAIL",      // код поля по которому фильтруем
                "FILTER_TYPE"       => "text",
                "PARAMETER_NAME"    => "USER",  // фильтруем по параметру 
                "VALUE"             => $email, // значение по какому фильтруем
                "EXACT_MATCH"       => "Y"  
            ];
    
            $rsResults = CFormResult::GetList($WEB_FORM_ID, $by = "s_id", $order = "asc", $arFilter, $is_filtered, 'N', false);
    
            if ($rsResults->SelectedRowsCount() > 0) {
                while ($arResult = $rsResults->Fetch()) {
                    $OLD_RESULT_ID = $arResult["ID"];
                }
                $arOldAnswer = CFormResult::GetDataByID($OLD_RESULT_ID, [], $arResult, $arAnswer2);
                $arSend = [
                    "CLIENT" => $arOldAnswer["CLIENT"][0]["USER_TEXT"],
                    "PHONE" => $arOldAnswer["PHONE"][0]["USER_TEXT"],
                    "EMAIL" => $arOldAnswer["EMAIL"][0]["USER_TEXT"],
                    "PROMOCODE" => $arOldAnswer["PROMOCODE"][0]["USER_TEXT"],
                ];
                CEvent::Send("FORM_FILLING_NEW_CLIENT", SITE_ID, $arSend);
                setcookie("new-client-form", 1, time()+(60*60*24*30), "/");
                $APPLICATION->ThrowException("Извините, но ".$email." уже принимает участие в акции. На почту повторно выслан промокод");
            } else {
                $codeCoupon = CatalogGenerateCoupon();
                $couponFields = [
                    "DISCOUNT_ID" => 125, // ID правила скидок
                    "COUPON" => $codeCoupon,
                    "ACTIVE" => "Y",
                    "TYPE" => Internals\DiscountCouponTable::TYPE_ONE_ORDER,
                    "MAX_USE" => 1,
                    "DESCRIPTION"	=> $email
                ];
    
                // добавляем новый купон
                $addCouponRes = Internals\DiscountCouponTable::add($couponFields);
                if (!$addCouponRes->isSuccess()) {
                    $err = $addCouponRes->getErrorMessages();
                    file_put_contents(__DIR__ . '/testform.txt', date('Y-m-d H:i:s'). ' ' .print_r($err , 1). "! \n", FILE_APPEND);
                } else {
                    $arSend = [
                        "CLIENT" => $_REQUEST["form_text_47"],
                        "PHONE" => $_REQUEST["form_text_48"], 
                        "EMAIL" => $email,
                        "PROMOCODE" => $codeCoupon,
                    ];
                    $arrVALUES["form_hidden_50"] = $codeCoupon;
                    CEvent::Send("FORM_FILLING_NEW_CLIENT", SITE_ID, $arSend);
                    setcookie("new-client-form", 1, time()+(60*60*24*30), "/");
                }
            } 
        }
    }
}

class OrderProccessing {
	public static function CheckOrderOnCoupon(\Bitrix\Main\Event $event) {
        if ($event->getParameter("IS_NEW")) {
            $entity = $event->getParameter("ENTITY");
            $order = Sale\Order::load($entity->getId());
            $propertyCollection = $order->getPropertyCollection();
            $discountData = $order->getDiscount()->getApplyResult();
            foreach ($propertyCollection as $obProp) {
                $arProp = $obProp->getProperty();
                if ($arProp["CODE"] == "FIO") {
                    //Получаю ФИО пользователя
                    $fio = $obProp->getValue();
                }
            }
            //Получаю id пользователя
            $id = $order->getUserId();
            if (empty($fio))
                $fio = $id;
            foreach ($discountData['COUPON_LIST'] as $key => $coupon) {
                if ($coupon["COUPON"] == "Firstz078")
                {
                    if(CModule::IncludeModule("iblock") && !empty($id) && !empty($fio)) 
                    {
                        $el = new CIBlockElement;

                        $prop = array();
                        $prop["USER_ID"] = $id;  // свойству с кодом 12 присваиваем значение "Белый"

                        $arLoadProductArray = Array(
                        "MODIFIED_BY"    => $id, // элемент изменен текущим пользователем
                        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                        "IBLOCK_ID"      => IBLOCK_USERS_WITH_COUPONS,
                        "PROPERTY_VALUES"=> $prop,
                        "NAME"           => $fio,
                        "ACTIVE"         => "Y",
                        );
                        if($prodID = $el->Add($arLoadProductArray))
                            AddMessage2Log($prodID);
                        else
                            AddMessage2Log($el->LAST_ERROR);
                    }
                }
            }
        }
	}
}
	
class OptionSetHandler {
 
    private static $prefixNameEvent = 'OnAfterSetOption_';
 
    protected static $setOptions = array();
 
    /**
     * @param \Bitrix\Main\Event $e
     */
    public static function onAfterSetOption(\Bitrix\Main\Event $e)
    {
        $optionValues = array(
            'secure_1c_exchange' => array('sale', 'N'),
            'DEFAULT_SKIP_SOURCE_CHECK' => array('catalog', 'Y')
        );
 
        $optionName = str_replace(self::$prefixNameEvent, '', $e->getEventType());
 
        if(!isset(self::$setOptions[$optionName]) && isset($optionValues[$optionName])) {
            self::$setOptions[$optionName] = 1;
            \Bitrix\Main\Config\Option::set($optionValues[$optionName][0], $optionName, $optionValues[$optionName][1]);
        }
    }
 
}

// Очистка примененных купонов
function clearCoupons()
{
    \Bitrix\Sale\DiscountCouponsManager::clear(true);
}

/**
 * Обработчик события после сохранения заказа, добавление пользователя в группу "сделал первый заказ"
 *
 * @param \Main\Event $event
 * @return void
 */
function madeFirstOrder($event) {
    global $USER;
    $groups = CUser::GetUserGroup($USER->GetID()); // группы пользователя

    $rsGroups = CGroup::GetList ($by = 'c_sort', $order = 'asc', ['STRING_ID' => 'made_first_order']);
    $madeFirstOrderGroup = $rsGroups->Fetch(); // группа "сделал первый заказ" 

    if (!in_array($madeFirstOrderGroup['ID'], $groups)) {
        $groups[] = $madeFirstOrderGroup['ID'];
        CUser::SetUserGroup($USER->GetID(), $groups);
    }
}
 
/**
 * Обработчик события после сохранения заказа c промокодом spasibo5 или s10, добавление пользователя в группу "Промокод spasibo5" или "Промокод s10"
 * 
 * @param Bitrix\Main\Event $event
 * 
 * @return void
 */
function addUserGroupSpasibo5orS10(Bitrix\Main\Event $event) {
    global $USER;
    $couponName = ['spasibo5', 's10', 'blog15']; 
    $order = $event->getParameter("ENTITY");
    $discountData = $order->getDiscount()->getApplyResult();
    if (in_array(key($discountData['COUPON_LIST']), $couponName)) {
        $groups = CUser::GetUserGroup($USER->GetID()); // группы пользователя
        $rsGroups = CGroup::GetList ($by = 'c_sort', $order = 'asc', ['STRING_ID' => key($discountData['COUPON_LIST'])]);
        $madeFirstOrderGroup = $rsGroups->Fetch(); // группа "Промокод spasibo5" или "Промокод s10"

        if (!in_array($madeFirstOrderGroup['ID'], $groups)) {
            $groups[] = $madeFirstOrderGroup['ID'];
            CUser::SetUserGroup($USER->GetID(), $groups);
        }
    }
}
function automaticFacetedIndexCreation() {
    if(\Bitrix\Main\Loader::includeModule('iblock')){
    $iblock_id = 17;
    $iblockInfo = \Bitrix\Iblock\IblockTable::getList(array(
                        'select' => array('ID', 'PROPERTY_INDEX'),
                        'filter' => array('=ID' => $iblock_id)))->fetch();
        if($iblockInfo["PROPERTY_INDEX"] == "I"){
            Bitrix\Iblock\PropertyIndex\Manager::DeleteIndex($iblock_id);
            Bitrix\Iblock\PropertyIndex\Manager::markAsInvalid($iblock_id);
            $index = Bitrix\Iblock\PropertyIndex\Manager::createIndexer($iblock_id);
            $index->startIndex();
            $res = $index->continueIndex();
            $index->endIndex();
            \Bitrix\Iblock\PropertyIndex\Manager::checkAdminNotification();
            CBitrixComponent::clearComponentCache("bitrix:catalog.smart.filter");
            CIBlock::clearIblockTagCache($iblock_id);
        }
    }
  //return "automaticFacetedIndexCreation();";
}
