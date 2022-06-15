<?include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
$fields_arr = array(
	'name_short_with_opf'=>'Сокращенное наименование юридического лица',
	'name_full_with_opf'=>'Полное наименование юридического лица',
	'fio_name'=>'Полностью ФИО индивидуального предпринимателя',
	'inn'=>'ИНН',
	'kpp'=>'КПП',
	'ogrn'=>'ОГРН',
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
	'emails'=>'E-mail',
	'management_name' => 'ФИО контактного лица',
	'bank'=>'Наименование банка',
	'ks'=>'Корреспондентский счет',
	'bik'=>'БИК',
	'rs'=>'Расчетный счет'
);
if($_POST['PERSON_TYPE'] == 3){
	$fields_arr['ogrn'] = 'ОГРНИП';
	$fields_profile_props_ids = array(
		'name_full_with_opf'=>55,
		'inn'=>56,
		'kpp'=>57,
		'ogrn'=>58,
		'address_value'=>59,
		'fact_address_full'=>60,
		'deliv_address_full'=>61,
		'phones'=>62,
		'emails'=>63,
		'bank'=>64,
		'ks'=>65,
		'bik'=>66,
		'rs'=>67
	);
}
if($_POST['PERSON_TYPE'] == 2){
	$fields_profile_props_ids = array(
		'name_short_with_opf'=>38,
		'name_full_with_opf'=>39,
		'fio_name'=>40,
		'inn'=>41,
		'kpp'=>42,
		'ogrn'=>43,
		'address_value'=>44,
		'fact_address_full'=>45,
		'deliv_address_full'=>46,
		'phones'=>47,
		'emails'=>48,
		'management_name' =>49,
		'bank'=>50,
		'ks'=>51,
		'bik'=>52,
		'rs'=>53
	);
}

 foreach($fields_arr as $field=>$name){
	if($_POST[$field]){
		$notes .= $name.'--'.$_POST[$field].PHP_EOL;
	}
} 

if($_POST['name_full_with_opf'] ==''){
	$error='<span>Не заполнено поле - Полное наименование юридического лица</span>';
}
if($_POST['inn'] ==''){
	$error.='<span>Не заполнено поле - ИНН</span>';
}
if($_POST['emails'] ==''){
	$error.='<span>Не заполнено поле - E-mail</span>';
}
if($_POST['kpp'] ==''){
	$error.='<span>Не заполнено поле - КПП</span>';
}
if($_POST['login'] ==''){
	$error.='<span>Не заполнен логин</span>';
}
if($_POST['password'] ==''){
	$error.='<span>Не заполнен пароль</span>';
}
if($_POST['confirm_password'] !=$_POST['password']){
	$error.='<span>Не совпадают пароли</span>';
}

if($error){?>
	<div class="errors">
		<?=$error?>
	</div>
<?} else {
	$user = new CUser;
	$arFields = Array(
	  "WORK_COMPANY"      => $_POST['name_full_with_opf'],
	  "EMAIL"             => $_POST['emails'],
	  "LOGIN"             => $_POST['login'],
	  "ACTIVE"            => "Y",
	  "GROUP_ID"          => array(5),
	  "PASSWORD"          => $_POST['password'],
	  "CONFIRM_PASSWORD"  => $_POST['confirm_password'],
	  "WORK_NOTES"    	  => $notes,
	  "WORK_PROFILE"	  =>$_POST['PERSON_TYPE']
	);

	$ID = $user->Add($arFields);
	if (intval($ID) > 0){
		CModule::IncludeModule("sale");

		 $arProfileFields = array(
				 "NAME" => $_POST['name_full_with_opf'],
				 "USER_ID" => $ID,
				 "PERSON_TYPE_ID" => $_POST['PERSON_TYPE']
			  );
			  $PROFILE_ID = CSaleOrderUserProps::Add($arProfileFields);
			  
			  //если профиль создан
			  if ($PROFILE_ID)
			  {
				 //формируем массив свойств
				 foreach($fields_arr as $field=>$name){
					 if($_POST[$field] && $fields_profile_props_ids[$field]){
						 $PROPS[] =  array(
						   "USER_PROPS_ID" => $PROFILE_ID,
						   "ORDER_PROPS_ID" => $fields_profile_props_ids[$field],
						   "NAME" => $name,
						   "VALUE" => $_POST[$field],
						);
					 }
				 }
				 //добавляем значения свойств к созданному ранее профилю
				 foreach ($PROPS as $prop){
					CSaleOrderUserPropsValue::Add($prop);
				 }
				 
			  }
	  ?>
		<div class="success">
			<span><?echo "Пользователь успешно добавлен.";?></span>
		</div>
	<?} else {?>
		<div class="errors">
			<?echo $user->LAST_ERROR;?>
		</div>
	<?}
		
}
?>