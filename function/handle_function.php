<?php

function var_dumper($variable,$die)
{
    highlight_string("<?php\n\$data =\n" . var_export($variable,true) . ";\n?>");
    if($die == true)
        die();
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