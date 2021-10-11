<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération de l'acte concerné
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,nom_fr,prenom_fr,nom_ar,prenom_ar
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where id_acte = $formData->id_acte");

        $qry->execute();
        $acte = $qry->fetch(PDO::FETCH_OBJ);  

        // copie de l'image vers le repertoire d'affichage   
        $chemin = getPathLot($acte->id_lot,$bdextra,$Base_Folder);    
        $image = $acte->imagepath;
        $isCopy = "no";    

        if(strpos($image, ';;') !== false)
        {   
            $images = explode(";;",$acte->imagepath);

            foreach($images as $img)
            {
                $src = $chemin."\\".$img;
                $dest = $Base_url."\\".$Temp_folder."\\".$acte->id_acte."_".$img;

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
            $dest = $Base_url."\\".$Temp_folder."\\".$acte->id_acte."_".$image;

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
                                              
        $result[] = $acte;                                                  
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