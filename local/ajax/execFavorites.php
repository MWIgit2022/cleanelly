<?php

require_once $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php";

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$productId = $request->get('productId');

if (!empty($productId)) {
    $favorites = new Favorites();
    $event = $favorites->updateFavorites($productId);
    echo json_encode([
        'success' => true,
        'productId' => $productId,
        'add' => $event,
        'count' => $favorites->getCount()
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'empty productId'
    ]);
}
