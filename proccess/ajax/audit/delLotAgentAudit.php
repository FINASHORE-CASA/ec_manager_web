<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    //Récupération des id_lots concernés        
    $qry = $bdextra->prepare("DELETE FROM audit_attribution_lot WHERE id = ?");
    $qry->execute([$formData->id]);

    $result[] = $qry->fetch();

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
