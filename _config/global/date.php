<?php

function RelativeDate($date)
{
	if(!$date) return;
	
	$time=@strtotime($date);
	if(!$time) return;
	
	$dR = 'Il y a ';
	
	$now=time();
	$delta=$now-$time;
	
	if($delta<10) $dR.='quelques secondes';
	elseif($delta<60) $dR.='moins d\'1 minute';
	elseif($delta<3600) $dR.=ceil($delta/60) . ' minutes';
	elseif($delta<86400) $dR.=ceil($delta/3600) . ' heures';
	else $dR.=ceil($delta/86400) . ' jours';

	
	return $dR;
}

function DiffInSeconds($heureDebut, $heureFin) {
    $hF = substr($heureFin, 0, 2) * 3600;
    $mF = substr($heureFin, 3, 2) * 60;
    $hD = substr($heureDebut, 0, 2) * 3600;
    $mD = substr($heureDebut, 3, 2) * 60;
    $diff = ($hF + $mF) - ($hD + $mD);
    return $diff;
}

function GetNumJour($code) {
    switch ($code) {
        case 'LU':
            return 1;
        case 'MA':
            return 2;
        case 'ME':
            return 3;
        case 'JE':
            return 4;
        case 'VE':
            return 5;
        case 'SA':
            return 6;
        case 'DI':
            return 7;
    }
}

function FormatDate($date) {
    return substr($date, 8, 2) . '/' . substr($date, 5, 2) . '/' . substr($date, 0, 4) . substr($date, 10);
}

function SetHeureFin($heure_debut) {
    if (substr($heure_debut, 3, 2) == '00') {
        return substr($heure_debut, 0, 3) . '30';
    } else {
        $h = parseInt(substr($heure_debut, 0, 2)) + 1;
        $h = ($h < 10 ? '0' : '') . $h;
        return $h . ':00';
    }
}

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
function parseInt($string) {
//	return intval($string);
    if (preg_match('/(\d+)/', $string, $array)) {
        return $array[1];
    } else {
        return 0;
    }
}

////////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
function FileName($filename) {
    $filename = strtolower($filename);
    $filename = str_replace("é", "e", $filename);
    $filename = str_replace("è", "e", $filename);
    $filename = str_replace("ë", "e", $filename);
    $filename = str_replace("e", "e", $filename);
    $filename = str_replace("à", "a", $filename);
    $filename = str_replace("â", "a", $filename);
    $filename = str_replace("î", "i", $filename);
    $filename = str_replace("ï", "i", $filename);
    $filename = str_replace("ô", "o", $filename);
    $filename = str_replace("ù", "ù", $filename);
    $filename = str_replace("  ", "_", $filename); //2 espaces = 1 underscode
    $filename = str_replace(" ", "-", $filename);
    for ($i = 0; $i < strlen($filename); $i++) {
        switch (substr($filename, $i, 1)) {
            case "a":
            case "b":
            case "c":
            case "d":
            case "e":
            case "f":
            case "g":
            case "h":
            case "i":
            case "j":
            case "k":
            case "l":
            case "m":
            case "n":
            case "o":
            case "p":
            case "q":
            case "r":
            case "s":
            case "t":
            case "u":
            case "v":
            case "w":
            case "x":
            case "y":
            case "z":
            case "0":
            case "1":
            case "2":
            case "3":
            case "4":
            case "5":
            case "6":
            case "7":
            case "8":
            case "9":
            case "-":
            case "_":
            case ".":
                break;
            default:
                $filename = substr($filename, 0, $i - 1) . '-' . substr($filename, $i + 1);
                break;
        }
    }
    return $filename;
}
