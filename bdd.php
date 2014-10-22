<?php

//echo 'TRUNCATE TABLE films_films<br/>';

ini_set('error_reporting',E_ALL);
ini_set('display_errors',1);
include "_display.php";


//if (!isset($_SESSION['files'])) {
$tFiles = array();

scan(Encoding::toLatin1('D:\\Documents\\Mes videos\\'), $tFiles);

setLinks($tFiles);

function scan($dir, & $tFiles) {


	//echo "$dir<br/>";
		
    if (!is_dir($dir))
        return;


    if (substr($dir, -1, 1) !== '\\')
        $dir.='\\';

    $t = scandir($dir);

    foreach ($t as $film) {

        if (substr($film, 0, 1) == '.')
            continue;

        if (is_dir($dir . $film)) {
            scan($dir . $film . '\\', $tFiles);
            continue;
        }

		if(!is_numeric(substr($film,1,4)))
			continue;

        $ext = strtolower(substr($film, -3, 3));
        switch ($ext) {
            case 'mov':
            case 'mp4':
            case 'mp2':
            case 'peg':
            case 'omg':
            case 'mpg':
            case 'm4v':
            case 'avi':
            case 'mkv':
            case 'wmv':
            case 'flv':

                $tFiles[filemtime($dir . $film).'-'.rand(10,99)] = $dir . $film;
                break;
            default:
                //echo $film;
                break;
        }
    }
}

