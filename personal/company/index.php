<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Данные юр. лица");
global $USER;
$rsUser = CUser::GetByID($USER->GetID()); 
$arUser = $rsUser->Fetch();
$notes = explode(PHP_EOL,$arUser['WORK_NOTES']);
foreach($notes as $note){
	if($note){
		$exp_note = explode('--',$note);
		$arNotes[$exp_note[0]] = $exp_note[1];
	}
}

?>
<style>
.errors, .success{
	display: flex;
    flex-direction: column;
	background: #ffdede;
    padding: 1em;
	margin: 1em 0;
}
.errors span{
	color:red;
	font-weight:700;
}
.success{
	background:#deffe2;
}
.success span{
	color:green;
	font-weight:700;
}
</style>
<?if($arUser['WORK_NOTES'] && $arUser['WORK_NOTES']!=''){

	$fields_arr = array(
		'name_short_with_opf'=>'Сокращенное наименование юридического лица',
		'name_full_with_opf'=>'Полное наименование юридического лица*',
		'inn'=>'ИНН*',
		'kpp'=>'КПП*',
		'ogrn'=>($arUser['WORK_PROFILE']=='2') ? 'ОГРН' : 'ОГРНИП',
		'address_value'=>'Юридический адрес',
		'address_data_postal_code'=>'Почтовый индекс (Юридический адрес)',
		'address_data_city'=>'Город, населенный пункт (Юридический адрес)',
		'address_data_street_with_type'=>'Улица, проспект, переулок и т.д. (Юридический адрес)',
		'address_data_house'=> 'Дом, строение, корпус и т.д. (Юридический адрес)',
		'address_data_flat'=>'Квартира, офис и т.д. (Юридический адрес)',
		'fact_address_full'=>'Фактический адрес',
		'fact_address_postal_code'=>'Почтовый индекс (Фактический адрес)',
		'fact_address_city'=>'Город, населенный пункт (Фактический адрес)',
		'fact_address_street'=>'Улица, проспект, переулок и т.д. (Фактический адрес)',
		'fact_address_house'=> 'Дом, строение, корпус и т.д. (Фактический адрес)',
		'fact_address_flat'=>'Квартира, офис и т.д. (Фактический адрес)',
		'deliv_address_full'=>'Фактический адрес доставки товара',
		'deliv_address_postal_code'=>'Почтовый индекс (Адрес доставки)',
		'deliv_address_city'=>'Город, населенный пункт (Адрес доставки)',
		'deliv_address_street'=>'Улица, проспект, переулок и т.д. (Адрес доставки)',
		'deliv_address_house'=> 'Дом, строение, корпус и т.д. (Адрес доставки)',
		'deliv_address_flat'=>'Квартира, офис и т.д. (Адрес доставки)',
		'phones'=>'Телефоны',
		'emails'=>'E-mail*',
		'management_name' => 'ФИО контактного лица',
		'bank'=>'Наименование банка',
		'ks'=>'Корреспондентский счет',
		'bik'=>'БИК',
		'rs'=>'Расчетный счет'
	);
	if($arUser['WORK_PROFILE']=='3'){
		unset($fields_arr['name_short_with_opf']);
	}
?>
	<form method="POST" action="" class="legal_update">
		<?foreach($fields_arr as $fk=>$field){?>
			<div style="flex-basis:450px;flex-grow:1;max-width:100%">
				<p><b><?=$field?></b></p>
				<input type="text" class="form-group" name="<?=$fk?>" value="<?=$arNotes[$field]?>">
			</div>
		<?}?>
		<input type="submit" class="btn btn-default button is-primary button-default" value="Сохранить данные">
	</form>
<?} else {?>
	<a class="btn btn-default button is-primary button-default" href="/for_legal/registration/">Зарегистрируйтесь как юридическое лицо</a>
<?}?>
<script>
$('.legal_update').on('submit', function(e){
	e.preventDefault();
	$th = $(this);
	$.ajax({
			type: "POST",
			url: "/local/ajax/legal_update.php",
			data: $th.serialize(),
			success: function(html){
				 $('.errors').remove();
				$('.success').remove();
				if($(html).filter('.errors').length>0){
					$th.prepend($(html).filter('.errors')[0].outerHTML);
					 $('html, body').animate({
						scrollTop: $('.errors').offset().top-150
					}, 1000);
				} else if($(html).filter('.success').length>0){
					$th.append($(html).filter('.success')[0].outerHTML);
				} 
			}
		 })
});
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>