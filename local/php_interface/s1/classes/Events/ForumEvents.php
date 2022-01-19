<?php

/**
 * Класс для работы с событиями форума
 */
class ForumEvents
{    
    public const IBLOCK_CATALOG_ID = 17;
    public const MAIL_EVENT_ID = 62;
    public const TYPE_MAIL_EVENT_ID = 'NEW_ITEM_REVIEW';

    /**
     * Отправка уведомления о новом отзыве на почту
     * 
     * @param integer $id
     * @param array  $message
     * @param array  $topicInfo
     * @param array  $forumInfo
     * @param array  $arFields
     * 
     */
    public static function notifyNewItemFeedback($id, $message, $topicInfo, $forumInfo, $arFields) {
        if (!isset($arFields["PARAM2"]) || empty($arFields["PARAM2"])) {
            return;
        }
        $rsElement = CIBlockElement::GetList(
            [],
            ['IBLOCK_ID' => self::IBLOCK_CATALOG_ID, 'ID' => $arFields["PARAM2"]],
            false,
            false,
            ['IBLOCK_ID', 'ID', 'NAME', 'DETAIL_PAGE_URL']
        );
        if ($arElement = $rsElement->GetNext()) {
            $arMail = [
                'ITEM_NAME' => $arElement['NAME'],
                'AUTHOR_NAME' => $arFields['AUTHOR_NAME'],
                'POST_DATE' => date('d.m.Y H:i:s'),
                'POST_MESSAGE' => $arFields['POST_MESSAGE'],
                'PATH2ITEM' => $arElement['DETAIL_PAGE_URL'],
            ];
            CEvent::Send(self::TYPE_MAIL_EVENT_ID, "s1", $arMail, self::MAIL_EVENT_ID);
        }
    }
}