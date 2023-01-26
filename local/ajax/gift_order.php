<?include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem;

global $USER;

Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");

// Допустим некоторые поля приходит в запросе
$request = Context::getCurrent()->getRequest();
$productId = $request["PRODUCT_ID"];
$phone1 = $request["PHONE_1"];
$name1 = $request["NAME_1"];
$phone2 = $request["PHONE_2"];
$name2 = $request["NAME_2"];
$adress = $request["ADRESS"];
$email = $request["EMAIL"];
$delivery = $request["DELIVERY"];


$whatsapp = $request["WHATSAPP"] ? 'Да' : 'Нет';

$siteId = Context::getCurrent()->getSite();
$currencyCode = CurrencyManager::getBaseCurrency();

// Создаёт новый заказ
$order = Order::create($siteId, $USER->isAuthorized() ? $USER->GetID() : 17549);
$order->setPersonTypeId(1);
$order->setField('CURRENCY', $currencyCode);

$gift_order_description = 'Заказ на подарок:'.PHP_EOL;
$gift_order_description .= 'Получатель: '.$name2.' '.$phone2.PHP_EOL;
$gift_order_description .= 'Доставка: '.$delivery.PHP_EOL;
$gift_order_description .= 'Адрес доставки: '.$adress.PHP_EOL;
$gift_order_description .= 'Согласовать через Whatsapp: '.$whatsapp.PHP_EOL;

if ($gift_order_description) {
    $order->setField('USER_DESCRIPTION', $gift_order_description); // Устанавливаем поля комментария покупателя
}

// Создаём корзину с одним товаром
$basket = Basket::create($siteId);
$item = $basket->createItem('catalog', $productId);
$item->setFields(array(
    'QUANTITY' => 1,
    'CURRENCY' => $currencyCode,
    'LID' => $siteId,
    'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
));
$order->setBasket($basket);

$courier_delivery_id = 51;
$pvz_delivery_id = 52;

$delivery_id = $courier_delivery_id;
if($delivery == 'ПВЗ'){
	$delivery_id = $pvz_delivery_id;
}
$shipmentCollection = $order->getShipmentCollection();
$shipment = $shipmentCollection->createItem();
$service = Delivery\Services\Manager::getById($delivery_id);
$shipment->setFields(array(
    'DELIVERY_ID' => $service['ID'],
    'DELIVERY_NAME' => $service['NAME'],
));
$shipmentItemCollection = $shipment->getShipmentItemCollection();
$shipmentItem = $shipmentItemCollection->createItem($item);
$shipmentItem->setQuantity($item->getQuantity());


$paymentCollection = $order->getPaymentCollection();
$payment = $paymentCollection->createItem();
$paySystemService = PaySystem\Manager::getObjectById(1);
$payment->setFields(array(
    'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
    'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
));

// Устанавливаем свойства
$propertyCollection = $order->getPropertyCollection();
$phoneProp = $propertyCollection->getItemByOrderPropertyId(3);
//$phoneProp = $propertyCollection->getPhone();
$phoneProp->setValue($phone1);
//$nameProp = $propertyCollection->getPayerName();
$nameProp = $propertyCollection->getItemByOrderPropertyId(1);
$nameProp->setValue($name1);
$emailProp = $propertyCollection->getItemByOrderPropertyId(2);
$emailProp->setValue($email);

// Сохраняем
$order->doFinalAction(true);
$result = $order->save();
echo $orderId = $order->getId();
	
	