<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация юр. лица");
use Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();

global $USER;
if($USER->getID()){
	$rsUser = CUser::GetByID($USER->GetID()); 
	$arUser = $rsUser->Fetch();
}

if($arUser['WORK_PROFILE'] !=2 && $arUser['WORK_PROFILE'] !=3){?>

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
<form method="POST" action="">
	<div class="form-group">
		<label class="bx-soa-custom-label">Тип плательщика</label><br>
		<div class="radio-inline checked">
			<label>
				<input <?if($request->getPost('PERSON_TYPE')=='2' || !$request->getPost('PERSON_TYPE')){?>checked="true"<?}?> type="radio" name="PERSON_TYPE" value="2">Юр. лицо
			</label>
		</div>
		<br>
		<div class="radio-inline">
			<label>
				<input <?if($request->getPost('PERSON_TYPE')=='3'){?>checked="true"<?}?> type="radio" name="PERSON_TYPE" value="3">ИП
			</label>
		</div>
	</div>
	<p><b>Введите ИНН</b></p>
	<input type="text" class="form-group" name="inn" value="<?=$request->getPost('inn')?>">
	<input type="submit" class="btn btn-default button is-primary button-default" value="Получить информацию">
</form>


<?
if($request->getPost('PERSON_TYPE') == 3){
	$fields_arr_merge = array(
		'Полностью ФИО индивидуального предпринимателя'=>'name.full_with_opf',
	);
} elseif($request->getPost('PERSON_TYPE') == 2 || !$request->getPost('PERSON_TYPE')) {
	$fields_arr_merge = array(
		'Сокращенное наименование юридического лица'=>'name.short_with_opf',
		'Полное наименование юридического лица'=>'name.full_with_opf',
	);
}

$required_fields = array('inn','kpp','login','password','confirm_password','name.full_with_opf', 'emails');
$fields_arrz = array(
	
	'ИНН'=>'inn',
	'КПП'=>'kpp',
	'ОГРН'=>'ogrn',
	'Юридический адрес'=>array(
		'Полный адрес'=>'address.value',
		'Почтовый индекс'=>'address.data.postal_code',
		'Город, населенный пункт'=>'address.data.city',
		'Улица, проспект, переулок и т.д.'=>'address.data.street_with_type',
		'Дом, строение, корпус и т.д.'=>'address.data.house',
		'Квартира, офис и т.д.'=>'address.data.flat',
		),
	'Фактический адрес'=>array(
		'Полный адрес'=>'fact_address_full',
		'почтовый индекс'=>'fact_adress_postal_code',
		'город, населенный пункт'=>'fact_adress_city',
		'улица, проспект, переулок и т.д.'=>'fact_adress_street',
		'дом, строение, корпус и т.д.'=>'fact_adress_house',
		'квартира, офис и т.д.'=> 'fact_adress_flat',
	),
	'Фактический адрес доставки товара'=>array(
		'Полный адрес'=>'deliv_address_full',
		'почтовый индекс'=>'deliv_address_postal_code',
		'город, населенный пункт'=>'deliv_address_city',
		'улица, проспект, переулок и т.д.'=>'deliv_address_street',
		'дом, строение, корпус и т.д.'=>'deliv_address_house',
		'квартира, офис и т.д.'=> 'deliv_address_flat',
	),
	'Контакты'=>array(
		'Телефон'=>'phones',
		'E-mail'=>'emails',
		'ФИО контактного лица'=>'management.name',
	),
	'Банковские реквизиты'=>array(
		'Наименование банка'=>'bank',
		'Корреспондентский счет'=>'ks',
		'БИК'=>'bik',
		'Расчетный счет'=>'rs',
	)
);

$fields_arr = array_merge($fields_arr_merge,$fields_arrz);


?>
<p style="font-size:1.5em;margin:1em 0"><b>Данные Юр.Лица</b></p>
<form method="POST" action="" class="legal_register">
	<?foreach($fields_arr as $fk=>$field){?>
		<?if(is_array($field)){?>
			<p style="font-size:1.5em;margin-bottom:1em;"><b><?=$fk?></b></p>
			<div style="display:flex;flex-wrap:wrap;gap:1em;">
			<?foreach($field as $k=>$v){?>
				<div style="flex-basis:450px;flex-grow:1;max-width:100%">
					<p><b><?=$k?></b><?if(in_array($v,$required_fields)){?>*<?}?></p>
					<input type="text" class="form-group" name="<?=$v?>" value="" <?if(in_array($v,$required_fields)){?>required<?}?>>
				</div>
			<?}?>
			</div>
		<?} else {?>
		
			<p><b><?=$fk?></b> <?if(in_array($field,$required_fields)){?>*<?}?></p>
			<input type="text" class="form-group" name="<?=$field?>" value="" <?if(in_array($field,$required_fields)){?>required<?}?>>
		<?}
	}?>
	<p style="font-size:1.5em;margin-bottom:1em;"><b>Данные для входа</b></p>
			<div style="display:flex;flex-wrap:wrap;gap:1em;">
				<div style="flex-basis:100%;max-width:100%">
					<p><b>Логин</b>*</p>
					<input type="text" class="form-group" name="login" value="" required>
				</div>
				<div style="flex-basis:450px;flex-grow:1;max-width:100%">
					<p><b>Пароль</b>*</p>
					<input type="password" class="form-group" name="password" value="" required>
				</div>
				<div style="flex-basis:450px;flex-grow:1;max-width:100%">
					<p><b>Повторите пароль</b>*</p>
					<input type="password" class="form-group" name="confirm_password" value="" required>
				</div>
			</div>
			<input type="hidden" name="PERSON_TYPE" value="<?=$request->getPost('PERSON_TYPE')?>">
	<input type="submit" class="btn btn-default button is-primary button-default" value="Зарегистрироваться">
</form>

<?if($request->getPost('inn')){?>
	<script>
	var url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party";
	var token = "16fe0b41c40f655a6a03e030476915eb229411d6";
	var query = "<?=$request->getPost('inn')?>";

	var options = {
		method: "POST",
		mode: "cors",
		headers: {
			"Content-Type": "application/json",
			"Accept": "application/json",
			"Authorization": "Token " + token
		},
		body: JSON.stringify({query: query})
	}

	fetch(url, options)
	.then(response => response.text())
	.then(result => getDataDa(JSON.parse(result).suggestions[0].data))
	.catch(error => console.log("error", error));

	function getDataDa(data){
		<?foreach($fields_arr as $field){
			if(is_array($field)){
				foreach($field as $v){?>
					if(data.<?=explode('.',$v)[0]?>){
						$('input[name="<?=$v?>"]').val(data.<?=$v?>);
					}
				<?}
			} else {?>
				if(data.<?=explode('.',$field)[0]?>){
						$('input[name="<?=$field?>"]').val(data.<?=$field?>);
					}
			<?}
		}?>
		
	}
</script>
<?}?>
<script>
$('.legal_register').on('submit', function(e){
	e.preventDefault();
	$th = $(this);
	$.ajax({
			type: "POST",
			url: "/local/ajax/legal_register.php",
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
					 $('html, body').animate({
						scrollTop: $('.success').offset().top-150
					}, 500);
				}
			}
		 })
});
</script>
<?} else {?>
	<p>Вы авторизованы как юридическое лицо</p>
<?}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>