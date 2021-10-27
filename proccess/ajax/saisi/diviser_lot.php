<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT * 
                                from lot
                                where id_lot in ($formData->id_lot)");

        $qry->execute();
        $listLotTrouves = $qry->fetchAll(PDO::FETCH_OBJ);  

        foreach($listLotTrouves as $lot) 
        {
            // Vérification que le lot n'est pas encore diviser 
            $qry = $bdextra->prepare("SELECT count(id_lot_parent) from mg_lot_division where id_lot_parent = ? ");
            $qry->execute(array($lot->id_lot));
            $lotDiviser = $qry->fetch();                             

            if ($lotDiviser[0] == 0) 
            {
                // Récupération des affectationregistres
                $qry = $bdd->prepare("SELECT * 
                                        from affectationregistre af
                                        inner join tomeregistre tr on tr.id_tome_registre = af.id_tome_registre
                                        inner join registre rg on rg.id_registre = tr.id_registre
                                        where id_lot = ? ");
                $qry->execute(array($lot->id_lot));
                $lotRegistre = $qry->fetch(PDO::FETCH_OBJ);

                $nb_div = $formData->nb_division;
                $lots_id_tome_registre = array();
                $lots_id_tome_registre[] = $lotRegistre->id_tome_registre;
                $lots_id_lot[] = $lotRegistre->id_lot;
                for ($i=0 ; $i < $nb_div ; $i++) 
                {
                    if ($i.'' != ($lot->id_lot.'')[strlen($lot->id_lot.'')-1].'') 
                    {
                        // new id_lot
                        $newlot = substr($lot->id_lot.'', 0, 13).$i;

                        // Vérification que le new lot n'existe pas
                        $qry = $bdd->prepare("SELECT count(id_lot) from lot where id_lot in (?)");
                        $qry->execute(array($newlot));
                        $lotExiste = $qry->fetch();

                        if ($lotExiste[0] == 0) {
                            // insertion lot
                            $qry = $bdd->prepare("  INSERT into lot (id_lot,date_ouverture,date_fermeture,utilisateur_creation,date_creation,utilisateur_modification,
                                                                    date_modification,id_bureau,id_commune,status_lot,num_echantillon,date_echantillon)
                                                    values(?,CURRENT_DATE,CURRENT_DATE,0,CURRENT_DATE,null,CURRENT_DATE,?,?,'I',0,null)
                                                    RETURNING id_lot;");

                            $qry->execute(array($newlot,$lotRegistre->id_bureau,$lotRegistre->id_commune));
                            $newlot_inserted = $qry->fetch()[0];

                            // insertion registre
                            $qry = $bdd->prepare(" INSERT into registre (id_type_registre,annee_registre_greg,annee_registre_hegire,nbre_tome,utilisateur_creation
                                                                        ,date_creation,utilisateur_modification,date_modification,id_commune,id_bureau) 
                                                    values(?,?,?,0,0,CURRENT_DATE,null,CURRENT_DATE,?,?)
                                                    RETURNING id_registre;");

                            $qry->execute(array(($lot->id_lot.'')[0],$lotRegistre->annee_registre_greg,$lotRegistre->annee_registre_hegire,$lotRegistre->id_commune,$lotRegistre->id_bureau));
                            $id_registre_inserted = $qry->fetch()[0];

                            // insertion tomeregistre
                            $qry = $bdd->prepare(" INSERT into tomeregistre (num_tome,nbre_page,nbre_acte_naissance,nbre_acte_deces,nbre_acte_mariage
                                                                            ,nbre_acte_divorce,id_registre,date_creation,date_modification,indice_num_tome,
                                                                            utilisateur_creation,utilisateur_modification,id_bureau,id_commune)
                                                    values(?,?,?,?,?,?,?,CURRENT_DATE,null,?,0,null,?,?)
                                                    RETURNING id_tome_registre;");

                            $qry->execute(array($lotRegistre->num_tome,$lotRegistre->nbre_page,$lotRegistre->nbre_acte_naissance,$lotRegistre->nbre_acte_deces,$lotRegistre->nbre_acte_mariage,
                                                $lotRegistre->nbre_acte_divorce,$id_registre_inserted,$lotRegistre->indice_num_tome,$lotRegistre->id_bureau,$lotRegistre->id_commune));
                            $id_tome_registre_inserted = $qry->fetch()[0];

                            // insertion affectationregistre
                            $qry = $bdd->prepare("  INSERT into affectationregistre (id_lot,id_tome_registre,date_affectation,date_fin_affectationprev,date_fin_affectationreel
                                                                                    ,utilisateur_creation,date_creation,utilisateur_modification,
                                                                                    date_modification,id_bureau,id_commune)								
                                                    values(?,?,null,null,null,0,CURRENT_DATE,null,null,?,?)
                                                    RETURNING id_affectationregistre;");

                            $qry->execute(array($newlot,$id_tome_registre_inserted,$lotRegistre->id_bureau,$lotRegistre->id_commune));
                            $id_affectationregistre_inserted = $qry->fetch()[0];

                            // insertion table mg_lot_division
                            $qry = $bdextra->prepare("  INSERT into mg_lot_division (id_lot_parent,id_lot_child,date_division,date_fusion)
                                                        values (?,?,NOW(),null);");

                            $qry->execute(array($lot->id_lot,$newlot));

                            // conservation du tome registre pour insertion
                            $lots_id_tome_registre[] = $id_tome_registre_inserted;
                            $lots_id_lot[] = $newlot;
                        }
                    } elseif ($i < 9) 
                    {
                        $nb_div = $nb_div + 1;
                    }
                }

                // Récupération du nombre d'actes
                $qry = $bdd->prepare(" SELECT count(id_acte) from acte where id_tome_registre = ? ");
                $qry->execute(array($lots_id_tome_registre[0]));
                $nb_actes = $qry->fetch()[0];

                // division obtention du nombre limit de chaque lot
                $nb_limit = ceil($nb_actes / count($lots_id_tome_registre));
                $list_lots_lot = "";

                for ($i = 1;$i < (count($lots_id_tome_registre));$i++) 
                {
                    $nb_actes_aff = $bdd->exec(" UPDATE acte set id_tome_registre = $lots_id_tome_registre[$i] 
                                            where id_acte in (
                                                    SELECT id_acte 
                                                    from acte 
                                                    where id_tome_registre = $lots_id_tome_registre[0]  
                                                    order by id_acte asc
                                                    limit $nb_limit 
                                                );");
                
                    $list_lots_lot .= $lots_id_lot[$i]." : $nb_actes_aff ,";
                }

                $result[] = $list_lots_lot;
            }
            else
            {
                $result[] = $lot->id_lot.' : deja_diviser';
            }                        
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