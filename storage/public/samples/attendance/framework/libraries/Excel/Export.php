<?php 
namespace Pusaka\Library;

class Export extends Excel {

	private $sheet;

	function __construct($spreadsheet, $file) {
		
		$this->spreadsheet = $spreadsheet;

		$this->save_to 	   = $file;

	}

	function createSheet($name) {

		$worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($this->spreadsheet, $name);

		$this->spreadsheet->addSheet($worksheet, 0);

		$this->sheet = $this->spreadsheet->getSheetByName($name);

		$this->spreadsheet->setActiveSheetIndex(0);

	}

	function activeSheet() {
		$this->sheet = $this->spreadsheet->getActiveSheet();
	}

	function setValue($key, $row, $value) {

		$key = array_search($key, $this->map);

		if($key !== false) {
			$this->sheet->setCellValue($key.$row, $value);
			$this->sheet->getColumnDimension($key)->setAutoSize(true);
		}


	}

	function save() {

		$file 	= $this->save_to;

		$ext 	= strtolower(pathinfo($file, PATHINFO_EXTENSION));

		if($ext = 'xlsx') {
			
			$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, "Xlsx");
			$writer->save($file);

			if(file_exists($file)) {
				return true;
			}else {
				return false;
			}
		
		}

		return false;

	}

}