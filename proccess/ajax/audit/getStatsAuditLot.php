<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Correspondance des type_audit
    $intTypeAudit = getTypeAuditNumber($formData->status);

    //Récupération des id_lots concernés dans la bd extra
    $qry = $bdextra->prepare(" SELECT l.id_lot,l2.id_commune,l2.id_bureau,l.nb_actes,l.percent_ech_audit,to_char(l.date_audit, 'dd/mm/YYYY') as date_audit,to_char(date_fin_audit, 'dd/mm/YYYY') as date_fin_audit,login
                                from audit_lot l
                                inner join dblink('dbname=$db_active->datname user=$utilisateur password=$mot_passe',
                                        'select id_lot,status_lot,id_commune,id_bureau from lot') 
                                        AS l2 (id_lot bigint,status_lot varchar,id_commune integer,id_bureau integer)
                                on l2.id_lot = l.id_lot
                                inner join mg_user m on m.id_user = id_audit_user
                                inner join audit_acte a on a.id_lot = l.id_lot and a.id_passage_audit_type = l.id_passage_audit_type
                                where l.type_audit = ? and l.status_audit > 0 
                                group by l.id_lot,l2.id_commune,l2.id_bureau,l.nb_actes,l.percent_ech_audit,to_char(l.date_audit, 'dd/mm/YYYY'),to_char(date_fin_audit, 'dd/mm/YYYY'),login");
    $qry->execute(array($intTypeAudit));
    $lots_stats = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $lots_stats;
    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
