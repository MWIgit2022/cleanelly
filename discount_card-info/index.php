<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<form method="POST" action="">
<p><b>Введите номер вашей дисконтной карты</b></p>
<input type="text" class="form-group" name="coupon" value="<?=$_POST['coupon']?>">
<input type="submit" class="btn btn-default button is-primary button-default" value="Получить информацию">
</form>
<?



if($_POST['coupon']){
	$couponIterator =  \Bitrix\Sale\Internals\DiscountCouponTable::getList(array('select' => array('DESCRIPTION', 'DISCOUNT_ID'), 'filter' => array('ACTIVE'=>'Y','COUPON' => $_POST['coupon']), 'group' => array('DISCOUNT_ID')));
     while ($coupon = $couponIterator->fetch()) {
		 $discountIterator = \Bitrix\Sale\Internals\DiscountTable::getList(array('select' => array('*'), 'filter' => array('ACTIVE'=>'Y','ID' => $coupon['DISCOUNT_ID']), 'group' => array('ID')));
		 while ($discount = $discountIterator->fetch()) {
			$disc_arr['NAME'] = $discount['NAME'];
		 }
		 $disc_arr['BALANCE'] = $coupon['DESCRIPTION'];
	 }
}
if(intval($disc_arr['BALANCE'])>0){
	$arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_FROM", "PROPERTY_TO", "PROPERTY_NEXT_DISCOUNT");
	$arFilter = Array("IBLOCK_ID"=>34, "ACTIVE"=>"Y", ">PROPERTY_FROM"=>$disc_arr['BALANCE']);
	$res = CIBlockElement::GetList(Array('PROPERTY_FROM'=>'ASC'), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement())
	{
	 $arFields = $ob->GetFields();
	 $from_to[] = array('FROM'=>$arFields['PROPERTY_FROM_VALUE'], 'TO'=>$arFields['PROPERTY_TO_VALUE'], "NEXT_D"=>$arFields['PROPERTY_NEXT_DISCOUNT_VALUE']);
	}
	$balance =  $disc_arr['BALANCE'];
}

if($disc_arr){?>
	<p style="margin-top:2em;"><?=$disc_arr['NAME']?></p>
	<?if($balance){?>
		<p>Текущий баланс по карте: <?=$balance?> руб.</p>
		<?if($from_to){?>
			<p>До перехода на следующий уровень скидки осталось: <?=current($from_to)['TO']+1 - $balance?> руб.</p>
		<?} else {?>
			<p>Максимально возможный процент скидки.</p>
		<?}?>
	<?}?>
<?}else {?>
	<p style="margin-top:2em;">Дисконтная карта не найдена</p>
<?}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>