function setLinks($tFiles) {

	krsort($tFiles);
	
    //shuffle($tFiles);
    //$tFiles=  array_slice($tFiles, 0, 2);

    $i = 0;


    foreach ($tFiles as $key=>$path) {

		flush();
		
        if (!is_file($path))
            continue;
		
			
        $film = basename($path);
        $dir = dirname($path) . '\\';

        if (substr($film, 0, 5) == 'TITLE')
            continue;

		$md5 = md5(substr($film, 0, strlen($film) - 4));
        $result=mysql_query("SELECT * FROM films_films WHERE url LIKE '%gen%' AND hash='$md5'");
		if(mysql_num_rows($result)>0) {
			$row=mysql_fetch_array($result);
			if($row['chemin_film']!=$path)
			{
				mysql_query("UPDATE films_films SET 
					chemin_film= '" . mysql_real_escape_string($path) . "',
					date_modif='".filemtime($path)."', duree='', largeur=0, hauteur=0
					WHERE hash='" .	$md5 . "';");
				echo 'update '.$film.' ('.date('H:i:s').')<br/>';
			}
			flush();
			continue;
		}
		
		echo '<hr/>'.$path;
		
		
        $q = substr($film, 7, strlen($film) - 11);
        $q = str_replace(' ', '+', $q);
        $q = str_replace('Xvid', '', $q);
        $q = str_replace('.', '+', $q);
        $q = str_replace('_', '+', $q);
        $q = str_replace('(', '', $q);
        $q = str_replace(')', '', $q);
        $q = str_replace('[', '', $q);
        $q = str_replace(']', '', $q);
        $q = str_replace('VOST', '', $q);
        $q = str_replace('VOSTFR', '', $q);
        $q = str_replace('VF', '', $q);
        //$q = substr($q, 0, 25);

        $serie = null;
		$film2=$film;
        preg_match('/S[0-9]+E[0-9]+/i', $q, $serie);
		
		if(count($serie)>0) {
			$q=str_replace('+-+'.$serie[0],'',$q);
			$film2=str_replace(' - '.$serie[0],'',$film);
		}

		
        $file = url_get_contents('http://essearch.allocine.net/fr/autocomplete?geo2=83093&q=' . $q);

        $json = json_decode($file, 1);

		
        $wellNamed = false;
        foreach ($json as $tableau) {
            if (!isset($tableau['metadata']))
                continue;

            $name = Encoding::toLatin1('(' . $tableau['metadata'][count($tableau['metadata']) - 1]['value'] . ') ' . trim(ucwords(str_replace(array(':','&','?','/','#','"','’'), array('-','et','','-','','',"'"), isset($tableau['title1']) ? $tableau['title1'] : $tableau['title2']))));
			echo '<br/><i>'.$name.'</i>';
            if ($name == substr($film2, 0, strlen($film2) - 4)) {
                $wellNamed = true;
                
                if(count($serie)>0)
					$url='http://www.allocine.fr/series/ficheserie_gen_cserie=' . $tableau['id'] . '.html';
				else
					$url = 'http://www.allocine.fr/film/fichefilm_gen_cfilm=' . $tableau['id'] . '.html';
                break;
            }
        }

        if (!$wellNamed) {

            $file = url_get_contents('http://www.allocine.fr/recherche/1/?q=' . $q);

            //Conservation uniquement des balises BODY
            $file = substr($file, strpos(strtolower($file), '<body'));
            if (strrpos(strtolower($file), '</body>') !== false)
                $file = substr($file, 0, strrpos(strtolower($file), '</body>') + 7);

            // Test pub FeedsPortal
            $doc = phpQuery::newDocument($file);

            foreach ($doc['table.purehtml td.totalwidth'] as $nom) {
                $name = trim(encoding::toLatin1('(' . intval(pq($nom)->find('span.fs11')->text()) . ') ' . ucwords(str_replace(array(':','&','?','/','#','"','’'),array('-','et','','-','','',"'"),Encoding::toUTF8(trim(pq($nom)->find('a')->text()))))));
				
				echo '<br/><i>'.$name.'</i>';
                
                if ($name == substr($film2, 0, strlen($film2) - 4)) {
                    $wellNamed = true;
                    
					$url = 'http://www.allocine.fr' . pq($nom)->find('a')->attr('href');
					break;
                }
            }
        }
        if (!$wellNamed)
            continue;


        $md5 = md5(substr($film, 0, strlen($film) - 4));
		
        mysql_query("INSERT IGNORE INTO films_films (nom_film, annee_film, chemin_film, url, hash) "
        . "VALUES ('" . mysql_real_escape_string(substr($film, 7, strlen($film) - 11)) . "','" . substr($film, 1, 4) . "','" .
        mysql_real_escape_string($path) . "','" . mysql_real_escape_string($url) . "','" .
        $md5 . "');");

		mysql_query("UPDATE films_films SET url= '$url', date_modif='".filemtime($path)."' WHERE hash='" .
			$md5 . "';");
			
		$result=mysql_query("SELECT id_film FROM films_films WHERE hash='$md5'");
		$row=mysql_fetch_array($result);
		
		//updateMetas($row['id']);

		echo 'ajout '.$film.' ('.date('H:i:s').')<br/>';
			
		
        $i++;
//        if ($i == 30)
//            break;
    }
}

CLoseCOnnection();

function updateMetas($id) {
	var_dump($id);
	
	if(!$id)
		return;

	$query = "SELECT id_film, url FROM films_films WHERE id_film=$id";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result)) {



		$file2 = url_get_contents($row['url']);
		$file2 = substr($file2, strpos(strtolower($file2), '<body'));
		if (strrpos(strtolower($file2), '</body>') !== false)
			$file2 = substr($file2, 0, strrpos(strtolower($file2), '</body>') + 7);

	//echo '<textarea>'.$file2.'</textarea>';

		$doc = phpQuery::newDocument($file2);


		//mysql_query("DELETE FROM films_genre_film WHERE id_film=" . $row['id_film']);

		foreach ($doc['span[itemprop="genre"]'] as $nom) {
			$r = mysql_query("SELECT id_genre FROM films_genres WHERE nom_genre='" . mysql_real_escape_string(pq($nom)->text()) . "'");
			$r2 = mysql_fetch_array($r);
			if (!$r2) {
				mysql_query("INSERT INTO films_genres (nom_genre) VALUES ('" . mysql_real_escape_string(pq($nom)->text()) . "')");
				$r = mysql_query("SELECT id_genre FROM films_genres WHERE nom_genre='" . mysql_real_escape_string(pq($nom)->text()) . "'");
				$r2 = mysql_fetch_array($r);
			}
			//var_dump($r2);
			mysql_query("INSERT IGNORE INTO films_genre_film VALUES (" . $row['id_film'] . ',' . $r2['id_genre'] . ')');
		}

		/*
		 * Acteurs
		 */

		//mysql_query("DELETE FROM films_acteur_film WHERE id_film=" . $row['id_film']);

		foreach ($doc['span[itemprop="actors"]'] as $nom) {
			$r = mysql_query("SELECT id_acteur FROM films_acteurs WHERE nom_acteur='" . mysql_real_escape_string(pq($nom)->text()) . "'");
			$r2 = mysql_fetch_array($r);
			if (!$r2) {
				mysql_query("INSERT INTO films_acteurs (nom_acteur) VALUES ('" . mysql_real_escape_string(pq($nom)->text()) . "')");
				$r = mysql_query("SELECT id_acteur FROM films_acteurs WHERE nom_acteur='" . mysql_real_escape_string(pq($nom)->text()) . "'");
				$r2 = mysql_fetch_array($r);
			}
			//var_dump($r2);
			mysql_query("INSERT IGNORE INTO films_acteur_film VALUES (" . $row['id_film'] . ',' . $r2['id_acteur'] . ')');
		}
		
		//mysql_query("DELETE FROM films_real_film WHERE id_film=" . $row['id_film']);
		foreach ($doc['span[itemprop="director"]'] as $nom) {
			$r = mysql_query("SELECT id_real FROM films_reals WHERE nom_real='" . mysql_real_escape_string(pq($nom)->text()) . "'");
			$r2 = mysql_fetch_array($r);
			if (!$r2) {
				mysql_query("INSERT INTO films_reals (nom_real) VALUES ('" . mysql_real_escape_string(pq($nom)->text()) . "')");
				$r = mysql_query("SELECT id_real FROM films_reals WHERE nom_real='" . mysql_real_escape_string(pq($nom)->text()) . "'");
				$r2 = mysql_fetch_array($r);
			}
			//var_dump($r2);
			mysql_query("INSERT IGNORE INTO films_real_film VALUES (" . $row['id_film'] . ',' . $r2['id_real'] . ')');
		}
	}
}