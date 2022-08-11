<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Récupération de l'acte concerné
    $qry = $bdextra->prepare("SELECT id_lot,id_acte,login,mention_acte,mention_corr,date_cont
                                from mention_manq_acte m
                                inner join mg_user u on u.id_user = m.id_user
                                where date_cont >= $formData->date_debut and date_cont <= $formData->date_fin");
    $qry->execute();
    $stats = $qry->fetchAll(PDO::FETCH_OBJ);
    $result[] = $stats;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
