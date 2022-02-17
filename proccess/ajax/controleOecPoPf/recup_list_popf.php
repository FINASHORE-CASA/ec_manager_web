<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
      $tomeregistre_columns = [
        "id_lot","id_tome_registre","id_registre","id_commune","id_bureau","utilisateur_modification","utilisateur_creation","id_procureur","id_officier","date_creation","date_modification","preimprime","affecte","status","ancien_commune","ancien_bureau","nbre_correction_niv_un","nbre_correction_niv_deux"
      ];

      $formData = json_decode($_POST['myData']);
      $result[] = "success" ;

      //Récupération des acte des id_lots concernés        
      $qry = $bdd->prepare("SELECT id_lot,tm.* 
                            from tomeregistre tm 
                            inner join affectationregistre af on af.id_tome_registre = tm.id_tome_registre
                            where af.id_lot in ($formData->id_lot)");
      $qry->execute();
      $tome_lots = $qry->fetchAll(PDO::FETCH_OBJ);  
                                     
      $result[] = $tome_lots;
      $result[] = $tomeregistre_columns;

      echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>