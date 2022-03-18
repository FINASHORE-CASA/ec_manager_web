<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Récupération de l'acte concerné
    $qry = $bdextra->prepare("  SELECT id_type_grant,list_role,date_creat,date_modif,name_group
                                from mg_group_user
                                where id_type_grant = ?");

    $qry->execute(array($formData->id_type_grant));
    $user = $qry->fetch(PDO::FETCH_OBJ);

    $result[] = $user;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
