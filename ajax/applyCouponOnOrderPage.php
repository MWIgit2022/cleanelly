<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

    if(!\Bitrix\Main\Loader::includeModule("sale") || !\Bitrix\Main\Loader::includeModule("catalog") || !\Bitrix\Main\Loader::includeModule("iblock") || !\Bitrix\Main\Loader::includeModule("aspro.next"))
    {
        echo "failure";
        return;
    }

    if ($_REQUEST["AJAX"] == "Y" && $_REQUEST["data"]) {    
    
        $price = []; 
        $old_price = [];

        $basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());

        $discounts = \Bitrix\Sale\Discount::buildFromBasket($basket, new \Bitrix\Sale\Discount\Context\Fuser($basket->getFUserId(true)));
       
        \Bitrix\Sale\DiscountCouponsManager::add($_REQUEST["data"]);        

        //Для применения правил корзины
        $discounts->calculate();

        //Для применения скидок товаров
        $basket->refreshData(array('PRICE', 'COUPONS'));

        $result = $discounts->getApplyResult(true);
    
        foreach($result["PRICES"]["BASKET"] as $key => $value)
        {
            $old_price[$key] = intval($value["BASE_PRICE"]);
            $price[$key] = intval($value["PRICE"]);
        }
    
        print json_encode(array("price" => $price, "old_price" => $old_price));
    } else {
        return false;
    }
    
