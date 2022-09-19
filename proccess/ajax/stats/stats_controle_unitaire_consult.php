<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Récupération de l'acte concerné
    $qry = $bdextra->prepare("SELECT id_lot,login,count(distinct id_acte) as nb_acte_ctr,to_char(date_action,'DD/MM/YYYY') as date_ctr
                                from action_user_ctr ac
                                inner join  mg_user u on u.id_user = ac.id_user_ctr
                                left join action_field af on af.id_action = ac.id
                                where date_action >= ? and date_action <= ? and af.id_action is null
                                group by id_lot,login,to_char(date_action,'DD/MM/YYYY')
                                order by id_lot,login,date_ctr");
    $qry->execute(array($formData->date_debut, $formData->date_fin));
    $stats = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $stats;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
