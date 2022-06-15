<?include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main;
	\Bitrix\Main\Loader::includeModule('catalog');
	\Bitrix\Main\Loader::includeModule('sale');
	\Bitrix\Main\Loader::includeModule('iblock');
	global $USER;
	$basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
	
	foreach ($basket as $basketItem){
		$basketPropertyCollection = $basketItem->getPropertyCollection();
			foreach ($basketPropertyCollection as $propertyItem) {
				if ($propertyItem->getField('CODE') == 'GIFT_NUMB') {
					$basket->getItemById($basketItem->getField('ID'))->delete();
					break;
				}
			}
	}
							
	$gift = $_POST['id'];
	$res = CIBlockElement::GetByID($gift);
	$ar_res = $res->GetNext();
				$item = $basket->createItem('catalog', $gift);
						$arPrice = CCatalogProduct::GetOptimalPrice($gift, 1, $USER->GetUserGroupArray());
						//$each_discount = $arPrice['DISCOUNT_PRICE']/count($basket);
					//	$item->markFieldCustom('PRICE');
						$item->setFields(array(
							'QUANTITY' => 1,
							'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
							'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
							'BASE_PRICE' => 1,
							'PRICE' => 1,
							'DISCOUNT_PRICE'=>0,
							'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
							'CUSTOM_PRICE' => 'Y',
							'NAME'=>$ar_res['NAME'],
							'DETAIL_PAGE_URL'=>$ar_res['DETAIL_PAGE_URL']
					   ));
						$item->save();
						$basketPropertyCollection = $item->getPropertyCollection();
						$basketPropertyCollection->setProperty(array(
							array(
							   'NAME' => 'Акция',
							   'CODE' => 'GIFT_NUMB',
							   'VALUE' => 'Подарок к покупке',
							   'SORT' => 10,
							),
						));
						
						$basketPropertyCollection->save();
						
$basket->save();