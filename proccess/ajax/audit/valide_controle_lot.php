<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Récupération des informations des actes déja audités
    // Nombre actes audités
    $qry = $bdextra->prepare("SELECT id_acte,status_audit_acte
                                from audit_acte
                                where id_lot = ? 
                                and type_audit = ? 
                                and id_audit_user = ?
                                and id_passage_audit_type = ?");
    $qry->execute([
        $formData->id_lot, $formData->type_audit, $formData->id_audit_user, $formData->id_passage_audit_type
    ]);

    $liste_actes_audit = $qry->fetchAll(PDO::FETCH_OBJ);

    if (count($liste_actes_audit) == ceil(($formData->percent_ech_audit * $formData->nb_actes) / 100)) {

        $is_accept = (count(array_filter($liste_actes_audit, function ($e) {
            return $e->status_audit_acte == 1;
        })) > 0) ? 1 : 2;

        // Validation du lot
        $qry = $bdextra->prepare("UPDATE audit_lot set status_audit = ?,date_fin_audit = NOW() where id = ?;");
        $qry->execute([$is_accept, $formData->id]);

        // Retrait de l'Attribution du lot
        $qry = $bdextra->prepare("DELETE FROM audit_attribution_lot WHERE id_lot = ? and is_actived = '1' and id_audit_user = ?");
        $qry->execute([$formData->id_lot, $formData->id_audit_user]);

        switch ($formData->type_audit) {
            case 0:
                $status_lot = 'I';
                break;
            case 1:
                $status_lot = 'V';
                break;
            case 2:
                $status_lot = 'E';
                break;
        }

        if ($formData->type_audit == 0) {
            if ($is_accept == 1) {
                try {

                    $bdd->beginTransaction();
                    // initialisation du lot 
                    // -- serait mieux de procéder par une fonction
                    $nbAff1 = $bdd->exec("UPDATE acte SET status_acte='$status_lot', status_acteechantillon='$status_lot' where 
                                    id_tome_registre in (
                                    select tr.id_tome_registre from tomeregistre tr inner join registre r on r.id_registre=tr.id_registre
                                    inner join affectationregistre ar on ar.id_tome_registre=tr.id_tome_registre
                                    inner join lot l on l.id_lot=ar.id_lot
                                        where l.id_lot in ($formData->id_lot)
                                    );");

                    $nbAff2 = $bdd->exec("UPDATE tomeregistre SET status='$status_lot' where 
                                            id_tome_registre in (
                                            select tr.id_tome_registre from tomeregistre tr inner join registre r on r.id_registre=tr.id_registre
                                            inner join affectationregistre ar on ar.id_tome_registre=tr.id_tome_registre
                                            inner join lot l on l.id_lot=ar.id_lot
                                                where l.id_lot in ($formData->id_lot)
                                            );");

                    $nbAff3 = $bdd->exec("UPDATE lot SET status_lot='$status_lot',num_echantillon=0 where id_lot in ($formData->id_lot);");
                    $bdd->commit();
                    $result[] = "Initialisation effectuée";
                } catch (Exception $e) {
                    $bdd->rollback();
                    $result[] = "Erreur lors de l'Initialisation";
                }
            }
        }
    } else {
        $result[1] = "non_termine";
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
