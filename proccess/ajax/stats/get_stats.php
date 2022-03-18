<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération des lots / et des actes  
        $qry = $bdd->prepare("SELECT id_lot FROM lot WHERE Status_lot = 'A'");
        $qry->execute();
        $lots_statut_a = $qry->fetchAll(PDO::FETCH_OBJ); 
        $result[] = $lots_statut_a;

        $qry = $bdd->prepare("SELECT id_lot FROM lot WHERE Status_lot <> 'A'");
        $qry->execute();
        $lots_statut_non_a = $qry->fetchAll(PDO::FETCH_OBJ); 
        $result[] = $lots_statut_non_a;

        $qry = $bdd->prepare("  SELECT count(distinct id_acte)
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in (SELECT id_lot FROM lot WHERE Status_lot = 'A')");
        $qry->execute();
        $acte_lot_a = $qry->fetch(); 
        $result[] = $acte_lot_a[0];


        $qry = $bdd->prepare(" SELECT count(distinct id_acte) FROM acte ");
        $qry->execute();
        $acte_lot_non_a = $qry->fetch(); 
        $result[] = $acte_lot_non_a[0];

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>