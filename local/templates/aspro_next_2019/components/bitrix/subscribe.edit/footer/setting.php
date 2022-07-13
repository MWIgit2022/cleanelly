<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<form action="<?=SITE_DIR.$arParams["PAGE"]?>" method="post" class="subscribe-form" onsubmit="ym(22769200,'reachGoal','Subscribe');">
	<?echo bitrix_sessid_post();?>
	<input type="text" name="EMAIL" class="form-control subscribe-input required" placeholder="<?=GetMessage("EMAIL_INPUT");?>" value="<?=$arResult["USER_EMAIL"] ? $arResult["USER_EMAIL"] : ($arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"]);?>" size="30" maxlength="255" />

	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
		<input type="hidden" name="RUB_ID[]" value="<?=$itemValue["ID"]?>" />
	<?endforeach;?>

	<input type="hidden" name="FORMAT" value="html" /> 
	<div class="g-recaptcha" id="recaptcha" data-sitekey="6LdpsfYUAAAAAMtSpbg1JWUuuJoF9Cr4NXtfl0g9"> </div>
	<input type="submit" name="Save" class="btn btn-default btn-lg subscribe-btn" value="<?echo GetMessage("ADD_USER");?>" />
	<input type="hidden" name="recaptcha" value="Y" /> 
	<input type="hidden" name="checked" value="" /> 
	<input type="hidden" name="PostAction" value="Add" />
	<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
</form>
<script defer src="https://www.google.com/recaptcha/api.js?onload=reCaptchaOnLoadCallback&render=explicit"></script>

