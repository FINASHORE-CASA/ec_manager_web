<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("SELECT id_lot from lot where status_lot='A' ");

        $qry->execute();
        $listLots = $qry->fetchAll(PDO::FETCH_OBJ);

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("SELECT af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte  
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in (select id_lot from lot where status_lot = 'A')    
                                order by af.id_lot");

        $qry->execute();
        $listeLots_bd = $qry->fetchAll(PDO::FETCH_OBJ);
        $NbRowError = 0;
        $NbCorrige = 0;

        function orderActe($a, $b) 
        {
            return $a["id_acte"] > $b["id_acte"];
        }

        foreach ($listLots as $lot) 
        {
            $ActeLotDb = array_values(array_filter($listeLots_bd,function($e) use($lot){ return $e->id_lot == $lot->id_lot ;}));
            $ActeIntrouvable = [];
            //Récupération du répertoire
            $repChemin =  getFullPathLot($lot->id_lot, $bdextra,$ListPathImages);

            if (!empty($repChemin) && is_dir($repChemin)) 
            {
                $listImageDirectories = array_values(array_filter(scandir($repChemin),function($e){ return ($e != "." && $e != ".." && !str_contains($e,"PO") && !str_contains($e,"PF") && str_contains($e,".tif"));}));
                
                // checks des images 
                foreach ($listImageDirectories as $img)
                {
                    $acteLot = array_values(array_filter($ActeLotDb,function($e) use($img){ return str_contains($e->imagepath,$img);}));   
                    if (count($acteLot) == 0)
                    {
                        $NbRowError++;
                        $ActeIntrouvable[] = ["id_lot" => $lot->id_lot,"id_acte" => 0,"num_acte" => explode(".",$img)[0], "imagepath" => ($repChemin."\\".$img)];
                    }
                }
                
                // if(count($ActeIntrouvable) > 0) var_dumper($ActeIntrouvable,true);
                // else continue;

                // checks des actes
                foreach ($ActeLotDb as $act)
                {
                    $listeImageActe = explode(";",trim(str_replace(";;", ";",$act->imagepath)));
                    
                    foreach($listeImageActe as $ImgAct)
                    {
                        if(!empty($ImgAct))
                        {
                            if(!is_file($repChemin."\\".$ImgAct))
                            {
                                $NbRowError++;
                                $ActeIntrouvable[] = ["id_lot" => $lot->id_lot,"id_acte" => $act->id_acte,"num_acte" => $act->num_acte, "imagepath" => $ImgAct];
                            }
                        }
                    }
                }

                usort($ActeIntrouvable, "orderActe");  

                foreach($ActeIntrouvable as $acte)
                {
                    if($acte["id_acte"] == 0)
                    {                                        
                        $num_acte_img = str_replace("_P2", "",str_replace("_P1", "",str_replace("DE-", "",str_replace(".jpeg", "",str_replace(".jpg","",str_replace("NA-", "",$acte["num_acte"]))))));

                        $int_value = ctype_digit($num_acte_img) ? intval($num_acte_img) : null;
                        $num_acte_img_int = $int_value;
                        if ($num_acte_img_int != null && $num_acte_img_int < 10)
                        {
                            // Récupération de l'image possible
                            $act_found = array_values(array_filter($ActeIntrouvable,function($e) use($num_acte_img_int) { return ($e["id_acte"] != 0 && $e['num_acte'] == ($num_acte_img_int."0") && str_contains($e['imagepath'],($num_acte_img_int."0")));}));
                            if(count($act_found) > 0)
                            {
                                // Rénommage de l'image concerné !!!
                                $newFileName = str_contains($acte["imagepath"],"NA") ? "NA-".($num_acte_img_int."0") : "DE-".($num_acte_img_int."0");
                                $newFileName .= str_contains($acte["imagepath"],"_P1") ? "_P1.jpg" : (str_contains($acte["imagepath"],"_P2.jpg") ? "_P2" : ".jpg");
                                $nbs = explode("\\",$acte["imagepath"]);
                                $newFileFullName = ($acte["imagepath"].'')[strlen($acte["imagepath"]) - strlen($nbs[(count($nbs) -1)])].$newFileName;
                                if(!is_file($newFileFullName))
                                {
                                    if(is_file($acte["imagepath"]))
                                    {
                                        copy($acte["imagepath"],$newFileFullName);                                    
                                        $liste_el_select[] = ["id_lot" => $lot->id_lot,"id_acte" => "ND" , "num_acte" => ($num_acte_img_int + "0")
                                        ,"imagepath" =>  $acte["num_acte"],"chemin_dossier" =>  $repChemin ,"observation" => 'CORRIGE (IMAGE RENOMMEE)'];  
                                        $NbCorrige++;
                                    }
                                    else
                                    {                                        
                                        $liste_el_select[] = ["id_lot" => $lot->id_lot,"id_acte" => "ND" , "num_acte" => ($num_acte_img_int + "0")
                                        ,"imagepath" =>  $acte["num_acte"],"chemin_dossier" =>  $repChemin ,"observation" => 'ERREUR CHEMIN IMAGE'];  
                                    }
                                }
                                else
                                {
                                    $liste_el_select[] = ["id_lot" => $lot->id_lot,"id_acte" => "ND" , "num_acte" => ($num_acte_img_int + "0")
                                    ,"imagepath" =>  $acte["num_acte"],"chemin_dossier" =>  $repChemin ,"observation" => 'AUCUN ACTE (IMAGE EXISTANTE)'];
                                }
                            }
                            else
                            {
                                $liste_el_select[] = ["id_lot" => $lot->id_lot,"id_acte" => "NULL" , "num_acte" => "NULL"
                                ,"imagepath" =>  $acte["num_acte"],"chemin_dossier" =>  $repChemin ,"observation" => 'AUCUN ACTE'];
                            }
                        }
                        else
                        {
                            $liste_el_select[] = ["id_lot" => $lot->id_lot,"id_acte" => "NULL" , "num_acte" => "NULL"
                            ,"imagepath" =>  $acte["num_acte"],"chemin_dossier" =>  $repChemin ,"observation" => 'AUCUN ACTE'];
                        }
                    }
                    else
                    {
                        $listeImageActe = explode(";",str_replace(";;", ";",Trim($acte["imagepath"])));

                        foreach ($listeImageActe as $ImgAct)
                        {
                            if (!empty($ImgAct))
                            {
                                if (!is_file($repChemin."\\".$ImgAct))
                                {
                                    $NbRowError++;                                 
                                    $liste_el_select[] = ["id_lot" => $lot->id_lot,"id_acte" => $acte["id_acte"] , "num_acte" => $acte["num_acte"]
                                    ,"imagepath" =>  $ImgAct,"chemin_dossier" =>  $repChemin ,"observation" => 'AUCUNE IMAGE'];
                                }
                            }
                        }
                    }
                }              
            }
            else
            {              
                $NbRowError++;                                 
                $liste_el_select[] = ["id_lot" => $lot->id_lot,"id_acte" => "NULL" , "num_acte" => "NULL"
                ,"imagepath" =>  "NULL","chemin_dossier" =>  "NULL" ,"observation" => 'REPERTOIRE INTROUVABLE'];  
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