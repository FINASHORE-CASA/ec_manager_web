<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,nom_fr,prenom_fr,nom_ar,prenom_ar
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in ($formData->id_lot)   
                                and (a.imagepath is null or a.imagepath = '')");

        $qry->execute();
        $imagepath_vide = $qry->fetchAll(PDO::FETCH_OBJ);  

        // $nbAff1 =   $bdd->exec("DELETE from transcription where id_acte in (
        //             SELECT id_acte
        //                 from acte a  
        //                 inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                 where af.id_lot in ($formData->id_lot)   
        //                 and (a.imagepath is null or a.imagepath = ''))");

        // $nbAff2 =   $bdd->exec("DELETE FROM controle_mention WHERE id_mention in 
        //             (select id_mention from mention where id_acte in 
        //                 (select id_acte from acte a  
        //                  inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                  where af.id_lot in ($formData->id_lot)   
        //                  and (a.imagepath is null or a.imagepath = ''));");

        // $nbAff3 =   $bdd->exec("DELETE from mention where id_acte in (
        //             SELECT id_acte
        //                 from acte a  
        //                 inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                 where af.id_lot in ($formData->id_lot)   
        //                 and (a.imagepath is null or a.imagepath = ''))");

        // $nbAff4 =   $bdd->exec("DELETE from deces where id_acte in (
        //             SELECT id_acte
        //                 from acte a  
        //                 inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                 where af.id_lot in ($formData->id_lot)   
        //                 and (a.imagepath is null or a.imagepath = ''))");

        // $nbAff5 =   $bdd->exec("DELETE from jugement where id_acte in (
        //             SELECT id_acte
        //                 from acte a  
        //                 inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                 where af.id_lot in ($formData->id_lot)   
        //                 and (a.imagepath is null or a.imagepath = ''))");

        // $nbAff6 =   $bdd->exec("DELETE from controle_acte where id_acte in (
        //             SELECT id_acte
        //                 from acte a  
        //                 inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                 where af.id_lot in ($formData->id_lot)   
        //                 and (a.imagepath is null or a.imagepath = ''))");        

        // $nbAff7 =   $bdd->exec("DELETE from declaration where id_acte in (
        //             SELECT id_acte
        //                 from acte a  
        //                 inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                 where af.id_lot in ($formData->id_lot)   
        //                 and (a.imagepath is null or a.imagepath = ''))");

        // $nbAff8 =   $bdd->exec("DELETE FROM acte 
        //             WHERE id_acte in (
        //                 SELECT id_acte
        //                 from acte a  
        //                 inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                 where af.id_lot in ($formData->id_lot)   
        //                 and (a.imagepath is null or a.imagepath = ''))");

        $result[] = $imagepath_vide;                                      
        // $result[] = "transcription : ".$nbAff1.",controle_mention : ".$nbAff2.",mention : ".$nbAff3.", deces : ".$nbAff4.",jugement : ".$nbAff5.",controle_acte : ".$nbAff6.",declaration : ".$nbAff7.",acte : ".$nbAff8;                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>