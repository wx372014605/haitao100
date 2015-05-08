<?php
//导出excel
function export_excel($data_arr,$filename=''){
	//引入PHPExcel类
	require_once (WEB_ROOT.'PHPExcel/Classes/PHPExcel.php');
	//excel头文件
	header("Content-type:application/vnd.ms-excel");
	if($filename==''){
		$filename = time().mt_rand(1000, 9999);
	}
	$filename = preg_replace("/\..*$/", "", $filename);
	header("Content-Disposition:attachment;filename=".$filename.".xls");
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	//输出数据
	$data_count = count($data_arr);
	//根据数据量多少自动选择合适的输出方式
	if($data_count<=1000){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("fly")
								 ->setLastModifiedBy("fly")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Test Excel file");
		
		$active_sheet = $objPHPExcel->setActiveSheetIndex(0);
		foreach ($data_arr as $i=>$val){
				$item_arr = array_values($val);
				foreach ($item_arr as $j=>$item){
					$active_sheet->setCellValue(get_excel_col($j).get_excel_row($i), $item);
				}
		}
		
		//设置excel样式
		$active_sheet->getDefaultRowDimension()->setRowHeight(20);
		$excel_rows = count($data_arr);//excel行数
		if($excel_rows>0){
			$excel_cols = count($data_arr[0]);//excel列数
			$sharedStyle = new PHPExcel_Style();
			$style_arr = array(
				'alignment'	=> array(
					'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
				)
			);
			$sharedStyle->applyFromArray($style_arr);
			$active_sheet->setSharedStyle($sharedStyle, "A1:".get_excel_col($excel_cols-1).get_excel_row($excel_rows-1));
			
			//设置单元格自适应宽度
			for($col=0;$col<$excel_cols;$col++){
				$active_sheet->getColumnDimension(get_excel_col($col))->setWidth(14);
			}
		}
		
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}else{
		$data_str = '';
		foreach ($data_arr as $val){
			foreach ($val as $item){
				$data_str .= str_replace('"', '', mb_convert_encoding($item, "gbk",  "utf-8"))."\t";
			}
			$data_str .= "\n";
		}
		echo $data_str;
	}
	exit();
}

//获取excel列的名称
function get_excel_col($index){
	$letter_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	return $letter_arr[$index];
}

//获取excel行的名称
function get_excel_row($index){
	return $index+1;
}

//导出word
function export_word($data_arr,$filename=''){
	//excel头文件
	header("Content-type:application/vnd.ms-excel");
	if($filename==''){
		$filename = time().mt_rand(1000, 9999);
	}
	$filename = preg_replace("/\..*$/", "", $filename);
	header("Content-Disposition:attachment;filename=".$filename.".doc");
	//输出数据
	$data_str .= "<table border='1' cellspacing='0'>\n";
	foreach ($data_arr as $val){
		$data_str .= "<tr>\n";
		foreach ($val as $item){
			$data_str .= "<td>".$item."</td>";
		}
		$data_str .= "\n</tr>\n";
	}
	$data_str .= "</table>";
	echo $data_str;
	exit();
}
?>