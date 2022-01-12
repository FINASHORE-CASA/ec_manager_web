<?php

function var_dumper($variable,$die=false)
{
    highlight_string("<?php\n\$data =\n" . var_export($variable,true) . ";\n?>");
    if($die == true)
        die();
}   

function str_contains(string $haystack, string $needle)
{
    return (strpos($haystack, $needle) !== false);
}

function shortText($text,$lgLimit)
{
    if(strlen($text) > $lgLimit)
    {
        return substr($text,0,($lgLimit - 3))."...";
    }
    else{
        return $text;
    }
}

function GetDirectorySize($path)
{
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path))
    {
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object)
        {
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}

function rcopy($src, $dst) 
{
  if(!is_dir($dst)) mkdir($dst,0777,true);

  if (is_dir($src)) 
  {          
    $files = scandir($src);
    foreach ($files as $file)
    {
        if ($file != "." && $file != "..") 
        {
            if(!file_exists("$dst/$file"))
            {
                copy("$src/$file", "$dst/$file");
            }
        }
    }
  }
}

// fonction d'énumération de prix
function EnumPrix($prix)
{
    $longPrix = strlen($prix);

    switch ($longPrix) {
        case 4:
                return $prix[0].' '.$prix[1].''.$prix[2].''.$prix[3];
            break;
        case 5:
                return $prix[0].''.$prix[1].' '.$prix[2].''.$prix[3].''.$prix[4];
            break;
        case 6:
                return $prix[0].''.$prix[1].''.$prix[2].' '.$prix[3].''.$prix[4].''.$prix[5];
            break;
        case 7:
                return $prix[0].' '.$prix[1].''.$prix[2].''.$prix[3].' '.$prix[4].''.$prix[5].''.$prix[6];
            break;
        case 8:
                return $prix[0].''.$prix[1].' '.$prix[2].''.$prix[3].''.$prix[4].' '.$prix[5].''.$prix[6].''.$prix[7];
            break;
        case 9:
                return $prix[0].''.$prix[1].''.$prix[2].' '.$prix[3].''.$prix[4].''.$prix[5].' '.$prix[6].''.$prix[7].''.$prix[8];
            break;
        default:
                return $prix;
            break;
    }
} 

function getPathLot($idLot, $bdextra,$basePath=null)
{
    try
    {
        $idLot = ($idLot.'');
        // récupération du chemin du lot 
        // formatage des informations
        // exemple format -- '1 2012 001 2403 03'
        switch ($idLot[0]) {
            case '1':
                $typlot = "NA";
                break;
            case '2':
                $typlot = "DE";
                break;
            case '3':
                $typlot = "JM";
                break;
            case '4':
                $typlot = "TR";
                break;               
            default:
                $typlot = "ER";
                break;  
        }

        $annee = $idLot[1].''.$idLot[2].''.$idLot[3].''.$idLot[4];
        $tome = $idLot[5].$idLot[6].$idLot[7];
        $idbec = $idLot[8].$idLot[9].$idLot[10].$idLot[11];
        $indice = $idLot[12];
        $idcom = "";
        $tome_indice = ($indice == "0") ? intval($tome) : intval($tome)."_".$indice;

        // récupération du com
        $qry = $bdextra->prepare('SELECT id_com from match_idbec where id_bec = '.$idbec);

        $qry->execute();
        $idcom = $qry->fetch()[0];  

        // formatage du chemin et retour
        // $PathImages = $annee;
        if($basePath != null)
        {
            $PathImages = $basePath."\\".$idcom."\\".$idbec."\\".$annee."\\".$typlot."\\".$tome_indice;
        }
        else
        {
            $PathImages = $idcom."\\".$idbec."\\".$annee."\\".$typlot."\\".$tome_indice;
        }
        
        return $PathImages;
    }
    catch (Exception $ex)
    {
        return "";
    }
}

function getFullPathLot($idLot, $bdextra,$ListPathImages)
{
    $relativePath = getPathLot($idLot,$bdextra);

    $Fullpath = "";

    // formatage du chemin et retour
    foreach($ListPathImages as $basePath)
    {
        if (is_dir($basePath."\\".$relativePath))
        {
            $Fullpath = $basePath."\\".$relativePath;
            break;
        }
    }

    return $Fullpath;
}



