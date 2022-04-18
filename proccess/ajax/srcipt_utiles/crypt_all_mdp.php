<?php
// Ajout des fonctions de gestion de handle function
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {

    $qry = $bdextra->prepare("select * from mg_user");
    $qry->execute();
    $users = $qry->fetchAll(PDO::FETCH_OBJ);
    $nb_modif = 0;
    foreach ($users as $user) {
        if (strlen($user->password) < 25) {

            // Lancement d'un update
            $qry = $bdextra->prepare("update mg_user set password = ? where id_user = ?;");
            $res = $qry->execute([password_hash($user->password, PASSWORD_DEFAULT), $user->id_user]);
            $nb_modif = ($res == 1) ? $nb_modif + 1 : $nb_modif;
        }
    }
    $result[] = "success";
    $result[] = $nb_modif;
    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
