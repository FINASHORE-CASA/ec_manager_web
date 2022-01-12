<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT id_lot from lot where status_lot = 'A'");

        $qry->execute();
        $liste_lots = $qry->fetchAll(PDO::FETCH_OBJ);

        $liste_el_select;
        $nbLot = 0;

        foreach ($liste_lots as $lot) 
        {
            $cheminLot = getFullPathLot($lot->id_lot, $bdextra,$ListPathImages);
            
            if (!empty($cheminLot) && is_dir($cheminLot))
            {
                $liste_files = scandir($cheminLot);  
                $findPO =  array_filter($liste_files, function($e){
                    return str_contains($e,"PO");
                });        

                $findPF =  array_filter($liste_files, function($e){
                    return  str_contains($e,"PF") ;
                });         

                if(count($findPO) == 0)
                {
                    $findPO = array_values($findPO);
                    $ImgVoid = "C:\\laragon\\www\\ec_manager_web\\img\\img_vide.jpg";
                    copy($ImgVoid,$cheminLot."\\PO_P1.jpg");            
                }  

                if(count($findPF) == 0)
                {
                    $findPF = array_values($findPF);
                    $ImgVoid = "C:\\laragon\\www\\ec_manager_web\\img\\img_vide.jpg";
                    copy($ImgVoid,$cheminLot."\\PF_P1.jpg");                
                }     
				
				if(count($findPF) == 0 || count($findPO) == 0)
                {
					$nbLot++;
					$liste_el_select[] = ["id_lot" => $lot->id_lot,"nb_po" => count($findPO) , "nb_pf" => count($findPF),"chemin_lot" => $cheminLot];
				}
            }
            else
            {
                // Enregistrement de la ligne dans le fichier Csv
                $nbLot++;
                $liste_el_select[] = ["id_lot" => $lot->id_lot,"nb_po" => "0" , "nb_pf" => "0","chemin_lot" => 'REPERTOIRE INTROUVABLE'];
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