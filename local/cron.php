#!/usr/local/php/bin/php -q
<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/..');
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

$siteID = 's1';

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true); 
define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);
@ignore_user_abort(true);

CCleanellyEvents::agentBitrix24Queue(5);
?>
