<?php
$liste_regions = array (
    "Alsace" => array(67,68),
    "Aquitaine" => array(24,33,40,47,64),
    "Auvergne" => array ("03",15,43,63),
    "Basse-Normandie" => array (14,50,61),
    "Bourgogne" => array (21,58,71,89),
    "Bretagne" => array (22,29,35,56),
    "Centre" => array (18,28,36,37,41,45),
    "Champagne-Ardenne" => array ("08",10,51,52),
    "Corse" => array(20),
    "DOM-TOM" => array(97),
    "Franche-Comté" => array (25,39,70,90),
    "Haute-Normandie" => array (27,76),
    "Ile de France" => array(75,77,78,91,92,93,94,95),
    "Languedoc-Roussillon" => array(11,30,34,48,66),
    "Limousin" => array(19,23,87),
    "Lorraine" => array (54,55,57,88),
    "Midi-Pyrénées" => array("09",12,31,32,46,65,81,82),
    "Nord / Pas-de-Calais" => array(59,62),
    "Pays de la Loire" => array (44,49,53,72,85),
    "Picardie" => array ("02",60,80),
    "Poitou-Charentes" => array (16,17,79,86),
    "PACA" => array("04","05","06",13,83,84),
    "Rhône-Alpes" => array ("01","07",26,38,42,69,73,74)
);
// Renvoie la région à partir du code postal ou du numéro de département

function region($codepostal)
{

    global $liste_regions;

    $departement = substr($codepostal,0,2);

    foreach($liste_regions as $region => $liste_dep)
    {

        if (in_array($departement, $liste_dep))
        {
            return $region;
        }
    }
}

function ShowDepts($name, $selected='',$onchange='')
{
    echo '<select name="'.$name.'"'.($onchange?' onchange="document.'.$onchange.'.submit();"':'').'>
        <option value="">France</a>';

    global $liste_regions;

    foreach($liste_regions as $region => $liste_dep)
    {
        echo '<optgroup label="'.$region.'">';

        for($i=0;$i<count($liste_dep);$i++)
        {
            echo '<option value="'.$liste_dep[$i].'"'.($selected==$liste_dep[$i]?' selected="selected"':'').'>'.$liste_dep[$i].'</option>';
        }
        echo '</optgroup>';
    }
    echo '</select>';
}