<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="subscribe-edit border_block">
<?
$arResult["MESSAGE"]['CONF'] = 'Спасибо, подписка подтверждена.';
if (!empty($arResult["MESSAGE"]['CONF']))
{
	if ($_REQUEST["action"] == 'unsubscribe')
	{
		$APPLICATION->SetTitle('Управление подпиской на новости компании Cleanelly!');
	}
	else
	{
		
		$APPLICATION->SetTitle('Поздравляем! Вы успешно подписаны на новости компании Cleanelly!');
	}
	/*?>
	<h3>Поздравляем! Вы успешно подписаны на новости компании Cleanelly</h3>
	<?*/
}
else
{
	foreach($arResult["MESSAGE"] as $itemID=>$itemValue)
		echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"OK"));
}



foreach($arResult["ERROR"] as $itemID=>$itemValue)
	echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"ERROR"));




//whether to show the forms
if($arResult["ID"] == 0 && empty($_REQUEST["action"]) || CSubscription::IsAuthorized($arResult["ID"]))
{
	//show confirmation form
	if($arResult["ID"]>0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y")
	{
		include("confirmation.php");
	}
	//show current authorization section
	if($USER->IsAuthorized() && ($arResult["ID"] == 0 || $arResult["SUBSCRIPTION"]["USER_ID"] == 0))
	{
		include("authorization.php");
	}
	//show authorization section for new subscription
	if($arResult["ID"]==0 && !$USER->IsAuthorized())
	{
		if($arResult["ALLOW_ANONYMOUS"]=="N" || ($arResult["ALLOW_ANONYMOUS"]=="Y" && $arResult["SHOW_AUTH_LINKS"]=="Y"))
		{
			include("authorization_new.php");
		}
	}
	//setting section
	include("setting.php");
	//status and unsubscription/activation section
	if($arResult["ID"]>0)
	{
		include("status.php");
	}
	?>
	<?
}
else
{
	//subscription authorization form
	include("authorization_full.php");
}
?>
</div>