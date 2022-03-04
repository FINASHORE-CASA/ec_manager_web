<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $result[] = "success";

    // Récupération de l'acte concerné
    $qry = $bdextra->prepare("  SELECT name,first_name,type_grant, to_char(u.date_creat,'DD-MM-YYYY HH:MI AM') as date_creat, to_char(u.date_last_up,' DD-MM-YYYY HH:MI AM') as date_last_up,login,password,id_user,name_group
                                    from mg_user u
                                    left join mg_group_user g on g.id_type_grant = u.type_grant");

    $qry->execute();
    $users = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $users;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
