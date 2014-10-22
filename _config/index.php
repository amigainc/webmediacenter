<?php

ini_set('session.use_trans_sid', 0);
ini_set('session.use_only_cookies', 1);
$rep = 'C:\Program Files\EasyPHP-DevServer-14.1VC9\data\localweb\_config\global/';

if ($handle = opendir($rep)) {
    /* Ceci est la fa�on correcte de traverser un dossier. */

    while (false !== ($file = readdir($handle))) {

        if (!is_dir($file) && $file != 'index.php' && substr($file, -4, 4) == ".php")
            include $rep . $file;
    }
    closedir($handle);
}

//Visites
$conX = @mysql_connect("mysql51-37.perso", "xncartoanalyse", "bGdtcaN8");

if (isset($_REQUEST['nolog']))
    $_SESSION['nolog'] = 1;

if ($conX) {

    mysql_select_db('xncartoanalyse', $conX);

    $URL = selfURL();

    if (isset($_SERVER['HTTP_REFERER'])) {
        $ref = parse_url($_SERVER['HTTP_REFERER']);
        $host = str_replace('www.', '', $ref['host']);
        //echo 'Vous arrivez de <b>'.$host.'</b><hr/><br/>';
    }

    /*if ($_SERVER['SERVER_NAME'] && strpos($URL, 'aurelien-stride.fr/logs') === false && !isset($_SESSION['nolog'])) {
        $query = "SELECT * FROM visites WHERE date_visite='" . date('Y-m-d') . "' and url='" . $URL . "'";
        $result = mysql_query($query, $conX);
        if (mysql_num_rows($result) == 0) {
            mysql_query("INSERT INTO visites(date_visite, site, url) VALUES ('" . date('Y-m-d') . "','" . $_SERVER['SERVER_NAME'] . "','" . $URL . "')", $conX);
            $result = mysql_query($query, $conX);
        }
        mysql_query("UPDATE visites SET nb_visites=nb_visites+1 WHERE date_visite='" . date('Y-m-d') . "' and url='" . $URL . "'", $conX);


        $result = mysql_query("select * from provenances where url='" . $URL . "'", $conX);
        if (mysql_num_rows($result) == 0) {
            mysql_query("insert into provenances (url) values ('" . $URL . "')", $conX);
            $result = mysql_query("select * from provenances where url='" . $URL . "'", $conX);
        }
        mysql_query("update provenances set provs=concat(provs,';','" . $host . "') where url='" . $URL . "'", $conX);
    }*/


    mysql_close($conX);
}

//MAJ visite
function selfURL() {
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/") . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) {
    return substr($s1, 0, strpos($s1, $s2));
}