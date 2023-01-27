<?
$url = $_SERVER['REQUEST_URI'];
$url = explode('?', $url);
$url = $url[0];
$is404 = true;

if (count(explode('/', $url)) > 3)  {
    foreach ($arResult['SECTIONS'] as $section) {
        if ($section['SECTION_PAGE_URL'] == $url)   {
            $is404 = false;
        }
    }

    if ($is404) {
        \Bitrix\Iblock\Component\Tools::process404(
            'Not found', //Сообщение
            true, // Нужно ли определять 404-ю константу
            true, // Устанавливать ли статус
            true, // Показывать ли 404-ю страницу
            false // Ссылка на отличную от стандартной 404-ю
        );
    }
}
