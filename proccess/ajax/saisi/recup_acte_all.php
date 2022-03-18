<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération de l'acte concerné
        // $qry = $bdd->prepare("  SELECT af.id_lot,a.*
        //                         from acte a  
        //                         inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                         where id_acte = $formData->id_acte");

        // $qry->execute();
        // $acte = $qry->fetch(PDO::FETCH_OBJ);  

        // Récupération du décès
        $qry = $bdd->prepare("  SELECT *
                                from deces
                                where id_acte = $formData->id_acte");

        $qry->execute();
        $deces = $qry->fetch(PDO::FETCH_OBJ);  
                
        // Récupération de la déclaration
        $qry = $bdd->prepare("  SELECT *
                                from jugement
                                where id_acte = $formData->id_acte");

        $qry->execute();
        $jugement = $qry->fetch(PDO::FETCH_OBJ);  
        
        // Récupération du Chemin
        $BaseCheminLot = getPathLot($formData->id_lot,$bdextra);
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
        // $chemin = getPathLot($acte->id_lot,$bdextra,$Base_Folder);    
        $chemin = $cheminLotSource ;
        $image = $formData->imagepath;
        $isCopy = "no";    

        if(strpos($image, ';;') !== false)
        {   
            $images = explode(";;",$formData->imagepath);

            foreach($images as $img)
            {
                $src = $chemin."\\".$img;
                $dest = $Base_url."\\".$Temp_folder."\\".$formData->id_acte."_".$img;

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
        else
        {   
            $src = $chemin."\\".$image;
            $dest = $Base_url."\\".$Temp_folder."\\".$formData->id_acte."_".$image;

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
                                              
        $result[] = $formData;                                                  
        $result[] = $isCopy;                                                      
        $result[] = $Temp_folder; 
        $result[] = (isset($deces)) ? $deces : [];                                                                                                       
        $result[] = (isset($jugement)) ? $jugement : [];                                                                                                       

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>