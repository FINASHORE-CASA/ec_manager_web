<?php
    // Require des données
    require_once "../../../config/checkConfig.php"; 
    require_once "./schema_acte.php";          

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        //Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,a.*
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in ($formData->id_lot)
                                order by af.id_lot,a.num_acte,a.imagepath");

        $qry->execute();
        $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);  

        $result[] = $liste_acte;
        $result[] = $liste_champs_actes;

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>