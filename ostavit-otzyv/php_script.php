<?
CModule::IncludeModule("forum");
$db_res = CForumMessage::GetList(array("ID"=>"ASC"), array('APPROVED'=>'Y'));
while ($ar_res = $db_res->Fetch())
{
	if(stristr(strtolower($ar_res['AUTHOR_NAME']),'admin')==false && $ar_res['AUTHOR_NAME'] != 'Обмен 1С 1С'){
		
		$ar_res['POST_MESSAGE'] = preg_replace('#:f.*:#sUi', '', $ar_res['POST_MESSAGE']);
		$arResult['REVIEWS'][] = array('NAME'=>$ar_res['AUTHOR_NAME'], 'DATE'=>$ar_res['POST_DATE'], 'REVIEW'=>str_replace(array('[',']'),array('<','>'),strtolower($ar_res['POST_MESSAGE'])), 'PRODUCT'=>$ar_res['PARAM2']);
		
		if(!in_array($ids,$ar_res['PARAM2'])){
			$ids[] = $ar_res['PARAM2'];
		}
	}
}

$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", 'ACTIVE');
$arFilter = Array("IBLOCK_ID"=>17, 'ID'=>$ids);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement())
{
 $arFields = $ob->GetFields();
 $file = CFile::ResizeImageGet($arFields['PREVIEW_PICTURE'], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true); 
 $arFields['PREVIEW_PICTURE'] = $file['src'];
 $arResult['PRODUCTS'][$arFields['ID']] = $arFields;
}

function paginator($data,$countOnPage = 10){
    // Получаем номер текущей страницы из реквеста
    $page = (intval($_GET['PAGEN_1'])) ? intval($_GET['PAGEN_1']) : 1;
    // Отбираем элементы текущей страницы
    $dataSlice = array_slice($data, (($page-1) * $countOnPage), $countOnPage,true);
    // Подготовка параметров для пагинатора
    $navResult = new CDBResult();
    $navResult->NavPageCount = ceil(count($data) / $countOnPage);
    $navResult->NavPageNomer = $page;
    $navResult->NavNum = 1;
    $navResult->NavPageSize = $countOnPage;
    $navResult->NavRecordCount = count($data);
    return array(
        'ITEMS'=>$dataSlice,
        'PAGINATION'=>$navResult->GetPageNavStringEx($navComponentObject, 'Страница', '', 'Y'),
    );
}