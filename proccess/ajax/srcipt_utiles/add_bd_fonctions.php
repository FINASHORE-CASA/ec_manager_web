<?php

session_start();
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    // Ajout de la fonction try_cast_int à la base de données
    $nbAff1 = $bdd->exec("CREATE function try_cast_int(p_in text, p_default int default null)
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

    // activation des extensions
    $nbAff2 = $bdd->exec("CREATE EXTENSION dblink; ");

    // sur la base de données extra
    // Ajout de la fonction try_cast_int à la base de données
    $nbAff1 = $bdextra->exec("CREATE function try_cast_int(p_in text, p_default int default null)
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

    // activation des extensions
    $nbAff2 = $bdextra->exec("CREATE EXTENSION dblink; ");

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
