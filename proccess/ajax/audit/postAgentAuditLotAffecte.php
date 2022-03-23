<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Correspondance des type_audit
    $intTypeAudit = 0;
    switch (strtolower($formData->type_audit)) {
        case 'AuditSaisi':
            $intTypeAudit = 0;
            break;
        default:
            $intTypeAudit = 0;
            break;
    }

    // Ajout des elements

    $id_lot = explode(",", $formData->id_lot);
    $notInserted;

    foreach ($id_lot as $id_l) {

        // check lot is statut demander
        $qry = $bdd->prepare("SELECT count(*) FROM lot where id_lot = ? and status_lot = ?;");
        $qry->execute([$id_l, $formData->status_lot]);

        if ($qry->fetch()[0] > 0) {
            // check du lot
            $qry = $bdextra->prepare("SELECT count(*) FROM audit_attribution_lot where id_lot = ? and is_actived = '1';");
            $qry->execute([$id_l]);

            if ($qry->fetch()[0] == 0) {

                // insertion 
                $qry = $bdextra->prepare("INSERT into audit_attribution_lot (id_lot,id_audit_user,date_attr,type_audit,is_actived,id_user_aff) values (?,?,NOW(),?,'1',?);");
                $qry->execute([$id_l, $formData->id_audit_user, $intTypeAudit, $formData->id_user_aff]);
                if (!$qry) $notInserted[] = $id_l;
            } else {
                $notInserted[] = $id_l;
            }
        } else {
            $notInserted[] = $id_l;
        }
    }

    //Récupération des id_lots concernés        
    $qry = $bdextra->prepare(" SELECT id,id_lot
                                from audit_attribution_lot
                                where id_audit_user = ? and is_actived = '1' 
                                and type_audit = ?");
    $qry->execute([$formData->id_audit_user, $intTypeAudit]);
    $lots_audit_user = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $lots_audit_user;
    $result[] = isset($notInserted) ? $notInserted : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $bdextra->rollBack();
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
