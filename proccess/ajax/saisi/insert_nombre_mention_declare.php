<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Récupération des id_lots concernés        
    $qry = $bdextra->prepare("INSERT into mention_manq_acte (id_lot,id_acte,mention_acte,mention_corr,date_cont,id_user) 
                              VALUES (?,?,?,?,NOW(),?);");
    $qry->execute([$formData->id_lot, $formData->id_acte, $formData->mention_acte, $formData->mention_corr, $formData->id_user]);
    $result[] = $formData;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
