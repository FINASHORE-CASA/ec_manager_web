<?php

    require_once "../../../config/defines.inc.php";
    require_once "../../../vendor/autoload.php";
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    try 
    {       
        // Récupération des données 
        $formData = json_decode($_POST['myData']);  

        $result[] = "success";

        // Création de la feuille excel de consolidation
        $spreadsheetConsolidation = new Spreadsheet();
        $sheet = $spreadsheetConsolidation->getActiveSheet();

        $ExcelLetter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O',
        'P','Q','R','S','T','U','V','W','X','Y','Z'];

        // Définition des colonnes 
        $i = 0;
        foreach ($formData[0] as $key => $value) 
        {
            $sheet->setCellValue($ExcelLetter[$i].'1',strtoupper($key));
            $i++;
        }        

        foreach ($ExcelLetter as $value) 
        {
            // Configuration column 
            $sheet->getColumnDimension($value)->setAutoSize(true);
            $sheet->getStyle($value)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);            
            $sheet->getStyle($value)->getAlignment()->setHorizontal('center');
            $sheet->getStyle($value)->getAlignment()->setVertical('center');
        }
        
        // taille header
        $sheet->getRowDimension('1')->setRowHeight(40);

        // style du header 
        $styleHeader = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FF0d3f8f']
                ],
            ],        
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FF0d3f8f'],
            ]        
        ];

        // style du site
        $styleSite = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => 'FF0d3f8f']
                ],
            ],        
        ];    

        // application du style de header
        $sheet->getStyle("A1:{$ExcelLetter[($i-1)]}1")->applyFromArray($styleHeader);  

        // injection des données
        $row = 2;
        foreach ($formData as $element) 
        {
            $c = 0;
            foreach ($element as $key => $value) 
            {
                $sheet->setCellValue($ExcelLetter[$c].$row,$value);            
                $c++;
            }  
            $row++;
        }     

        // Lancement de la Génération du fichier
        $date_gen_xlsx = date('d_m_Y');
        $writer = new Xlsx($spreadsheetConsolidation);
        $file_name = 'REJET_CONTROLE_'.$date_gen_xlsx.'.xlsx';
        
        $path = "../../../".$Download_Folder.'\generer\\'.$file_name;
        $writer->save($path);

        $result[] = ".".$Download_Folder.'\\generer\\'.$file_name;

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>