<?
if(
	$_SERVER['REMOTE_ADDR'] != '213.27.12.29'
&&	$_SERVER['REMOTE_ADDR'] != '185.180.40.121'
&&	$_SERVER['REMOTE_ADDR'] != '5.167.52.86'
&&	$_SERVER['REMOTE_ADDR'] != '37.9.3.122'
) {
	echo $_SERVER['REMOTE_ADDR'];
	echo ' wrong ip';
	exit();
}

include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//header('Content-Type: application/json');
use Bitrix\Sale;
global $DB;
$d = file_get_contents("php://input");
$data = json_decode($d);

$order = Sale\Order::load($data->Order_ID);
if($order == false){
	echo 'Заказ не существует';
	exit();
}

$marked = false;

$collection = $order->getShipmentCollection();
foreach ($collection as $shipment)
{
		
		if($shipment->getField('STATUS_ID') == 'DF'){
			echo 'Заказ уже передан в доставку';
			exit();
		}
		
		$shipmentItemCollection = $shipment->getShipmentItemCollection();
		foreach($shipmentItemCollection as $item)
     {
		 
		//print_r($item->getBasketId());
		//print_r($item->getQuantity());
		 $shipmentItemStoreCollection = $item->getShipmentItemStoreCollection();
		 foreach ($shipmentItemStoreCollection as $shipmentItemStore) {
			 $marked = true;
            // $basketId = $shipmentItemStore->getField('BASKET_ID');
            // $barcodeList[$basketId][$storeId][] = array('ID' => $item->getProductId(), 'QUANTITY' => $shipmentItemStore->getQuantity(), 'BARCODE' => $shipmentItemStore->getMarkingCode()); 
				$res = CIBlockElement::GetByID($item->getProductId());	
				$product = $res->GetNext();
				 $product_xml = $product['XML_ID'];
				 foreach($data->data as $data_item){
					if($data_item->XML_ID == $product_xml){
						$shipmentItemStore->setField('MARKING_CODE', current($data_item->MarkingCodes));
						array_shift($data_item->MarkingCodes);
					}
				}
				$order->save(); 
		 }
		 
		
		
		if($marked == false){	// метод с прямым запросом конечно так себе, но ничего другого не придумалось	 
			for ($i = 0; $i < $item->getQuantity(); $i++) {
				$DB->Query("INSERT INTO `b_sale_store_barcode` (BASKET_ID, QUANTITY, ORDER_DELIVERY_BASKET_ID) VALUES ('".$item->getBasketId()."', '1', '".$item->getId()."')");
			}
		} 
         
	 }
}

if($marked == false){	
	$order2 = Sale\Order::load($data->Order_ID);
	$collection = $order2->getShipmentCollection();
	foreach ($collection as $shipment)
	{
		if($shipment->getField('STATUS_ID') == 'DF'){
			echo 'Заказ уже передан в доставку';
			exit();
		}
		
			$shipmentItemCollection = $shipment->getShipmentItemCollection();
			foreach($shipmentItemCollection as $item)
		 {
			 $shipmentItemStoreCollection = $item->getShipmentItemStoreCollection();
			 foreach ($shipmentItemStoreCollection as $shipmentItemStore) { 
					$marked = true;
					$res = CIBlockElement::GetByID($item->getProductId());	
					$product = $res->GetNext();
					 $product_xml = $product['XML_ID'];
					 foreach($data->data as $data_item){
						if($data_item->XML_ID == $product_xml){
							$shipmentItemStore->setField('MARKING_CODE', current($data_item->MarkingCodes));
							array_shift($data_item->MarkingCodes);
						}
					}
					$order2->save(); 
			 }
			 
		 }
	}
}  
//if($marked==true){
	$order3 = Sale\Order::load($data->Order_ID);
	$shipments = $order3->getShipmentCollection();

	foreach ($shipments as $shipment)
	{
		   if(!$shipment->isSystem())
		   {
				   $shipment->setField('STATUS_ID', "DF");
				   $shipment->setField('DEDUCTED', "Y");
				   $shipment->setField('ALLOW_DELIVERY', "Y");
				   $shipment->setField('TRACKING_NUMBER', $data->TRACKING_NUMBER);
		   }
	}
	$order3->save();
	echo 'ok';
//} else {
//	echo 'Сайт не произвел запись по неизвестной ошибке, обратитесь к разработчикам сайта';
//}