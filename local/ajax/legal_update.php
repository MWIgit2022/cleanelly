<?include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
global $USER;
$rsUser = CUser::GetByID($USER->GetID()); 
$arUser = $rsUser->Fetch();

$fields_arr = array(
	'name_short_with_opf'=>'Сокращенное наименование юридического лица',
	'name_full_with_opf'=>'Полное наименование юридического лица',
	'fio_name'=>'Полностью ФИО индивидуального предпринимателя',
	'inn'=>'ИНН',
	'kpp'=>'КПП',
	'ogrn'=>'ОГРН',
	'address_value'=>'Юридический адрес (адрес места нахождения) юридического лица, ИП',
	'address_data_postal_code'=>'Почтовый индекс (Юридический адрес)',
	'address_data_city'=>'Город, населенный пункт (Юридический адрес)',
	'address_data_street_with_type'=>'Улица, проспект, переулок и т.д. (Юридический адрес)',
	'address_data_house'=> 'Дом, строение, корпус и т.д. (Юридический адрес)',
	'address_data_flat'=>'Квартира, офис и т.д. (Юридический адрес)',
	'fact_address_full'=>'Фактический адрес юридического лица, ИП',
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

 foreach($fields_arr as $field=>$name){
	if($_POST[$field]){
		$notes .= $name.'--'.$_POST[$field].PHP_EOL;
	}
} 

$db_sales = CSaleOrderUserProps::GetList(
        array("DATE_UPDATE" => "DESC"),
        array("NAME" => $arUser['WORK_COMPANY'])
    );

if ($ar_sales = $db_sales->Fetch())
{
  $profile = $ar_sales['ID'];
}

$inn_consists=0;
$filter = array('?WORK_NOTES'=>'ИНН--'.$inn);
$order = array('sort' => 'asc');
$tmp = 'sort';
$rsUsers = CUser::GetList($order, $tmp, $filter);
while($arUser = $rsUsers->fetch()){
	$inn_consists++;
}

if($inn_consists>1){
	$error='<span>ИНН уже есть в базе</span>';
}
if($_POST['name_full_with_opf'] ==''){
	$error.='<span>Не заполнено поле - Полное наименование юридического лица</span>';
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

if($error){?>
	<div class="errors">
		<?=$error?>
	</div>
<?} else {
	$user = new CUser;
	$arFields = Array(
	  "WORK_COMPANY"      => $_POST['name_full_with_opf'],
	  "EMAIL"             => $_POST['emails'],
	  "WORK_NOTES"    	  => $notes
	);

	$user->Update($USER->getID(), $arFields);
	$strError .= $user->LAST_ERROR;
	if ($strError == false || $strError ==''){
		CModule::IncludeModule("sale");
		$db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$profile));
		while ($arPropVals = $db_propVals->Fetch())
		{
			$pr[$arPropVals['ORDER_PROPS_ID']] = $arPropVals;
		}

		CSaleOrderUserProps::Update($profile, array('NAME'=>$_POST['name_full_with_opf']));

		foreach($pr as $code=>$vals){
			$arField = array();
		   $arField = array(
					 "USER_PROPS_ID" => $vals["USER_PROPS_ID"],
					 "ORDER_PROPS_ID" => $vals["ORDER_PROPS_ID"],
					 "NAME" => $vals["NAME"],
					 "VALUE" => $_POST[array_search($code,$fields_profile_props_ids)]
				);
		
			CSaleOrderUserPropsValue::Update($vals["ID"] , $arField);
		}

		
	  ?>
		<div class="success">
			<span><?echo "Профиль обновлён";?></span>
		</div>
	<?} else {?>
		<div class="errors">
			<?echo $strError?>
		</div>
	<?}
		
}
?>