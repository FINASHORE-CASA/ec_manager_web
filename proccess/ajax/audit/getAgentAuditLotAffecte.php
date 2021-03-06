<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Correspondance des type_audit
    $intTypeAudit = getTypeAuditNumber($formData->type_audit);

    //Récupération des id_lots concernés        
    $qry = $bdextra->prepare(" SELECT id,id_lot
                                from audit_attribution_lot
                                where id_audit_user = ? and is_actived = '1' 
                                and type_audit = ?");
    $qry->execute(array($formData->id_user, $intTypeAudit));
    $lots_audit_user = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $lots_audit_user;
    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
