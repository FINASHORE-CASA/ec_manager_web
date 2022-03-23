<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    //Récupération des id_lots concernés        
    $qry = $bdextra->prepare(" SELECT login,id_user,name_group
                            from mg_user u
                            left join mg_group_user g on g.id_type_grant = u.type_grant
                            where g.list_role like '%$formData->typeAudit%'
                            order by login");

    $qry->execute();
    $liste_users = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $liste_users;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
