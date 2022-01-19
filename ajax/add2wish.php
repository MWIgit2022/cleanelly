<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?> 
  
<?if (CModule::IncludeModule("sale")) { 
    $fUserID = CSaleBasket::GetBasketUserID(True); 
    $fUserID = IntVal($fUserID); 
   $arFields = array(  
      "PRODUCT_ID" => (int)$_POST['p_id'],  
      "PRODUCT_PRICE_ID" => (int)$_POST['pp_id'],  
      "PRICE" => htmlspecialchars($_POST['p']),  
      "CURRENCY" => "RUB",  
      "WEIGHT" => 0,  
      "QUANTITY" => 1,  
      "LID" => 's1',  
      "DELAY" => "Y",  
      "CAN_BUY" => "Y",  
      "NAME" => htmlspecialchars($_POST['name']), 
       "MODULE" => "sale", 
       "NOTES" => "",  
      "DETAIL_PAGE_URL" => htmlspecialchars($_POST['dpu']),  
      "FUSER_ID" => $fUserID     
   );     
   if (CSaleBasket::Add($arFields)) { 
      $arBasketItems = array();
      $dbBasketItems = CSaleBasket::GetList(
         array(
               "NAME" => "ASC",
               "ID" => "ASC"
            ),
         array(
               "FUSER_ID" => CSaleBasket::GetBasketUserID(),
               "LID" => SITE_ID,
               "ORDER_ID" => "NULL",
               "DELAY" => "Y",
            ),
         false,
         false,
         array("PRODUCT_ID")
      );
      
      while ($arItems = $dbBasketItems->Fetch()){
         $arBasketItems[] = $arItems["PRODUCT_ID"];
      }
      echo count($arBasketItems);
   }
}?>  
 
<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>