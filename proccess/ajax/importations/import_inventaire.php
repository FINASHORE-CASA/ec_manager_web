<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;
        
        foreach($formData as $dt)
        {
            $qry = $bdextra->prepare("SELECT COUNT(*) FROM lotstats WHERE lot = ?");
            $qry->execute(array($dt->lot));
            $lot_stats = $qry->fetch()[0];

            if($lot_stats == 0)
            {
                $qry = $bdextra->prepare("INSERT into lotstats (id_bec,lot,tome,indice,g,h,naissance,deces,mariage,divorce,total) 
                                          values(?,?,?,?,?,?,?,?,?,?,?);");
                $isAff = $qry->execute(array($dt->id_bec,$dt->lot,$dt->tome,$dt->indice,$dt->annee_g,$dt->annee_h,$dt->naissance,$dt->deces,$dt->mariage
                ,$dt->divorce,$dt->total));

                if($isAff == true) 
                {
                    $result[] = $dt->lot." inserted";
                }
                else
                {
                    $result[] = $dt->lot." fail";        
                }                                    
            }
            else
            {
                $result[] = $dt->lot." already";        
            }
        }

        echo(json_encode($result));        
    }
    catch(Exception $e)
    {
        $fail[0] = "fail";
        $fail[1] = $e->getMessage();
        echo(json_encode($fail));
    }
?>