<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération de l'acte concerné
        $qry = $bdd->prepare(" SELECT id_lot,'PO_P1.jpg;;PF_P1.jpg' as imagepath,tm.* 
                               from tomeregistre tm 
                               inner join affectationregistre af on af.id_tome_registre = tm.id_tome_registre
                               where tm.id_tome_registre = $formData->id_tome_registre ");

        $qry->execute();
        $tomeregistre = $qry->fetch(PDO::FETCH_OBJ);  
        
        // Récupération du Chemin
        $BaseCheminLot = getPathLot($tomeregistre->id_lot,$bdextra);
        $cheminLotSource = "";

        $SourceTable = $ListPathImages;

        foreach($SourceTable as $src)
        {
            if(is_dir(trim($src)."\\".$BaseCheminLot))
            {
                $cheminLotSource = trim($src)."\\".$BaseCheminLot;
                break;
            }
        }

        // copie de l'image vers le repertoire d'affichage     
        $chemin = $cheminLotSource ;
        $image = "PO_P1.jpg;;PF_P1.jpg";
        $isCopy = "no";    

        if(strpos($image, ';;') !== false)
        {   
            $images = explode(";;",$image);

            foreach($images as $img)
            {
                $src = $chemin."\\".$img;
                $dest = $Base_url."\\".$Temp_folder."\\".$tomeregistre->id_lot."_".$img;

                if(trim($img) != "")
                {
                    if(!file_exists($dest))
                    {    
                        if(file_exists($src))
                        {
                            if(copy($src,$dest))
                            {
                                $isCopy = "yes";
                            }
                            else
                            {
                                $isCopy = "no";
                            }
                        }    
                        else
                        {
                            $isCopy = "no";
                        }    
                    }  
                    else
                    {
                        $isCopy = "yes";
                    }  
                }
            }
        }
                                              
        $result[] = $tomeregistre;                                                  
        $result[] = $isCopy;                                                      
        $result[] = $Temp_folder;                                                                                                       

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>