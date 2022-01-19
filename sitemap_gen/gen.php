<?
$filename = $_SERVER['DOCUMENT_ROOT'].'/sitemap.xml';
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php" ) ;
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/xml.php');
header('Content-Type: text/html; charset=utf-8');
$xml = new CDataXML();
$xml->Load($filename);
$arResult = $xml->GetArray();


foreach($arResult['sitemapindex']['#']['sitemap'] as $sitemap){
	/* $xml_2 = new CDataXML();
	$xml_2->Load($_SERVER['DOCUMENT_ROOT'].str_replace('https://www.cleanelly.ru','',$sitemap['#']['loc'][0]['#']));
	$res =  $xml_2->GetArray();
	foreach($res['urlset']['#']['url'] as $k=>$url){
		if($url['#']['loc'][0]['#'] == 'https://www.cleanelly.ru/'){
			$res['urlset']['#']['url'][$k]['#']['priority'] = array('0'=>array('@'=>array(), '#'=>1));
		}
	} */
	 $smap=file_get_contents($sitemap['#']['loc'][0]['#']);
		 if (preg_match_all('|<loc>(.+)</loc>|isU', $smap, $arr1)) { 
		 preg_match_all('|<lastmod>(.+)</lastmod>|isU', $smap, $arr2);
		$out = '<?xml version="1.0" encoding="utf-8"?>' . "\r\n";
		$out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\r\n";
		
		foreach($arr1[1] as $k=>$loc){
			$out .= '<url>' . "\r\n";
			$out .= '<loc>'.$loc.'</loc>' . "\r\n";
			$out .= '<lastmod>'.$arr2[1][$k].'</lastmod>' . "\r\n";
			if($loc == 'https://www.cleanelly.ru/'){
				$out .= '<priority>1</priority>' . "\r\n";
			} elseif($sitemap['#']['loc'][0]['#'] == 'https://www.cleanelly.ru/sitemap-iblock-17.xml'){
				$out .= '<priority>0.8</priority>' . "\r\n";
			} else {
				$out .= '<priority>0.6</priority>' . "\r\n";
			}
			$out .= '<changefreq>weekly</changefreq>' . "\r\n";
			$out .= '</url>' . "\r\n";
		}
		$out .= '</urlset>' . "\r\n";
		file_put_contents($_SERVER['DOCUMENT_ROOT'].str_replace('https://www.cleanelly.ru','',$sitemap['#']['loc'][0]['#']),$out);
		 }
		// break;
}