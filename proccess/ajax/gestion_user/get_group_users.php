<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $result[] = "success";

    // Récupération de l'acte concerné
    $qry = $bdextra->prepare("  SELECT id_type_grant,list_role,date_creat,date_modif,name_group
                                from mg_group_user");

    $qry->execute();
    $group_user = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $group_user;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
