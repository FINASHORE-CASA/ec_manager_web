<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte  
                                from acte a inner  
                                join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in (select id_lot from lot where status_lot = 'A')  
                                and a.imagepath not like concat('%-',a.num_acte,'.%')  
                                and a.imagepath not like concat( '%-',a.num_acte,'_%') 
                                and a.num_acte not like '%/%'
                                union
                                select af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte
                                from acte a
                                inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                where af.id_lot in ($formData->id_lot)
                                and num_acte like '%/%' and
                                REGEXP_REPLACE(replace(replace(replace(replace(replace(imagepath,'NA-',''),'DE-',''),'.jpg',''),'_P1',''),'_P2',''),';;(.*);;','') <> replace(num_acte,'/','_')");

        $qry->execute();
        $num_acte_diff_imagepath = $qry->fetchAll(PDO::FETCH_OBJ);   

        // Correction des num_acte vide
        $bdd->query("UPDATE acte SET status_acte = 'I',num_acte = 'Num_Errone-' + num_acte
                     WHERE id_acte in 
                     (
                        SELECT id_acte
                        from acte a inner  
                        join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                        where af.id_lot in (select id_lot from lot where status_lot = 'A')  
                        and a.imagepath not like concat('%-',a.num_acte,'.%')  
                        and a.imagepath not like concat( '%-',a.num_acte,'_%') 
                        and a.num_acte not like '%/%'
                        union
                        select af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte
                        from acte a
                        inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                        where af.id_lot in ($formData->id_lot)
                        and num_acte like '%/%' and
                        REGEXP_REPLACE(replace(replace(replace(replace(replace(imagepath,'NA-',''),'DE-',''),'.jpg',''),'_P1',''),'_P2',''),';;(.*);;','') <> replace(num_acte,'/','_')  
                     )");

        $result[] = $num_acte_diff_imagepath;                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
?>