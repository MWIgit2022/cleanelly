<?
require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
CModule::IncludeModule('sale');

global $USER;
if (!$USER->isAdmin()) {
    echo "Выполнение скрипта заблокировано";
} else {
    $elementCountSuccess = 0;
    $elementCount = 0;
    $orders = [];

    $dbRes = \Bitrix\Sale\Order::getList([
        'select' => [
            "ID", 
            "PROPERTY_VAL.VALUE",
        ],
        'filter' => [
            '=PROPERTY_VAL.CODE' => 'EMAIL',
            '%PROPERTY_VAL.VALUE' => 'www_cleanelly.ru',
        ],
        'runtime' => [
            new \Bitrix\Main\Entity\ReferenceField(
                'PROPERTY_VAL',
                '\Bitrix\sale\Internals\OrderPropsValueTable',
                ["=this.ID" => "ref.ORDER_ID"],
                ["join_type"=>"left"]
            ),
        ]
    ]);

    while($orderArr = $dbRes->fetch())
    {
        $elementCount++;

        $order = \Bitrix\Sale\Order::load($orderArr["ID"]);
        $str = explode("@", $orderArr["SALE_INTERNALS_ORDER_PROPERTY_VAL_VALUE"]);

        $propertyCollection = $order->getPropertyCollection();
        $emailPropValue = $propertyCollection->getUserEmail();
        $emailPropValue->setValue($str[0]."@www-cleanelly.ru");
        $result = $order->save();

        if ($result->isSuccess())
        {
            $orders[] = $orderArr["ID"];
            $elementCountSuccess++;
        }
    }

    echo "Успешно обновлено " . $elementCountSuccess . " заказ(ов) из " . $elementCount. "<br>";

    echo "Список обновленных заказов: <br>";
    foreach ($orders as $orderID) {
        echo "Заказ №". $orderID ." обновлен.";
        echo "<br>";
    }
}
