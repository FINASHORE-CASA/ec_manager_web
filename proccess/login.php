<?php
    session_start();

    try
    {
        require_once "../config/checkConfig.php";
    }
    catch(Exception $e)
    {
        header("location: ../login.php?log=Problème de connexion à la Base de Données"); 
        die();
    }

    if(isset($_POST['login']) && isset($_POST['mot_de_passe']))
    {   
        // Récupération de l'acte concerné
        $qry = $bdextra->prepare("  SELECT name,first_name,type_grant,date_creat,date_last_up,login,password,id_user
                                    from mg_user where login = ? and password = ?");

        $qry->execute(array($_POST['login'],$_POST['mot_de_passe']));
        $users = $qry->fetchAll(PDO::FETCH_OBJ);  

        if(count($users) == 1)
        {                        
            $_SESSION['user'] = $users[0]; 
            header('Location: ../index.php');     
        }
        else if(count($users) > 0)
        {
            header('Location: ../login.php?log=Login double, contacter l\'Administateur');     
        }
        else
        {
            header('Location: ../login.php?log=Login ou Mot de passe incorrecte ');     
        }    
    }
    else
    {
        header('Location: ../login.php?log=Veuillez bien renseigner les champs');     
    }   