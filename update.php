<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
include '_display.php';

//MAJ affiches des séries
$query = "UPDATE films_films ff 
INNER JOIN films_films ff2 ON MID(ff2.nom_film,1, length(ff2.nom_film)-6)=MID(ff.nom_film,1, length(ff.nom_film)-6)
SET ff.affiche= ff2.affiche WHERE ff.affiche='' AND ff2.affiche!='' AND ff.nom_film LIKE '% - S%E%' ";
$result = mysql_query($query);
echo mysql_error();

//Suppression des fichiers manquants
$query = "SELECT id_film, chemin_film, nom_film FROM films_films
	ORDER BY chemin_film ASC";
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
	if(!file_exists(Encoding::toLatin1($row['chemin_film']))) {
		echo '<span style="color:red">'.$row['chemin_film'].'</span><br/>';
		mysql_query("DELETE FROM films_films WHERE id_film=".$row['id_film']);
	}
	else {
		//echo '<span style="color:darkgreen">'.$row['chemin_film'].'</span><br/>';
	}
}

$query = "SELECT id_film, url, nom_film FROM films_films
	WHERE id_film NOT IN (SELECT DISTINCT id_film FROM films_acteur_film ORDER BY id_film ASC)
	OR affiche='' OR affiche IS NULL
	ORDER BY id_film DESC";
