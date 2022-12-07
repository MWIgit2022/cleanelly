<?
$file = file_get_contents('log.php');
$arr = explode(PHP_EOL,str_replace('<br>','',$file));
echo '<pre>';
	foreach($arr as $str){
		if(stristr($str,'--')){
			$str_arr = explode('--',$str);
			if(stristr($str_arr[2],'null')==false){
				print_r($str_arr);
			}
		}
	}
echo '</pre>'; 