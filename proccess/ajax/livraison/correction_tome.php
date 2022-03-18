<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,tm.num_tome,tm.id_tome_registre 
                                from affectationregistre af 
                                inner join tomeregistre tm on tm.id_tome_registre = af.id_tome_registre 
                                where af.id_lot in (select id_lot from lot where status_lot = 'A') 
                                group by af.id_lot,tm.num_tome,tm.id_tome_registre");

        $qry->execute();
        $liste_db_tomes = $qry->fetchAll(PDO::FETCH_OBJ);

        // Récupération des id_lots concernés        
        $qry = $bdextra->prepare('SELECT lot,tome from lotstats');

        $qry->execute();
        $liste_extra_inventaires = $qry->fetchAll(PDO::FETCH_OBJ);

        $liste_el_select;
        $nbLot = 0;

        foreach ($liste_db_tomes as $db_tome) 
        {
            $searchedValue = $db_tome->id_lot;
            $find = array_filter(
                            $liste_extra_inventaires,
                            function ($e) use ($searchedValue) {
                                return $e->lot == $searchedValue;
                            },ARRAY_FILTER_USE_BOTH
                        );
           if(count($find) > 0)
           {
               $find = array_values($find);

               if($db_tome->num_tome != $find[0]->tome)
               {
                    $BaseCheminLot = getPathLot($db_tome->id_lot, $bdextra); 
                    $SourceTable = $ListPathImages;
                    $OldChemin = "";

                    foreach($SourceTable as $src)
                    {
                        if(is_dir(trim($src)."\\".$BaseCheminLot))
                        {
                            $OldChemin = trim($src)."\\".$BaseCheminLot;
                            break;
                        }
                        else 
                        {
                            $baseCheminExplode = explode("\\",$BaseCheminLot);
                            $baseCheminExplode[count($baseCheminExplode) - 1] = (($db_tome->id_lot.'')[12] != '0') ?  $db_tome->num_tome."_".($db_tome->id_lot.'')[12] : $db_tome->num_tome;
                            $baseCheminExplode = implode("\\",$baseCheminExplode);
                            if(is_dir(trim($src)."\\".$baseCheminExplode))
                            {
                                $OldChemin = trim($src)."\\".$baseCheminExplode;
                                break;
                            }
                        }
                    }

                    if (!empty($OldChemin) && is_dir($OldChemin))
                    {       
                        $newCheminBase = explode("\\",$OldChemin);
                        $newCheminBase[count($newCheminBase) - 1] = "";
                        $newCheminBase = implode("\\",$newCheminBase);
                        $newFullChemin = $newCheminBase.((($db_tome->id_lot.'')[12] != '0') ?  $find[0]->tome."_".($db_tome->id_lot.'')[12] : $find[0]->tome);
                        rename($OldChemin,$newFullChemin);                             
                        $nbLot++;

                        $liste_el_select[] = ["id_lot" => $db_tome->id_lot,"id_tome_actu" => $db_tome->num_tome,"id_tome_corr" => $find[0]->tome,"chemin_lot" => $OldChemin
                        ,"observation" => "CORRIGE"];

                        // Correction automatique
                        $qry = $bdd->prepare("UPDATE tomeregistre set num_tome = :num_tome where id_tome_registre = :id_tome_registre;");
                        $qry->bindParam(':id_tome_registre',$db_tome->id_tome_registre);          
                        $qry->bindParam(':num_tome', $find[0]->tome); 
                        $qry->execute();
                    }
                    else
                    {
                        $nbLot++;
                        $liste_el_select[] = ["id_lot" => $db_tome->id_lot,"id_tome_actu" => $db_tome->num_tome,"id_tome_corr" => $find[0]->tome,"chemin_lot" => $OldChemin
                        ,"observation" => "REPERTOIRE INTROUVABLE"];
                    }                                    
               }
           }
           else
           {
                $liste_el_select[] = ["id_tomeregistre"=>$db_tome->id_tome_registre ,"id_lot" => $db_tome->id_lot,"id_tome_actu" => $db_tome->num_tome,"id_tome_corr" => '',"chemin_lot" => ''
                ,"observation" => "INVENTAIRE LOT INTROUVABLE"];               
           }
        }

        $result[] = isset($liste_el_select) ? $liste_el_select : [];                                              

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>