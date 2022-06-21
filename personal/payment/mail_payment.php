<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?use \Bitrix\Sale;
$APPLICATION->setTitle('Оплата заказа');?>
<style>
   .sberbank__wrapper{
	   margin: 0 auto;
		text-align: center;
		display: flex;
		flex-direction: column;
		justify-content: center;
		width: max-content;
   }
</style>
<?
CModule::IncludeModule("sale");
$ORDER_ID=intval($_GET["ORDER_ID"]);

$orderObj  = Sale\Order::load( $ORDER_ID );
$paymentCollection  =  $orderObj ->getPaymentCollection();
$payment  =  $paymentCollection [0];
$service  = Sale\PaySystem\Manager::getObjectById( $payment ->getPaymentSystemId());
$context  = \Bitrix\Main\Application::getInstance()->getContext();
$service ->initiatePay( $payment ,  $context ->getRequest()); 
 
$initResult = $service->initiatePay($payment, $context->getRequest(), \Bitrix\Sale\PaySystem\BaseServiceHandler::STRING);
$buffered_output = $initResult->getTemplate();
?>

<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");