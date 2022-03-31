<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Correspondance des type_audit
    $intTypeAudit = getTypeAuditNumber($formData->typeAuditSelector);
    $search = ($formData->search) ? " and LOWER(login) like '%$formData->search%' " : "";

    //Récupération des id_lots concernés        
    $qry = $bdextra->prepare(" SELECT login,id_user,name_group, count(id_lot) as nb_lot
                                from mg_user u
                                left join mg_group_user g on g.id_type_grant = u.type_grant
                                left join audit_attribution_lot al on al.id_audit_user = u.id_user 
                                     and al.is_actived = '1' and al.type_audit = $intTypeAudit
                                where g.list_role like '%$formData->typeAudit%' $search
                                group by login,id_user,name_group
                                order by login");

    $qry->execute();
    $liste_users = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $liste_users;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
