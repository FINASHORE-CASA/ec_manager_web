<?php

// -------------------------------------------------------
// -------------------------------------------------------
// -------------------------------------------------------
// Génération d'un PDF des états de registres
require_once "../../config/checkConfig.php"; 

// Consolidation des fichiers et génération du fichier final
//Spreadsheet API
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$Download_Folder = "../../".$Download_Folder;


// Récupération de la date
$date_gen_deb = date("d/m/Y");
$date_gen_fin = date("d/m/Y");

if(isset($_POST['date_gen_deb']) && isset($_POST['date_gen_fin']))
{
    $date_gen_deb = date("d/m/Y",strtotime($_POST['date_gen_deb']));
    $date_gen_fin = date("d/m/Y",strtotime($_POST['date_gen_fin']));
}

//Suppression avant génération
$lignesAff[] = $bdd->exec(" DELETE from controle_acte where id_acte IS NULL");
$lignesAff[] = $bdd->exec(" DELETE from controle_acte where cast(date_creation as varchar) like '2021-09-26%'; 
                            DELETE from controle_acte where cast(date_creation as varchar) like '2021-09-27%';");
$lignesAff[] = $bdd->exec(" DELETE from  controle_acte where 
                            (cast(date_creation as varchar) like '2021-09%' and 
                            id_acte  in
                            (select distinct id_acte from controle_acte where 
                            cast(date_creation as varchar) like '2021-04%' 
                            or cast(date_creation as varchar) like '2021-05%' 
                            or cast(date_creation as varchar) like '2021-06%'
                            or cast(date_creation as varchar) like '2021-07%'
                            ))
                            or 
                            (cast(date_creation as varchar) like '2021-08%' and 
                            id_acte  in
                            (select distinct id_acte from controle_acte where 
                            cast(date_creation as varchar) like '2021-04%' 
                            or cast(date_creation as varchar) like '2021-05%' 
                            or cast(date_creation as varchar) like '2021-06%'
                            or cast(date_creation as varchar) like '2021-07%'
                            ));");                                        
$lignesAff[] = $bdd->exec(" DELETE from controle_acte where id_controle_acte  in 
                            (
                                select id_controle_acte FROM controle_acte
                                LEFT OUTER JOIN (
                                        SELECT max(id_controle_acte) as id, id_acte
                                        FROM controle_acte
                                        GROUP BY id_acte
                                    ) as t1 
                                    ON controle_acte.id_controle_acte = t1.id
                                WHERE t1.id IS NULL
                            )");

$lignesAff[] = $bdd->exec(" DELETE from controle_acte where cast(date_creation as varchar)  like '2021-04%';
                            delete from controle_acte where cast(date_creation as varchar)  like '2021-05%';
                            delete from controle_acte where cast(date_creation as varchar)  like '2021-06%';
                            delete from controle_acte where cast(date_creation as varchar)  like '2021-07%';
                            delete from controle_acte where cast(date_creation as varchar)  like '2021-08-0%';
                            delete from controle_acte where cast(date_creation as varchar)  like '2021-08-1%';
                            delete from controle_acte where cast(date_creation as varchar)  like '2021-08-20%';
                            delete from controle_acte where cast(date_creation as varchar)  like '2021-08-21%';
                            delete from controle_acte where cast(date_creation as varchar)  like '2021-08-22%';");

// Requêtes de récupération des données en fonction de la configuration    
$qry = $bdd->prepare("  SELECT concat(prenom_agent_fr,' ',nom_agent_fr) as agent,count(*) as nb_total
                        from controle_acte a, agentsaisie s
                        where cast(a.utilisateur_creation as integer)=cast(id_agent as integer) 
                        and a.date_creation >= '{$date_gen_deb}'
                        and a.date_creation <= '{$date_gen_fin}'
                        group by concat(prenom_agent_fr,' ',nom_agent_fr)
                        order by concat(prenom_agent_fr,' ',nom_agent_fr)");
$qry->execute();
$vues = $qry->fetchAll(PDO::FETCH_OBJ);
$qry->closeCursor();

// var_dumper($qry,false);
// var_dumper($vues,true);

// Création de la feuille excel de consolidation
$spreadsheetConsolidation = new Spreadsheet();
$sheet = $spreadsheetConsolidation->getActiveSheet();
// Définition du type de fichier 
$inputFileType = 'Xlsx';

$sheet->setCellValue('A1',"Agents");
$sheet->setCellValue('B1',"Nombre Total");

// Configuration column 
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);

// définition des formats spéciaux de columns
$sheet->getStyle('A')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);
$sheet->getStyle('B')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_GENERAL);

// Alignement des columns
$sheet->getStyle('A')->getAlignment()->setHorizontal('left');
$sheet->getStyle('A')->getAlignment()->setVertical('center');
$sheet->getStyle('B')->getAlignment()->setHorizontal('left');
$sheet->getStyle('B')->getAlignment()->setVertical('center');
    
$sheet->getStyle('A1:B1')->getAlignment()->setHorizontal('center');
$sheet->getStyle('A1:B1')->getAlignment()->setVertical('center');

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
$sheet->getStyle('A1:B1')->applyFromArray($styleHeader);  

// Remlissage du tableau des données 
$i = 2; // compteur d'élément
$j = 0;
// var_dumper($vues,true);
foreach ($vues as $vue) 
{    
    $sheet->getRowDimension($i)->setRowHeight(20);
    $sheet->setCellValue('A'.$i,$vue->agent);
    $sheet->setCellValue('B'.$i,$vue->nb_total);
    $i++;
    $j++;
}

$date_gen_deb_title = isset($_POST['date_gen_deb']) ? date("d_m_Y",strtotime($_POST['date_gen_deb'])) : date("d_m_Y");
$date_gen_fin_title = isset($_POST['date_gen_fin']) ? date("d_m_Y",strtotime($_POST['date_gen_fin'])) : date("d_m_Y");
$writer = new Xlsx($spreadsheetConsolidation);
$file_name = 'CONTROLE_1_'.$date_gen_deb_title.'_'.$date_gen_fin_title.'.xlsx';
$path = $Download_Folder.'\generer\\'.$file_name;
$writer->save($path);

// auto téléchargement du fichier 
header("Content-type: application/force-download");
header("Content-Length: ".filesize($path));
header("Content-Disposition: attachment; filename=".basename($path));
readfile($path);