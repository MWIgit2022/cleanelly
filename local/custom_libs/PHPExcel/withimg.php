<?foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
	# Массив с изображениями
	$images = array();
	
	# Забираем информацию о изображениях
	foreach ($worksheet->getDrawingCollection() as $img) {
		# Открываем изображение
		$im = file_get_contents($img->getPath());
		# Забираем его с Excel и записываем в новый файл на сервере
		file_put_contents("./img/" . $img->getFilename(), $im);
		# Добавляем в массив информацию о новом изображении
		$images[$img->getCoordinates()] = $img->getFilename();
	}
	
	foreach ($worksheet->getRowIterator() as $row) {
		echo '    - Row number: ' . $row->getRowIndex() . "\r\n";

		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		
		foreach ($cellIterator as $cell) {
			if (!is_null($cell)) {
				$coordinate = $cell->getCoordinate();
				# Если есть в массиве изображение, то выводим его
				echo '        - Cell: ' . $coordinate . ' - ' . (isset($images[$coordinate]) ? $images[$coordinate] : $cell->getValue()) . "\r\n";
			}
		}
	}
}