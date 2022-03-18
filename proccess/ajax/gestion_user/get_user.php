<?php
    // Require des données
    require_once "../../../config/checkConfig.php";       

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération de l'acte concerné
        $qry = $bdextra->prepare("  SELECT name,first_name,type_grant,date_creat,date_last_up,login,password,id_user
                                    from mg_user 
                                    where id_user = ?");

        $qry->execute(array($formData->id_user));
        $user = $qry->fetch(PDO::FETCH_OBJ);  
                                                   
        $result[] = $user;                                                                                                        

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>