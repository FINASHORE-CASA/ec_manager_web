<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Correspondance des type_audit
    $intTypeAudit = getTypeAuditNumber($formData->status);

    // prepare count schemat
    $qry = $bdextra->prepare("SELECT concat('sum(',column_name,') as ',replace(column_name,'ct_','')) as libelle
                        from information_schema.tables tb
                        inner join information_schema.columns cl on cl.table_name = tb.table_name
                        where tb.table_name = 'audit_acte' 
                        and column_name like 'ct_%'
                        order by ordinal_position");
    $qry->execute();
    $list_champs = $qry->fetchAll(PDO::FETCH_OBJ);

    foreach ($list_champs as $champs) {
        $list_str_champs[] = $champs->libelle;
    }

    $list_champs_count = join(",", $list_str_champs);

    //Récupération des id_lots concernés dans la bd extra
    $qry = $bdextra->prepare(" SELECT l.id_lot,l2.id_commune,l2.id_bureau,l.nb_actes,l.percent_ech_audit,ceil(l.percent_ech_audit * l.nb_actes / 100.0) as nb_act_ech
                            ,to_char(l.date_audit, 'dd/mm/YYYY') as date_audit,to_char(date_fin_audit, 'dd/mm/YYYY') as date_fin_audit,login
                            , $list_champs_count
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
