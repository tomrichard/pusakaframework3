<?php
namespace Pusaka\Library;

require_once( strtr(__DIR__, ['\\' => '/']) . '/' . 'Import.php' );
require_once( strtr(__DIR__, ['\\' => '/']) . '/' . 'Export.php' );

class Excel {

	protected $save_to;

	protected $map;

	protected $spreadsheet;

	static function import($file) {

		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

		$spreadsheet = NULL;

		if($ext === 'xlsx') {

			$reader 		= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

			$spreadsheet 	= $reader->load($file);

		}

		return new Import($spreadsheet);

	}

	static function export($file) {

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		return new Export($spreadsheet, $file);

	}

	function setMap($map) {

		$this->map = $map;

	}

	function each($funct, $array) {

		$sheet = $this->spreadsheet->getSheet(0);

		$count = $sheet->getHighestRow();

		for ($i=2; $i<=$count; $i++) {
			
			if($array === FALSE) {
				$row = new stdClass;
			}else {
				$row = [];
			}

			foreach ($this->map as $key => $as) {

				$cell 	= $sheet->getCell($key.$i);

				$val 	= $cell->getFormattedValue();

				if($val == '') {
					$val = NULL;
				}

				if($array === FALSE) {
					$row->{$as} = $val;
				}else {
					$row[$as] = $val;
				}

			}

			$funct($row, $i);

			unset($row);

		}

	}

}