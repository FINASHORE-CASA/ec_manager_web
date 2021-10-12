<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {        
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        $qry = $bdextra->prepare("SELECT count(id_user) from mg_user where login = ?");
        $qry->execute(array($formData->login));
        $user_exist = $qry->fetch();  

        if($user_exist[0] == 0)
        {
            //Add User
            $qry = $bdextra->prepare("INSERT into mg_user (name,first_name,type_grant,date_creat,date_last_up,login,password) 
                                    values(?,?,?,NOW(),NOW(),?,?)");
            $qry->execute(array($formData->name,$formData->first_name,$formData->type_grant,$formData->login,$formData->password));                                                                                                                                                                
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