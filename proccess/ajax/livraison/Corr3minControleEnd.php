<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    // Récupération des id_lots concernés        
    $qry = $bdd->prepare("SELECT id_actionec,date_debut_action,date_fin_action,  
                                (CASE WHEN(extract(EPOCH from date_fin_action - date_debut_action) >= 0  
                                and extract(EPOCH from date_fin_action -date_debut_action) <= 30)  
                                THEN(date_fin_action + interval '180 second')  
                                WHEN(extract(EPOCH from date_fin_action -date_debut_action) >= 30  
                                and extract(EPOCH from date_fin_action -date_debut_action) < 60)  
                                THEN(date_fin_action + interval '150 second')  
                                WHEN(extract(EPOCH from date_fin_action -date_debut_action) >= 60  
                                and extract(EPOCH from date_fin_action -date_debut_action) < 90)  
                                THEN(date_fin_action + interval '120 second')  
                                WHEN(extract(EPOCH from date_fin_action -date_debut_action) >= 90  
                                and extract(EPOCH from date_fin_action -date_debut_action) < 120)  
                                THEN(date_fin_action + interval '90 second')  
                                WHEN(extract(EPOCH from date_fin_action -date_debut_action) >= 120 
                                and extract(EPOCH from date_fin_action -date_debut_action) < 150)  
                                THEN(date_fin_action + interval '60 second')  
                                WHEN(extract(EPOCH from date_fin_action -date_debut_action) >= 150  
                                and extract(EPOCH from date_fin_action -date_debut_action) < 180)  
                                THEN(date_fin_action + interval '30 second') END) as new_date_fin  
                                from actionec where date_debut_action is not null  
                                and id_actionec in ( select id_actionec from actionec ac 
                                inner join acte a on a.id_acte = ac.id_acte  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in (select id_lot from lot where status_lot = 'A')  
                                and extract(EPOCH from date_fin_action -date_debut_action) >= 0  
                                and extract(EPOCH from date_fin_action -date_debut_action) < 180 )   
                                or id_actionec in (select id_actionec from actionec ac
                                inner join mariage_divorce_etranger mde on mde.id_evenement = ac.id_acte  
                                inner join affectationregistre af on af.id_tome_registre = mde.id_tome_registre  
                                where af.id_lot in (select id_lot from lot where status_lot = 'A')  
                                and extract(EPOCH from date_fin_action -date_debut_action) >= 0  
                                and extract(EPOCH from date_fin_action -date_debut_action) < 180)
                                order by id_actionec;");

    $qry->execute();
    ini_set('memory_limit', '-1');
    $liste3minLessCtrl = $qry->fetchAll(PDO::FETCH_OBJ);

    $liste_el_select;

    foreach ($liste3minLessCtrl as $less3min) {
        $liste_el_select[] = [
            "id_actionec" => $less3min->id_actionec, "date_debut_action" => $less3min->date_debut_action, "date_fin_action" => $less3min->date_fin_action, "new_date_fin_action" => $less3min->new_date_fin
        ];
    }

    // Récupération des id_lots concernés        
    $qry = $bdd->exec("UPDATE actionec set date_fin_action = 
                        (CASE WHEN ( extract( EPOCH from date_fin_action - date_debut_action)  >= 0 
                                    and extract( EPOCH from date_fin_action - date_debut_action) <= 30) 
                            THEN (date_fin_action + interval '180 second')
                        WHEN ( extract( EPOCH from date_fin_action - date_debut_action) >= 30
                                    and extract( EPOCH from date_fin_action - date_debut_action) < 60)
                            THEN (date_fin_action + interval '150 second')
                        WHEN ( extract( EPOCH from date_fin_action - date_debut_action) >= 60
                                    and extract( EPOCH from date_fin_action - date_debut_action) < 90)
                            THEN (date_fin_action + interval '120 second')
                        WHEN ( extract( EPOCH from date_fin_action - date_debut_action) >= 90
                                    and extract( EPOCH from date_fin_action - date_debut_action) < 120)
                            THEN (date_fin_action + interval '90 second')
                        WHEN ( extract( EPOCH from date_fin_action - date_debut_action) >= 120
                                    and extract( EPOCH from date_fin_action - date_debut_action) < 150)
                            THEN (date_fin_action + interval '60 second')
                        WHEN ( extract( EPOCH from date_fin_action - date_debut_action) >= 150
                                    and extract( EPOCH from date_fin_action - date_debut_action) < 180)
                            THEN (date_fin_action + interval '30 second') END)
                        where id_actionec in (
                            select id_actionec from actionec ac
                            inner join acte a on a.id_acte = ac.id_acte
                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre
                            where  af.id_lot in (select id_lot from lot where status_lot='A')
                            and extract( EPOCH from date_fin_action - date_debut_action) >= 0
                            and extract( EPOCH from date_fin_action - date_debut_action) < 180
                            and date_debut_action is not null)
                        or id_actionec in ( select id_actionec from actionec ac
                            inner join mariage_divorce_etranger mde on mde.id_evenement = ac.id_acte  
                            inner join affectationregistre af on af.id_tome_registre = mde.id_tome_registre  
                            where af.id_lot in (select id_lot from lot where status_lot = 'A')  
                            and extract(EPOCH from date_fin_action -date_debut_action) >= 0  
                            and extract(EPOCH from date_fin_action -date_debut_action) < 180
                            and date_debut_action is not null)	");

    $result[] = isset($liste_el_select) ? count($liste_el_select) : 0;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
