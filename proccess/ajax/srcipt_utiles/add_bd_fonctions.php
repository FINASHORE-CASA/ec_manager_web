<?php

    session_start();
    // Require des données
    require_once "../../../config/checkConfig.php";  

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Ajout de la fonction try_cast_int à la base de données
        $nbAff_deces = $bdd->exec("CREATE function try_cast_int(p_in text, p_default int default null)
                                    returns int
                                    as
                                    $$
                                    begin
                                    begin
                                        return $1::int;
                                    exception 
                                        when others then
                                        return p_default;
                                    end;
                                    end;
                                    $$
                                    language plpgsql;    
                                ");  

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>