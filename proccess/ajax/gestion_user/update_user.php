<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {        
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;
        
        $qry = $bdextra->prepare("SELECT count(id_user) from mg_user where login = ? and id_user <> ?");
        $qry->execute(array($formData->login,$formData->id_user));
        $user_exist = $qry->fetch();  

        if ($user_exist[0] == 0) 
        {
            //Update Users
            $qry = $bdextra->prepare("UPDATE mg_user SET name = ?,first_name = ?,type_grant = ?,date_last_up = NOW(),login = ?,password = ? where id_user = ?");
            $qry->execute(array($formData->name,$formData->first_name,$formData->type_grant,$formData->login,$formData->password,$formData->id_user));
            
            // Récupération de l'acte concerné
            $qry = $bdextra->prepare("  SELECT name,first_name,type_grant,date_creat,date_last_up,login,password,id_user
                                        from mg_user where id_user = ?");

            $qry->execute(array($formData->id_user));
            $user = $qry->fetch(PDO::FETCH_OBJ);

            $result[] = $user;   
        } 
        else
        {
            $result[0] = "login_find";
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