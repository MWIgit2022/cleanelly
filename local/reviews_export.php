<?include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (ini_get('mbstring.func_overload') & 2) {
	$PHPEXCELPATH =  $_SERVER['DOCUMENT_ROOT']."/local/custom_libs/PHPExcel/Classes_overload2";
} else {
	$PHPEXCELPATH =  $_SERVER['DOCUMENT_ROOT']."/local/custom_libs/PHPExcel/Classes_overload0";
}
	
require_once($PHPEXCELPATH.'/PHPExcel.php');
$xls = new PHPExcel();
// Устанавливаем индекс активного листа
$xls->setActiveSheetIndex(0);
// Получаем активный лист
$sheet = $xls->getActiveSheet();
// Подписываем лист
$sheet->setTitle('Отзывы '.date('d.m.Y'));
			

CModule::IncludeModule("forum");
$db_res = CForumMessage::GetList(array("ID"=>"ASC"), array('APPROVED'=>'Y' ,'NEW_TOPIC'=>'N'));
while ($ar_res = $db_res->Fetch())
{
	//if(stristr(strtolower($ar_res['AUTHOR_NAME']),'admin')==false){
		
		$ar_res['POST_MESSAGE'] = preg_replace('#:f.*:#sUi', '', $ar_res['POST_MESSAGE']);
		$arResult['REVIEWS'][] = array('NAME'=>$ar_res['AUTHOR_NAME'], 'DATE'=>$ar_res['POST_DATE'], 'REVIEW'=>str_replace(array('[',']'),array('<','>'),strtolower($ar_res['POST_MESSAGE'])), 'PRODUCT'=>$ar_res['PARAM2']);
		

		if(!in_array($ids,$ar_res['PARAM2'])){
			$ids[] = $ar_res['PARAM2'];
		}
	//}
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

$sheet->getColumnDimension("A")->setWidth(30);
$sheet->getColumnDimension("B")->setWidth(50);
$sheet->getColumnDimension("C")->setWidth(100);
//$sheet->getColumnDimension("D")->setWidth(50);
$sheet->setCellValue("A1", 'Автор и дата');
$sheet->setCellValue("B1", 'Товар');
$sheet->setCellValue("C1", 'Отзыв');
	
foreach($arResult['REVIEWS'] as $k=>$review	){
	$sheet->setCellValue("A".($k+2), $review['NAME'].' '.$review['DATE']);
	$sheet->setCellValue("B".($k+2), $arResult['PRODUCTS'][$review['PRODUCT']]['NAME'].PHP_EOL.'https://'.$_SERVER['HTTP_HOST'].$arResult['PRODUCTS'][$review['PRODUCT']]['DETAIL_PAGE_URL']);
	$sheet->setCellValue("C".($k+2), strip_tags($review['REVIEW']));
	$sheet->getStyle("C".($k+2))->getAlignment()->setWrapText(true);
	//$sheet->setCellValue("D".($k+2), 'https://'.$_SERVER['HTTP_HOST'].$arResult['PRODUCTS'][$review['PRODUCT']]['DETAIL_PAGE_URL']);
	
	$sheet->getRowDimension($k+2)->setRowHeight(150);
}

$objWriter = new PHPExcel_Writer_Excel5($xls);
//$objWriter->save('php://output');
$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/upload/reviews/reviews_'.date('d.m.Y').'.xls');
$filename = $_SERVER['DOCUMENT_ROOT'].'/upload/reviews/reviews_'.date('d.m.Y').'.xls';

header('Content-disposition: attachment; filename='.basename($filename));
header('Content-Length: ' . filesize($filename));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

readfile($filename);
exit;