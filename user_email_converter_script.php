<?
require $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);

global $USER;
if (!$USER->isAdmin()) {
    echo "Выполнение скрипта заблокировано";
} else {
    $elementCountSuccess = 0;
    $elementCount = 0;
    $user = new CUser;
    $emailSearch = '%user_';

    $filter = array("EMAIL" => $emailSearch);
    $rsUsers = CUser::GetList(($by = "NAME"), ($order = "desc"), $filter);
    while ($arUser = $rsUsers->Fetch()) {
        $emailParts = explode('@', $arUser['EMAIL']);
        $loginParts = explode('@', $arUser['LOGIN']);
        if (count($emailParts) == 2 && strpos($emailParts[1], '_') !== false) {
            echo 'Найден пользователь ' . $arUser['LOGIN'] . '<br>';

            $emailParts[1] = str_replace('_', '-', $emailParts[1]);
            $loginParts[1] = str_replace('_', '-', $loginParts[1]);
            $newEmail = $emailParts[0] . '@' . $emailParts[1];
            $newLogin = $loginParts[0] . '@' . $loginParts[1];

            if (!empty($newEmail) && !empty($newLogin)) {
                $fields = array(
                    "EMAIL" => $newEmail,
                    "LOGIN" => $newLogin,
                );
                $updateStatus = $user->Update($arUser['ID'], $fields);
                if ($updateStatus) {
                    echo 'Login & Email обновлены на ' . $fields['EMAIL'];
                    $elementCountSuccess = $elementCountSuccess + 1;
                } else {
                    echo 'Произошла ошибка при обновлении пользователя: ' . $user->LAST_ERROR;
                }
                echo '<br>';
            }

            $elementCount = $elementCount + 1;
            echo '<br>';
        }
        unset($emailParts, $loginParts, $newEmail, $newLogin);
    }

    echo "Успешно обновлено " . $elementCountSuccess . " пользователей из " . $elementCount;
}
