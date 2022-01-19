<?php

class Favorites
{
    //название cookie, в котором хранится строка с избранными товарами
    private $cookieName = 'FavoritesProductsList';

    //разделитель
    private $delimiter = ';';

    //массив с перечислением идентификаторов избранных товаров
    private $arFavoritesList = null;

    //массив с продуктами
    private $arFavoritesProducts = null;

    function __construct()
    {
        global $USER;
        $str = '';
        if ($USER->isAuthorized()) {
            //запрашиваем строку с избранными товарами из профиля
            $str = $this->getStrFromProfile($USER->getId());
        } else {
            //запрашиваем строку с избранными товарами из cookie
            $str = $this->getStrFromCookie();
        }
        //преобразуем строку в массив
        $this->arFavoritesList = $this->strToArray($str);
        //запрос из БД
        $this->arFavoritesProducts = $this->selectFavoritesProducts($this->arFavoritesList);
    }

    /**
     * Метод делит строку по делителю $this->delimiter и записывает в массив
     * 
     * @param mixed $str
     * 
     * @return [type]
     */
    public function strToArray($str)
    {
        $str =  trim($str, $this->delimiter);
        if (!empty($str)) {
            $arProducts = preg_split("/$this->delimiter/", $str);
            return $arProducts;
        } else {
            return [];
        }
    }

    /**
     * Метод преобразует массив в строку с разделителем $this->delimiter
     * 
     * @return string
     */
    public function arrayToStr()
    {
        $str = '';
        foreach ($this->arFavoritesProducts as $key => $favoriteId) {
            $str .= $key . $this->delimiter;
        }
        return trim($str, $this->delimiter);
    }

    /**
     * Метод добавляет/удаляет товар из списка избранных товаров
     * 
     * @param int $productId - идентификатор товара
     * 
     * @return true - товар добавлен в избранное, false - товар удален из избранного
     */
    private function updateFavoritesList($productId)
    {
        $productId = preg_split("/$this->delimiter/", $productId)[0];
        if (array_key_exists($productId, $this->arFavoritesProducts)) {
            unset($this->arFavoritesProducts[$productId]);
            return false;
        } else {
            $this->arFavoritesProducts[$productId] = $productId;
            return true;
        }
    }

    /**
     * Метод возвращает строку с избранными товарами из профиля авторизвованного пользователя
     * 
     * @return string|false
     */
    private function getStrFromProfile()
    {
        global $USER;
        $str = '';
        $rsUser = CUser::GetByID($USER->getId());
        $arUser = $rsUser->Fetch();
        $str = $arUser['UF_FAVORITES'];
        if (!empty($str)) {
            return $str;
        } else {
            return false;
        }
    }

    /**
     * Метод возвращает строку с избранными товарами из cookie
     * 
     * @return string - строка с избранными товарами
     */
    public function getStrFromCookie()
    {
        return htmlspecialchars($_COOKIE[$this->cookieName]);
    }

    /**
     * Метод обновляет строку с избранными товарами либо в профиле пользователя, если он авторизован, либо в cookie
     * 
     * @param mixed $productId - идентификатор товара
     * 
     * @return true - если товар добавлен, false - если товар удален
     */
    public function updateFavorites($productId)
    {
        global $USER;
        if ($USER->isAuthorized()) {
            $event = $this->updateFavoritesList($productId);
            $str = $this->arrayToStr();
            $user = new CUser;
            $fields = array(
                "UF_FAVORITES" => $str,
            );
            $user->Update($USER->getId(), $fields);
        } else {
            $event = $this->updateFavoritesList($productId);
            $str = $this->arrayToStr();
            setcookie($this->cookieName, $str, time() + 60 * 60 * 24 * 30, '/');
        }
        return $event;
    }

    /**
     * метод получает из БД список с информацией об избранных товарах
     * 
     * @return array
     */
    public function selectFavoritesProducts($arId)
    {
        if (!empty($this->arFavoritesList)) {
            $dbItems = CIBlockElement::GetList(
                ["SORT" => "ASC"],
                [
                    'IBLOCK_ID' => 17,
                    'ID' => $arId
                ],
            );

            while ($arItem = $dbItems->GetNext()) {
                $arItems[$arItem['ID']] = $arItem;
            }

            return $arItems;
        }
    }

    public function selectHits()
    {
        if (!empty($this->arFavoritesProducts)) {
            //запрос акций
            foreach ($this->arFavoritesProducts as $key => &$item) {
                $arProp = [];
                $resProp = CIBlockElement::GetProperty(
                    17,
                    $key,
                    [],
                    [
                        'CODE' => 'HIT',
                        'EMPTY' => 'N'
                    ]
                );
                while ($prop = $resProp->getNext()) {
                    $arProp[$prop['VALUE_XML_ID']] = $prop;
                }
                $item['HIT'] = $arProp;
            }
        }
    }

    /**
     * Метод запрашивает торговые предложения для избранных товаров
     * 
     */
    public function selectOffers()
    {
        if (!empty($this->arFavoritesProducts)) {
            //запрос торговых предложений
            $offers = CCatalogSKU::getOffersList(
                array_keys($this->arFavoritesProducts),
                17,
                [],
                [
                    '*',
                    'PROPERTY_SIZES'
                ]
            );
            foreach ($this->arFavoritesProducts as $key => &$item) {
                global $USER;
                $item['OFFERS'] = $offers[$key];
                $price = CCatalogProduct::GetOptimalPrice(
                    key($offers[$key]),
                    1,
                    $USER->GetUserGroupArray()
                );
                $item['PRICE']['BASE_PRICE'] = $price['RESULT_PRICE']['BASE_PRICE'];
                $item['PRICE']['DISCOUNT_PRICE'] = $price['RESULT_PRICE']['DISCOUNT_PRICE'];
                if ($price['RESULT_PRICE']['DISCOUNT_PRICE'] < $price['RESULT_PRICE']['BASE_PRICE']  ) {
                    $item['PRICE']['DISCOUNT'] = 'Y';
                } else {
                    $item['PRICE']['DISCOUNT'] = 'N';
                }
            }
        }
    }

    /**
     * Метод изменяет размер изображений для избранных товаров
     * 
     */
    public function resizeImages()
    {
        if (!empty($this->arFavoritesProducts)) {
            foreach ($this->arFavoritesProducts as &$item) {
                $item['RESIZE']['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
                    $item['PREVIEW_PICTURE'],
                    [
                        'width' => 133,
                        'height' => 133
                    ],
                    BX_RESIZE_IMAGE_PROPORTIONALDETAIL_PICTURE,
                );
                $item['RESIZE']['DETAIL_PICTURE'] = CFile::ResizeImageGet(
                    $item['DETAIL_PICTURE'],
                    [
                        'width' => 133,
                        'height' => 133
                    ],
                    BX_RESIZE_IMAGE_PROPORTIONALDETAIL_PICTURE,
                );
            }
        }
    }

    /**
     * Метод возвращает массив с идентификаторами избранных товаров
     * 
     * @return array
     */
    public function getFavoritesList()
    {
        return $this->arFavoritesList;
    }

    /**
     * Метод возвращает список с информацией об избранных товарах
     * 
     * @return array
     */
    public function getFavoritesProducts()
    {
        return $this->arFavoritesProducts;
    }

    /**
     * Метод возвращает количество избранных товаров
     * 
     * @return array
     */
    public function getCount()
    {
        return count($this->arFavoritesProducts);
    }
}
