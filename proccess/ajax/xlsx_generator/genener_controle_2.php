<?php

// -------------------------------------------------------
// -------------------------------------------------------
// -------------------------------------------------------
// Génération d'un PDF des états de registres
require_once "../../../config/checkConfig.php";

// Consolidation des fichiers et génération du fichier final
//Spreadsheet API
require_once '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$Download_Folder = "../../" . $Download_Folder;

// Récupération de la date
$date_gen_deb = date("d/m/Y");
$date_gen_fin = date("d/m/Y");

$date_deb_like = date("Y-m", strtotime($_POST['date_gen_deb'])) . "%";
$date_fin_like = date("Y-m", strtotime($_POST['date_gen_fin'])) . "%";
$date_deb_month = substr($date_deb_like, 0, 7) . "-01";

if (isset($_POST['date_gen_deb']) && isset($_POST['date_gen_fin'])) {
    $date_gen_deb = date("d/m/Y", strtotime($_POST['date_gen_deb']));
    $date_gen_fin = date("d/m/Y", strtotime($_POST['date_gen_fin']));
}

//Suppression avant génération
$lignesAff[] = $bdd->exec(" DELETE  from actionec where id_type_actionec!=11 ;");
$lignesAff[] = $bdd->exec(" DELETE  from actionec where id_acte is null;");
$lignesAff[] = $bdd->exec(" DELETE  from actionec where cast(date_debut_action as date) > cast('$date_gen_fin' as date);");
$lignesAff[] = $bdd->exec(" DELETE  from actionec where id_acte in 
                            (
                                select distinct ac.id_acte 
                                from actionec ac
                                join (select distinct id_acte from actionec where                              
                                cast(date_debut_action as date) < cast('$date_deb_month' as date)
                                ) as tb1 on tb1.id_acte = ac.id_acte
                                where cast(date_debut_action as varchar) like '$date_fin_like' or 
                                    cast(date_debut_action as varchar) like '$date_deb_like'
                            )");
$lignesAff[] = $bdd->exec(" DELETE from actionec where id_actionec  in 
                            (
                            select id_actionec FROM actionec
                            LEFT OUTER JOIN (
                                    SELECT max(id_actionec) as id, id_acte
                                    FROM actionec
                                    GROUP BY id_acte
                                ) as t1 
                                ON actionec.id_actionec = t1.id
                            WHERE t1.id IS NULL
                            );");

$lignesAff[] = $bdd->exec(" DELETE from actionec where cast(date_debut_action as date) < cast('$date_gen_deb' as date);");

// Requêtes de récupération des données en fonction de la configuration    
$qry = $bdd->prepare("  SELECT concat(prenom_agent_fr,' ',nom_agent_fr) as agent,count(*) as nb_total
                        from actionec a, agentsaisie s
                        where id_type_actionec=11 
                        and a.date_debut_action is not null 
                        and cast(a.utilisateur_modification as integer)=cast(id_agent as integer)
                        and cast(a.date_debut_action as date) >= cast('{$date_gen_deb}' as date)
                        and cast(a.date_debut_action as date) <= cast('{$date_gen_fin}' as date)
                        group by concat(prenom_agent_fr,' ',nom_agent_fr)
                        order by concat(prenom_agent_fr,' ',nom_agent_fr)");
$qry->execute();
$vues = $qry->fetchAll(PDO::FETCH_OBJ);
$qry->closeCursor();

// Création de la feuille excel de consolidation
$spreadsheetConsolidation = new Spreadsheet();
$sheet = $spreadsheetConsolidation->getActiveSheet();
// Définition du type de fichier 
$inputFileType = 'Xlsx';

$sheet->setCellValue('A1', "Agents");
$sheet->setCellValue('B1', "Nombre Total");

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
foreach ($vues as $vue) {
    $sheet->getRowDimension($i)->setRowHeight(20);
    $sheet->setCellValue('A' . $i, $vue->agent);
    $sheet->setCellValue('B' . $i, $vue->nb_total);
    $i++;
    $j++;
}

$date_gen_deb_title = isset($_POST['date_gen_deb']) ? date("d_m_Y", strtotime($_POST['date_gen_deb'])) : date("d_m_Y");
$date_gen_fin_title = isset($_POST['date_gen_fin']) ? date("d_m_Y", strtotime($_POST['date_gen_fin'])) : date("d_m_Y");
$writer = new Xlsx($spreadsheetConsolidation);
$file_name = 'CONTROLE_2_' . $date_gen_deb_title . '_' . $date_gen_fin_title . '.xlsx';
$path = $Download_Folder . '\generer\\' . $file_name;
$writer->save($path);

// auto téléchargement du fichier 
header("Content-type: application/force-download");
header("Content-Length: " . filesize($path));
header("Content-Disposition: attachment; filename=" . basename($path));
readfile($path);
