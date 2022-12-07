<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
if(CModule::IncludeModule('iblock'))
{
    $dbEl = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>20));
    if($obEl = $dbEl->GetNextElement())
    {   
        $props = $obEl->GetProperties();
        foreach($props as $k=>$v){
			$props_arr_vals[$k] = $v['NAME'];
		}
		
    }
}
$arTemplateParameters = array(	
	'SHOW_PROPS' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 100,
		'NAME' =>'Доп. свойства на детальной странце заказа',
		'TYPE' => 'LIST',
		"VALUES"    =>  $props_arr_vals,
		'MULTIPLE' => 'Y',
	),
);
?>