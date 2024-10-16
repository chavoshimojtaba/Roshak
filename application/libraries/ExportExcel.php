<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\IOFactory;
class ExportExcel
{   
	public $fileName; 
	public $data=[]; 
	public $sheet;  
	public $spreadsheet;  
	public $headers=[];  
	public $endChar='A';  
	public $countData;  
	public $chars=[
		'A',
		'B',
		'C',
		'D',
		'E',
		'F',
		'G',
		'H',
		'I',
		'J',
		'K',
		'L',
		'M',
		'N',
		'O',
	];  
	
	public function __construct($fileName)
	{    
		$this->author  	   = $_SESSION['full_name']; 
		$this->fileName    = $fileName; 
		$this->spreadsheet = new Spreadsheet();
        $this->sheet       = $this->spreadsheet->getActiveSheet(); 
	} 
	public function setData($headers,$data) { 
		$this->headers   = $headers;
		$this->data 	 = array_merge([$headers],$data); 
		$this->countData = count($this->data);
		$this->endChar   = $this->chars[count(array_keys($this->data[0]))]; 
	} 
	public function generate($styleType)
	{
		
		$this->sheet->fromArray($this->data,null,'A1');

        $this->generalStyles();
        if($styleType=='translate'){
			$this->translationsStyles(); 
		} 
		ob_end_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $this->fileName.'_'.strtotime("now"). '.xlsx"');
		header('Cache-Control: max-age=0');
		
		$xlsxWriter = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
		$xlsxWriter = new  Xlsx($this->spreadsheet);
		exit($xlsxWriter->save('php://output'));
        // $writer->save($this->fileName.strtotime("now").'.xlsx');
		 
	}

	public function generalStyles()
	{
		$this->sheet
        ->getStyle("A1:{$this->endChar}1")
        ->getFont()->setBold(true);
        $this->sheet->getStyle("A1:{$this->endChar}1")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$this->sheet
        ->getStyle("A1:{$this->endChar}1")
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('66BB6A'); 
        $stlyes = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
					'color' => array('rgb' => 'AAAAAA'),
                ]
            ]
        ];
        $this->sheet->getStyle("A1:{$this->endChar}".$this->countData)->applyFromArray($stlyes); 

	}
	public function translationsStyles()
	{ 
        $this->sheet
        ->getStyle('B2:C'.$this->countData)
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('E8EAF6'); 
        $this->sheet
        ->getStyle('D2:E'.$this->countData)
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('E8F5E9'); 
        $this->sheet
        ->getStyle('F2:G'.$this->countData)
        ->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('E3F2FD');    
	}
}



?>  