$result = mysql_query($query);
while ($row = mysql_fetch_array($result)) {

	echo '<h2>'.$row['nom_film'].'</h2>';
	echo '<a href="'.$row['url'].'">Voir la fiche</a><br/>';
	
    $file2 = url_get_contents($row['url']);
    $file2 = substr($file2, strpos(strtolower($file2), '<body'));
    if (strrpos(strtolower($file2), '</body>') !== false)
        $file2 = substr($file2, 0, strrpos(strtolower($file2), '</body>') + 7);

//echo '<textarea>'.$file2.'</textarea>';

    $doc = phpQuery::newDocument($file2);


    //mysql_query("DELETE FROM films_genre_film WHERE id_film=" . $row['id_film']);

    foreach ($doc['#col_main p[itemprop="description"]'] as $nom) {
		if(trim(pq($nom)->text())=='')
			mysql_query("UPDATE films_films SET synopsis='".mysql_real_escape_string(trim(pq($nom)->next()->text()))."' WHERE id_film='".$row['id_film']."'");
		else
			mysql_query("UPDATE films_films SET synopsis='".mysql_real_escape_string(trim(pq($nom)->text()))."' WHERE id_film='".$row['id_film']."'");
		break;
	} 
	
	foreach ($doc['.poster img'] as $nom) {
		$src=pq($nom)->attr('src');
		mysql_query("UPDATE films_films SET affiche='".mysql_real_escape_string($src)."' WHERE (affiche='' OR affiche IS NULL) AND id_film='".$row['id_film']."'");
		break;
	}
	
	
	
    foreach ($doc['span[itemprop="genre"]'] as $nom) {
        $r = mysql_query("SELECT * FROM films_genres WHERE nom_genre='" . mysql_real_escape_string(pq($nom)->text()) . "'");
        $r2 = mysql_fetch_array($r);
        if (!$r2) {
            mysql_query("INSERT INTO films_genres (nom_genre) VALUES ('" . mysql_real_escape_string(pq($nom)->text()) . "')");
            $r = mysql_query("SELECT * FROM films_genres WHERE nom_genre='" . mysql_real_escape_string(pq($nom)->text()) . "'");
            $r2 = mysql_fetch_array($r);
        }
        echo $r2['nom_genre'].' ; ';
        mysql_query("INSERT IGNORE INTO films_genre_film VALUES (" . $row['id_film'] . ',' . $r2['id_genre'] . ')');
    }

    /*
     * Acteurs
     */

    //mysql_query("DELETE FROM films_acteur_film WHERE id_film=" . $row['id_film']);

    
    //mysql_query("DELETE FROM films_nationalite_film WHERE id_film=" . $row['id_film']);
    foreach ($doc['span[itemprop="director"], span[itemprop="creator"], table.data_box_table margin_10b tr:eq(1) a.xXx'] as $nom) {
        $r = mysql_query("SELECT * FROM films_reals WHERE nom_real='" . mysql_real_escape_string(pq($nom)->text()) . "'");
        $r2 = mysql_fetch_array($r);
        if (!$r2) {
            mysql_query("INSERT INTO films_reals (nom_real) VALUES ('" . mysql_real_escape_string(pq($nom)->text()) . "')");
            $r = mysql_query("SELECT * FROM films_reals WHERE nom_real='" . mysql_real_escape_string(pq($nom)->text()) . "'");
            $r2 = mysql_fetch_array($r);
        }
        echo $r2['nom_real'].' ; ';
        mysql_query("INSERT IGNORE INTO films_real_film VALUES (" . $row['id_film'] . ',' . $r2['id_real'] . ')');
    }
	
	//Nationalités (ne fonctionne pas)
	foreach ($doc['table.data_box_table margin_10b tr:eq(4) td span'] as $nom) {
        $r = mysql_query("SELECT * FROM films_nationalites WHERE nom_nat='" . mysql_real_escape_string(pq($nom)->text()) . "'");
        $r2 = mysql_fetch_array($r);
        if (!$r2) {
            mysql_query("INSERT INTO films_nationalites (nom_nat) VALUES ('" . mysql_real_escape_string(pq($nom)->text()) . "')");
            $r = mysql_query("SELECT * FROM films_nationalites WHERE nom_nat='" . mysql_real_escape_string(pq($nom)->text()) . "'");
            $r2 = mysql_fetch_array($r);
        }
        echo $r2['nom_nat'].' ; ';
        mysql_query("INSERT IGNORE INTO films_nationalite_film VALUES (" . $row['id_film'] . ',' . $r2['id_nat'] . ')');
    }
	
	//Casting film
	if(strpos($row['url'],'serie')===false) {
		$id=filter_var($row['url'], FILTER_SANITIZE_NUMBER_INT);
		$casting="http://www.allocine.fr/film/fichefilm-$id/casting/";
		
		$file2 = url_get_contents($casting);
		$file2 = substr($file2, strpos(strtolower($file2), '<body'));
		if (strrpos(strtolower($file2), '</body>') !== false)
			$file2 = substr($file2, 0, strrpos(strtolower($file2), '</body>') + 7);
			
		$doc = phpQuery::newDocument($file2);
		
		foreach($doc['div.media_list_02 li:not([itemprop]) p > a,div.media_list_02 li:not([itemprop]) p > span'] as $nom) {
			$act=trim(pq($nom)->text());
			if(!$act) continue;
			if(strpos($act,'(')!==false) $act=trim(substr($act,0,strpos($act,'(')));
			
			$role=trim(str_replace('Rôle : ','',pq($nom)->parent()->next()->text()));
			echo $act.' - ' . $role.' // ';
			
			$r = mysql_query("SELECT * FROM films_acteurs WHERE nom_acteur='" . mysql_real_escape_string($act) . "'");
			$r2 = mysql_fetch_array($r);
			if (!$r2) {
				mysql_query("INSERT INTO films_acteurs (nom_acteur) VALUES ('" . mysql_real_escape_string($act) . "')");
				$r = mysql_query("SELECT * FROM films_acteurs WHERE nom_acteur='" . mysql_real_escape_string($act) . "'");
				$r2 = mysql_fetch_array($r);
			}
			mysql_query("INSERT IGNORE INTO films_acteur_film (id_film, id_acteur, role) VALUES (" . $row['id_film'] . ',' . $r2['id_acteur'] . ",'".mysql_real_escape_string($role)."')");
			mysql_query("UPDATE films_acteur_film SET role=".mysql_real_escape_string($role)."' WHERE id_film=".$row['id_film']." AND id_acteur=".$r2['id_acteur']);			
		}
	}
	else {
		foreach ($doc['table.data_box_table margin_10b tr:eq(1) td span.acLnk'] as $nom) {
		$act=pq($nom)->text();
		if(strpos($act,'(')!==false) $act=trim(substr($act,0,strpos($act,'(')));
			$r = mysql_query("SELECT * FROM films_reals WHERE nom_real='" . mysql_real_escape_string($act) . "'");
			$r2 = mysql_fetch_array($r);
			if (!$r2) {
				mysql_query("INSERT INTO films_reals (nom_real) VALUES ('" . mysql_real_escape_string($act) . "')");
				$r = mysql_query("SELECT * FROM films_reals WHERE nom_real='" . mysql_real_escape_string($act) . "'");
				$r2 = mysql_fetch_array($r);
			}
			echo $r2['nom_real'].' ; ';
			mysql_query("INSERT IGNORE INTO films_real_film VALUES (" . $row['id_film'] . ',' . $r2['id_real'] . ')');
		}
	}
	
}



CloseConnection();
