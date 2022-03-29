<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Correspondance des type_audit
    $intTypeAudit = getTypeAuditNumber($formData->type_audit);

    //Récupération des id_lots concernés dans la bd extra
    $qry = $bdextra->prepare(" SELECT l.id,l.id_lot,l2.status_lot
                                from audit_attribution_lot l
                                inner join dblink('dbname=$db_active->datname user=$utilisateur password=$mot_passe',
                                        'select id_lot,status_lot from lot') AS l2 (id_lot bigint,status_lot varchar)
                                on l2.id_lot = l.id_lot
                                where id_audit_user = ? and is_actived = '1' 
                                and type_audit = ? and l2.status_lot = ? ");
    $qry->execute(array($formData->id_user, $intTypeAudit, $formData->status_lot));
    $lots_audit_user = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $lots_audit_user;
    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
