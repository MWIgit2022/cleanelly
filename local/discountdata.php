<?
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//OnBasketUpdateHandler();
//phpinfo(); 
/* use \Bitrix\Main\Service\GeoIp;
echo $ipAddress = GeoIp\Manager::getRealIp();
			if($ipAddress){
				$dataResult = GeoIp\Manager::getDataResult($ipAddress, "ru");
				if($dataResult){
					$result = $dataResult->getGeoData();
				}
			}
echo '<pre>';
print_r($dataResult);
echo '</pre>';			
*/	


global $DB;
$yesterday = date('d.m.Y', strtotime('yesterday'));
$today = date('d.m.Y');


if($_GET['date_from'] && $_GET['date_to']){
	$yesterday = $_GET['date_from'].' 00:00:01';
	$today = $_GET['date_to'].' 23:59:59';
}

$arFilter = Array(
   ">=DATE_UPDATE" => date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), strtotime($yesterday)),
   "<DATE_UPDATE" => date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), strtotime($today)),
   );

$db_sales = CSaleOrder::GetList(array("DATE_UPDATE" => "ASC"), $arFilter);
while ($ar_sales = $db_sales->Fetch())
{
	$discount_arr = [];
	$order = Bitrix\Sale\Order::load($ar_sales["ID"]); 
	$basket = Bitrix\Sale\Basket::loadItemsForOrder($order);

	$paymentCollection = $order->getPaymentCollection();
	foreach($paymentCollection as $payment){
		$psID = $payment->getPaymentSystemId(); 
		if($psID == 3){
			$delivery_discount = 1;
		}	
	}
	$order_info[$order->getId()] = array(
		'DATE'=>$order->getField('DATE_UPDATE')->format("Y-m-d H:i:s"),
		'ID' => $order->getId(),
	);
	foreach ($basket as $basketItem){
		$basketPropertyCollection = $basketItem->getPropertyCollection();
		$props = $basketPropertyCollection->getPropertyValues();
		$xmls[$basketItem->getId()] = $props['PRODUCT.XML_ID']['VALUE'];
		if($props['SALE_NUMBER']){
			if($delivery_discount && $order->getPrice()>=5000){
				$props['SALE_NUMBER']['VALUE'].=' + скидка за оплату на сайте';
			} 
			$discount_arr[$xmls[$basketItem->getId()]]['DISCOUNTS']['акция '.$props['SALE_NUMBER']['VALUE']] = ($basketItem->getField('BASE_PRICE')-$basketItem->getPrice())*$basketItem->getField('QUANTITY');
		}
		if($props['GIFT_NUMB']){
			if($delivery_discount && $order->getPrice()>=5000){
				$props['SALE_NUMBER']['VALUE'].=' + скидка за оплату на сайте';
			}
			$discount_arr[$xmls[$basketItem->getId()]]['DISCOUNTS']['акция '.$props['SALE_NUMBER']['VALUE']] = ($basketItem->getField('BASE_PRICE')-$basketItem->getPrice())*$basketItem->getField('QUANTITY');
			
		}
		$discount_arr[$xmls[$basketItem->getId()]]['BASE_AMOUNT'] =  $basketItem->getField('BASE_PRICE')*$basketItem->getField('QUANTITY');
		$discount_arr[$xmls[$basketItem->getId()]]['QUANTITY'] = $basketItem->getField('QUANTITY');
	}
	$discountData = $order->getDiscount()->getApplyResult(true);
	foreach($discountData['DISCOUNT_LIST'] as $d){
		//if($d['REAL_DISCOUNT_ID'] != 122){
			$actions[$d['ID']] = $d['NAME'];
		//}
	}
	foreach($discountData['RESULT']['BASKET'] as $bid=>$prod){
		foreach($prod as $desc){
			if($actions[$desc['DISCOUNT_ID']]){
				$discount_arr[$xmls[$bid]]['DISCOUNTS'][$actions[$desc['DISCOUNT_ID']]] = str_replace(array(' ','руб.)'), array('',''),explode('(',$desc['DESCR'][0])[1])*$discount_arr[$xmls[$bid]]['QUANTITY'];
			}
		}
	}
	$order_info[$order->getId()]['PRODUCTS'] = $discount_arr;
	
}
 echo $json_order = json_encode($order_info, JSON_UNESCAPED_UNICODE);
?>
<!--script>
	console.log(JSON.parse('<?=$json_order?>'));
</script-->
<?
/* $arr = $order->getFieldValues();

			$paymentCollection = $order->getPaymentCollection();
			$DeliveryTrue = 0;
			foreach($paymentCollection as $payment){
			 $psID = $payment->getPaymentSystemId(); 
			 if($psID == 3){
				 $DeliveryTrue = 1;
			 }
			}
			$setCustom = 0;
			$discountData = $order->getDiscount()->getApplyResult();
			foreach($discountData['DISCOUNT_LIST'] as $d){
				if($d['REAL_DISCOUNT_ID'] == 122){
					$setCustom = 1;
				}
			}

			if($setCustom==0 && $DeliveryTrue==1 && $order->getPrice()>=5000){
				echo $new_price = $order->getPrice() - $order->getPrice()/100*5;
			} */

/* use Bitrix\Sale;
$order = Sale\Order::load(6064); 
$f = $order->getAvailableFields();
echo '<pre>';
print_r($f); */
/* $paymentCollection = $order->getPaymentCollection();
$DeliveryTrue = 0;
foreach($paymentCollection as $payment){
 $psID = $payment->getPaymentSystemId(); 
 if($psID == 3){
	 $DeliveryTrue = 1;
 }
}
$setCustom = 0;
$discountData = $order->getDiscount()->getApplyResult();
foreach($discountData['DISCOUNT_LIST'] as $d){
	if($d['REAL_DISCOUNT_ID'] == 122){
		$setCustom = 1;
	}
} 
 
if($setCustom==0 && $DeliveryTrue==1 && $order->getPrice()>=5000){
	$basket = Bitrix\Sale\Basket::loadItemsForOrder($order);
	foreach ($basket as $basketItem){
		$basketItem->markFieldCustom('PRICE');
		$basketItem->setFields(
			array(
			'DISCOUNT_PRICE'=>$each_discount_quan,
			'PRICE' => $basketItem->getField('PRICE')-$basketItem->getField('PRICE')/100*5,
			'CUSTOM_PRICE'=>'Y'
			)
		);
	} 
	
	$new_price = $order->getPrice() - $order->getPrice()/100*5;
	
	$basket->save();
	$order->setField('PRICE', $new_price);
	
	$order->save();
} */



