<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        $nbAff1 =   $bdd->exec("DELETE from transcription where id_acte in (
                    SELECT id_acte
                        from acte a  
                        left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                        and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                        where  af.id_tome_registre is null)");

        $nbAff2 =   $bdd->exec("DELETE FROM controle_mention WHERE id_mention in 
                    (select id_mention from mention where id_acte in 
                        (SELECT id_acte
                            from acte a  
                            left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                            where  af.id_tome_registre is null);");

        $nbAff3 =   $bdd->exec("DELETE from mention where id_acte in (
                    SELECT id_acte
                        from acte a  
                        left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                        and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                        where  af.id_tome_registre is null)");

        $nbAff4 =   $bdd->exec("DELETE from deces where id_acte in (
                    SELECT id_acte
                        from acte a  
                        left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                        and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                        where  af.id_tome_registre is null)");

        $nbAff5 =   $bdd->exec("DELETE from jugement where id_acte in (
                    SELECT id_acte
                        from acte a  
                        left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                        and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                        where  af.id_tome_registre is null)");

        $nbAff6 =   $bdd->exec("DELETE from controle_acte where id_acte in (
                    SELECT id_acte
                        from acte a  
                        left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                        and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                        where  af.id_tome_registre is null)");        

        $nbAff7 =   $bdd->exec("DELETE from declaration where id_acte in (
                    SELECT id_acte
                        from acte a  
                        left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                        and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                        where  af.id_tome_registre is null)");

        $nbAff8 =   $bdd->exec("DELETE FROM acte WHERE id_acte in (
                        SELECT id_acte
                        from acte a  
                        left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                        and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                        where  af.id_tome_registre is null)");
                                 
        $result[] = "transcription : ".$nbAff1.",controle_mention : ".$nbAff2.",mention : ".$nbAff3.", deces : ".$nbAff4.",jugement : ".$nbAff5.",controle_acte : ".$nbAff6.",declaration : ".$nbAff7.",acte : ".$nbAff8;                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>