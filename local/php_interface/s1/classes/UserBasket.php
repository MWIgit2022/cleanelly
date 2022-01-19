<?php
use Bitrix\Main\Loader,
    Bitrix\Highloadblock as HL,
    Bitrix\Sale;

class UserBasket
{
    //название highload блока
    private static $highloadName = 'UserBasket';

    /**
     * Функция возвращает массив товаров и их количество из корзины пользователя
     * 
     * @return array - items - массив товаров и их количества; price - стоимость корзины
     */
    public static function getUserBasketItems() {
        //получение корзины текущего пользователя
        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
        $basketItem = $basket->getBasketItems();
        //получние идентификаторов товаров из корзины
        $items = [];
        foreach ($basketItem as $item) {
            $items[] = $item->getProductId() . ';' . $item->getQuantity();
        }
        return [
            'items' => $items,
            'price' => $basket->getPrice()
        ];
    }

    /**
     * Функция возвращает сущность highload блока
     * 
     * @return $entity_data_class - сущность highload блока
     */
    public static function getHighloadEntity() {
        Loader::includeModule("highloadblock"); 
        $entity = HL\HighloadBlockTable::compileEntity(self::$highloadName); 
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    /**
     * Метод возвращает массив с информацией пользователя
     * 
     * @param int $id - идентификатор пользователя
     * 
     * @return array - массив с информацией о пользователе
     */
    public static function getUserInfo($id) {
        $rsUser = CUser::GetByID($id);
        $arUser = $rsUser->Fetch();
        return $arUser;
    }

    /**
     * Функция обновляет запись о корзине зарегистрированного пользователя в highload блоке
     * 
     */
    public static function updateBasketHL() {
        global $USER;
        if ($USER->IsAuthorized()) {
            //идентификатор пользователя
            $arUser = self::getUSerInfo($USER->getId());
            //получение товаров в корзине
            $basket = self::getUserBasketItems();
            //получение сущности HL блока
            $entity_data_class = self::getHighloadEntity();
            // Массив полей для добавления/обновления
            $data = array(
                'UF_USER_ID' => $arUser['ID'],
                'UF_BASKET' => $basket['items'],
                'UF_DATETIME' => date('d.m.Y H:i:s'),
                'UF_PHONE' => $arUser['PERSONAL_PHONE'],
                'UF_EMAIL' => $arUser['EMAIL'],
                'UF_PRICE' => $basket['price']
            );
            //проверяем, есть ли запись для этого пользователя
            $rsData = $entity_data_class::getList(
                [
                "select" => ["*"],
                "order" => ["ID" => "ASC"],
                "filter" => ["UF_USER_ID" => $arUser['ID']]
                    // Задаем параметры фильтра выборки
                ]
            );

            if ($arData = $rsData->Fetch()){
                $entity_data_class::update($arData['ID'], $data); //обновление записи
            } else {
                $entity_data_class::add($data); // добавление записи
            }
        }        
    }      
}
?>