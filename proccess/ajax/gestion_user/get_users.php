<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération de l'acte concerné
        $qry = $bdextra->prepare("  SELECT name,first_name,type_grant, to_char(date_creat,'DD-MM-YYYY HH:MI:SS AM') as date_creat, to_char(date_last_up,' DD-MM-YYYY HH:MI:SS AM') as date_last_up,login,password,id_user
                                    from mg_user");

        $qry->execute();
        $users = $qry->fetchAll(PDO::FETCH_OBJ);  
                                                   
        $result[] = $users;                                                                                                        

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>