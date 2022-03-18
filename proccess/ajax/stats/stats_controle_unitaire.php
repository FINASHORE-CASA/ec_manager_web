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
                            where id_lot in ($formData->id_lot)
                            group by id_lot,login,to_char(date_action,'DD/MM/YYYY')
                            order by id_lot,login,date_ctr");
    $qry->execute();
    $stats = $qry->fetchAll(PDO::FETCH_OBJ);

    $qry = $bdd->prepare("  SELECT af.id_lot,count(distinct a.id_acte) as nb_acte
                            from acte a  
                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            where af.id_lot in ($formData->id_lot)
                            group by id_lot
                            order by af.id_lot");

    $qry->execute();
    $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);

    foreach ($stats as $key => $value) {
        $find = array_filter($liste_acte, function ($li) use ($value) {
            return $li->id_lot == $value->id_lot;
        });

        if (count($find) > 0) {
            $find = array_values($find);
            $value->nb_acte = $find[0]->nb_acte;
        }
    }

    $result[] = $stats;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
