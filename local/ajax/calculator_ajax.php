<?
include($_SERVER['DOCUMENT_ROOT'].'/local/ajax/size_arrays.php');

$type= $_POST['type'];
unset($_POST['type']);

if($type == 'men'){
	$table = $men;
} elseif($type == 'women'){
	$table = $women;
} elseif($type == 'children'){
	$table = $children;
}



foreach($table as $size=>$arr){
	foreach($_POST as $k=>$v){
		if($arr[$k] == '-'){
			continue;
		}
		$vals = explode('-',$arr[$k]);
		if(count($vals) == 2){
			if($v>=$vals[0] && $v<$vals[1]){
				$itog_key = $size;
				if($arr['SIZE']){
					$itog_key.='|'.$arr['SIZE'];
				}
				$itog[$itog_key]++;
			}
		} else {
			if($v==$vals[0]){
				$itog_key = $size;
				if($arr['SIZE']){
					$itog_key.='/'.$arr['SIZE'];
				}
				$itog[$itog_key]++;
			}
		}
	}
}
arsort($itog);
$needle = current($itog);
foreach($itog as $k=>$v){
	if($v !=$needle){
		unset($itog[$k]);
	}
}
if(count($itog) == 1){
	echo 'Больше всего вам подойдет размер <span class="size">'.current(array_flip($itog)).'</span>';
} else {
	$out = 'Указанным параметрам соответсвует несколько размеров: ';
	foreach($itog as $k=>$v){
		$out.='<span class="size">'.$k.'</span>';
	}
	$out .= '<br>Пожалуйста, сверьтесь с <span class="animate-load table link" data-event="jqm" data-param-form_id="TABLES_SIZE" data-param-url="/include/table_sizes/detail_clothes.php" data-name="TABLES_SIZE">таблицей размеров</span> и выберете наиболее подходящий';
	
	echo $out;
}
?>