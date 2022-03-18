<?php
    // Require des données
    require_once "../../../config/checkConfig.php"; 
    require_once "./schema_acte.php";          

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;
        $reqs = $formData->reqs;

        if(strtolower(substr(trim($reqs),0,6)) == "select" && 
           !str_contains(strtolower($reqs),"update") && 
           !str_contains(strtolower($reqs),"delete") && 
           !str_contains(strtolower($reqs),"insert") && 
           !str_contains(strtolower($reqs),"alter")  &&  
           !str_contains(strtolower($reqs),"modify") && 
           !str_contains(strtolower($reqs),"drop"))
        {
            //Exécution de la requête envoyé
            $qry = $bdd->prepare($reqs);
            $qry->execute();
            $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);  

            if($liste_acte && property_exists($liste_acte[0], "id_acte") && $formData->show_all == 0)
            {
                $str_acte = "";
                $i = 1;
                foreach($liste_acte as $a)
                {
                    $str_acte .= ( count($liste_acte) == $i) ? $a->id_acte : $a->id_acte.",";  
                    $i++;
                }

                // Récupération des id_actes contrôlés
                $qry = $bdextra->query("select distinct id_acte from acte_ctr_fina where is_visible = false and id_acte in ($str_acte)");                
                if($qry->rowCount() > 0)
                {
                    $list_id_acte_ctr = $qry->fetchAll(PDO::FETCH_OBJ);

                    foreach($list_id_acte_ctr as $id_a)
                    {
                        $liste_acte = array_values(array_filter($liste_acte,function($e) use($id_a){return $e->id_acte != $id_a->id_acte;}));
                    }
                }
            } 
            $result[] = $liste_acte; 
        }
        else
        {
            $result[0] = "fail";
            $result[1] = "Requete non permise";
            $result[2] = $reqs; 
        }

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>