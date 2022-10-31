<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */

if ($arParams["SET_TITLE"] == "Y")
{
	$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
}
?>

<? if (!empty($arResult["ORDER"])): ?>
	<script>fbq('track', 'Lead');</script>
	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=Loc::getMessage("SOA_ORDER_SUC", array(
					"#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"]->format('d.m.Y H:i'),
					"#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]
				))?>
				<? if (!empty($arResult['ORDER']["PAYMENT_ID"])): ?>
					<?=Loc::getMessage("SOA_PAYMENT_SUC", array(
						"#PAYMENT_ID#" => $arResult['PAYMENT'][$arResult['ORDER']["PAYMENT_ID"]]['ACCOUNT_NUMBER']
					))?>
				<? endif ?>
				<br /><br />
				<?=Loc::getMessage("SOA_ORDER_SUC1", array("#LINK#" => $arParams["PATH_TO_PERSONAL"]))?>
			</td>
		</tr>
	</table>

	<?
	if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y')
	{
		if (!empty($arResult["PAYMENT"]))
		{
			foreach ($arResult["PAYMENT"] as $payment)
			{
				if ($payment["PAID"] != 'Y')
				{
					if (!empty($arResult['PAY_SYSTEM_LIST'])
						&& array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
					)
					{
						$arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];

						if (empty($arPaySystem["ERROR"]))
						{
							?>
							<br /><br />

							<table class="sale_order_full_table">
								<tr>
									<td class="ps_logo">
										<div class="pay_name"><?=Loc::getMessage("SOA_PAY") ?></div>
										<?=CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100, "border=0\" style=\"width:100px\"", "", false) ?>
										<div class="paysystem_name"><?=$arPaySystem["NAME"] ?></div>
										<br/>
									</td>
								</tr>
								<tr>
									<td>
										<? if (strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
											<?
											$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
											$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
											?>
											<script>
												window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
											</script>
										<?=Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber))?>
										<? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']): ?>
										<br/>
											<?=Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&pdf=1&DOWNLOAD=Y"))?>
										<? endif ?>
										<? else: ?>
											<?=$arPaySystem["BUFFERED_OUTPUT"]?>
										<? endif ?>
									</td>
								</tr>
							</table>

							<?
						}
						else
						{
							?>
							<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
							<?
						}
					}
					else
					{
						?>
						<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
						<?
					}
				}
			}
		}
	}
	else
	{
		?>
		<br /><strong><?=$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']?></strong>
		<?
	}
	?>

<? else: ?>

	<b><?=Loc::getMessage("SOA_ERROR_ORDER")?></b>
	<br /><br />

	<table class="sale_order_full_table">
		<tr>
			<td>
				<?=Loc::getMessage("SOA_ERROR_ORDER_LOST", array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?>
				<?=Loc::getMessage("SOA_ERROR_ORDER_LOST1")?>
			</td>
		</tr>
	</table>

<? endif ?>

<?
$order = Bitrix\Sale\Order::load($arResult['ORDER']['ID']);
$propertyCollection = $order->getPropertyCollection();
$ar = $propertyCollection->getArray();
foreach($ar['properties'] as $prop){
	$props_arr[$prop['CODE']] = $prop;
}
$user_id = $order->getUserId();
$rsUser = CUser::GetByID($user_id); 
$arUser = $rsUser->Fetch();
$statuses_n_active = array(20,19,23);
if($arUser && $props_arr['DISCOUNT_CARD']['VALUE'][0]== 'Y'){?>
	<div style="display: none; width: 500px;" id="hidden">
		<?if(in_array($arUser['UF_DISCOUNT_CARD_STATUS'],$statuses_n_active) || !$arUser['UF_DISCOUNT_CARD_STATUS']){?>
			<h2>Код из СМС</h2>
			<p>
				Вы хотели получить дисконтную карту, для её активации введите код из смс. 
			</p>
			<p>
				Смс отправлено на номер <?=$arUser['PERSONAL_PHONE']?>
			</p>
			<input type="text" name="sms_cd" placeholder="Введите код...">
			<a class="resend disable" href="javascript:void(0)" onclick="reSend(this)">Отправть повторно</a>
			<button onclick="checkCode(this)" style="margin:1em 0" type="button" class="btn btn-default aprove">Подтвердить</button>
			<div class="rez"></div>
		<?} else {?>
			<h2>Карта уже присвоена</h2>
			<p>
				Вы хотели получить дисконтную карту 
			</p>
			<p>
				За пользователем уже закреплена дисконтная карта <?=$arUser['UF_DISCOUNT_CARD_ID']?>
			</p>
		<?}?>
	</div>
	
<script>
$(window).load(function(){
	$.fancybox.open($('#hidden').html());	
	$('.fancybox-inner input[name="sms_cd"]').focus();
	setTimeout(function(){
		$('.resend.disable').removeClass('disable');
	},60000);
})
function checkCode(th){
	 $.ajax({
            type: "POST",
            url: '/local/dc/check_code.php',
            data: 'code='+$(th).parent().find('input').val()+'&phone=<?=$arUser['PERSONAL_PHONE']?>',
            success: function (data) {
				$(th).parent().find('.rez').html(data);
			}
	 })
}
function reSend(th){
	 $.ajax({
            type: "POST",
            url: '/local/dc/resend_code.php',
            data: 'phone=<?=$arUser['PERSONAL_PHONE']?>',
            success: function (data) {
				$(th).parent().find('.rez').html(data);
			}
	 })
}
</script>
<?}