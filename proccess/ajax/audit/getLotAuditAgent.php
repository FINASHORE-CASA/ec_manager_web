<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Correspondance des type_audit
    $intTypeAudit = getTypeAuditNumber($formData->type_audit);
    $search = ($formData->search) ? " and CAST(l.id_lot as varchar) like '%$formData->search%' " : "";

    if ($intTypeAudit == 0) {
        //Récupération des id_lots concernés dans la bd extra
        $qry = $bdextra->prepare("SELECT l.id,l.id_lot,l2.status_lot,count(al.id) as is_audit
                                    from audit_attribution_lot l
                                    left join audit_lot al on al.id_lot = l.id_lot and al.type_audit = l.type_audit and al.status_audit = 0
                                    inner join dblink('dbname=$db_active->datname user=$utilisateur password=$mot_passe',
                                            'SELECT id_lot,tr.status as status_lot  FROM affectationregistre af
                                             inner join tomeregistre tr on tr.id_tome_registre = af.id_tome_registre
                                             ') AS l2 (id_lot bigint,status_lot varchar)
                                    on l2.id_lot = l.id_lot
                                    where l.id_audit_user = ? and is_actived = '1' 
                                    and l.type_audit = ? and l2.status_lot = ? $search
                                    group by l.id,l.id_lot,l2.status_lot
                                ");
        $qry->execute(array($formData->id_user, $intTypeAudit, $formData->status_lot));
        $lots_audit_user = $qry->fetchAll(PDO::FETCH_OBJ);

        $result[] = $lots_audit_user;
    } else {
        //Récupération des id_lots concernés dans la bd extra
        $qry = $bdextra->prepare("SELECT l.id,l.id_lot,count(al.id) as is_audit
                                    from audit_attribution_lot l
                                    left join audit_lot al on al.id_lot = l.id_lot and al.type_audit = l.type_audit and al.status_audit = 0                                    
                                    where l.id_audit_user = ? and is_actived = '1' 
                                    and l.type_audit = ? $search
                                    group by l.id,l.id_lot
                                ");
        $qry->execute(array($formData->id_user, $intTypeAudit));
        $lots_audit_user = $qry->fetchAll(PDO::FETCH_OBJ);

        $result[] = $lots_audit_user;
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
