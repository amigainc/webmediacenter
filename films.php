<?php


// Turn off output buffering
ini_set('output_buffering', 'off');
// Turn off PHP output compression
ini_set('zlib.output_compression', false);

include '_display.php';


//Flush (send) the output buffer and turn off output buffering
//ob_end_flush();
while (@ob_end_flush());

// Implicitly flush the buffer(s)
ini_set('implicit_flush', true);
ob_implicit_flush(true);

//prevent apache from buffering it for deflate/gzip
//header("Content-type: text/plain");
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

for ($i = 0; $i < 1000; $i++) {
    echo ' ';
}



if (isset($_GET['name']) && $_GET['name']) {
    $ext = substr($_GET['file'], -3, 3);

    $name = Encoding::toLatin1($_GET['name']);
    $name = str_replace('/', '-', $name);
    $name = str_replace('?', '', $name);
    $name = str_replace(':', '-', $name);
    $name = str_replace('&', 'Et', $name);
    $name = str_replace(chr(9), '', $name);
    $name = trim($name);

    $newpath = dirname($_GET['file']) . '\\' . $name . '.' . $ext;
    echo $_GET['file'] . ' --> ' . $newpath;

    rename($_GET['file'], $newpath);
	
	//AJout en base
	
	
	//MAJ metadatas
	
    die();
}


//if (!isset($_SESSION['files'])) {
$tFiles = array();

//scan('D:\Documents\Mes videos\\', $tFiles);
scan('D:\Documents\Mes videos\\', $tFiles);
//scan('Z:\Mes videos\\', $tFiles);

//scan('m:\\', $tFiles);
//scan('M:\\Series\\Under the dome S02\\', $tFiles);
//scan('m:\\Animation\\', $tFiles);
//scan('E:\Videos\\', $tFiles);
$_SESSION['files'] = $tFiles;
//} else {
//  $tFiles = $_SESSION['files'];
//}

setLinks($tFiles);

function scan($dir, & $tFiles) {


    if (!is_dir($dir))
        return;

    //echo "$dir<br/>";
    @ob_flush();
    flush();

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
		
		if (is_numeric(substr($film, 1, 4)))
			continue;

        $ext = strtolower(substr($film, -3, 3));
        switch ($ext) {
            case 'mov':
            case 'mp4':
            case 'mp2':
            case 'peg':
            case 'ogm':
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

    //shuffle($tFiles);
    //$tFiles = array_slice($tFiles, 0, 10);
	
	krsort($tFiles);
	
    $i = 0;

    foreach ($tFiles as $path) {



        if (!is_file($path))
            continue;

        $film = basename($path);
        $dir = dirname($path) . '\\';

        if (substr($film, 0, 5) == 'TITLE')
            continue;

		
        //if (intval(substr($film, 1, 4)) > 0)
        //    continue;

        if (strpos($dir, 'Spectacle') !== false)
            continue;
        if (strpos($dir, 'Souvenir') !== false)
            continue;
		
			
        $q = substr($film, 0, strlen($film) - 4);
        if (is_numeric(substr($q, 0, 4)))
            $q = substr($q, 4);
		
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

        $q=trim($q, '+-');	
        $q=trim($q);	
		

        //$q = substr($q, 0, 25);

        $serie = null;
        preg_match('/S[0-9]+E[0-9]+/i', $q, $serie);
        var_dump($serie);

        $file = url_get_contents('http://essearch.allocine.net/fr/autocomplete?geo2=83093&q=' . $q);

        $json = json_decode($file, 1);


        $wellNamed = false;
        foreach ($json as $tableau) {
            $name = Encoding::toLatin1('(' . $tableau['metadata'][count($tableau['metadata']) - 1]['value'] . ') ' . ucwords(isset($tableau['title1']) ? $tableau['title1'] : $tableau['title2']));
            if ($name == substr($film, 0, strlen($film) - 4)) {
                $wellNamed = true;
                echo '<i>' . $film . '</i><br/>';
                break;
            }
        }

        if ($wellNamed)
            continue;

//        var_dump($json);
//        die();


        echo '<b><a href="' . $path . '" target="_blank">' . $q .' / '.$film. "</b><br/>";


        $wellNamed = false;
        foreach ($json as $tableau) {
            $name = '(' . $tableau['metadata'][count($tableau['metadata']) - 1]['value'] . ') ' . ucwords(isset($tableau['title1']) ? $tableau['title1'] : $tableau['title2']);
            if ($name == substr($film, 0, strlen($film) - 4)) {
                $wellNamed = true;
                break;
            }
            echo '<div><img src="http://fr.web.img6.acsta.net/c_75_100/' . $tableau['poster'] . '"><a target="_blank" href="?file=' . urlencode($dir . $film) . '&name=' . $name . (count($serie) ? ' - ' . strtoupper($serie[0]) : '' ) . '">';
            echo $name . '</a></div>';
        }


        $file = url_get_contents('http://www.allocine.fr/recherche/1/?q=' . $q);

        //Conservation uniquement des balises BODY
        $file = substr($file, strpos(strtolower($file), '<body'));
        if (strrpos(strtolower($file), '</body>') !== false)
            $file = substr($file, 0, strrpos(strtolower($file), '</body>') + 7);

        // Test pub FeedsPortal
        $doc = phpQuery::newDocument($file);

        foreach ($doc['table.purehtml td div a'] as $nom) {
            $name = '(' . intval(pq($nom)->next()->next()->text()) . ') ' . ucwords(Encoding::toUTF8(pq($nom)->text()));
            echo '<a target="_blank" href="?file=' . urlencode(Encoding::toLatin1($dir . $film)) . '&name=' . $name . (count($serie) ? ' - ' . strtoupper($serie[0]) : '' ) . '">';
            echo $name . '</a><br/>';
        }
		
		
		$file = url_get_contents('http://www.imdb.com/find?ref_=nv_sr_fn&q=' . $q);

        //Conservation uniquement des balises BODY
        $file = substr($file, strpos(strtolower($file), '<body'));
        if (strrpos(strtolower($file), '</body>') !== false)
            $file = substr($file, 0, strrpos(strtolower($file), '</body>') + 7);

        // Test pub FeedsPortal
        $doc = phpQuery::newDocument($file);

        foreach ($doc['.findSection:eq(0) td.result_text'] as $nom) {
            $name = '(' . intval(substr(pq($nom)->text(), strpos(pq($nom)->text(),'(')+1)) . ') ' . ucwords(Encoding::toUTF8(pq($nom)->find('a')->text()));
            echo '<a target="_blank" href="?file=' . urlencode(Encoding::toLatin1($dir . $film)) . '&name=' . $name . (count($serie) ? ' - ' . strtoupper($serie[0]) : '' ) . '">';
            echo $name . '</a><br/>';
        }


        echo '<hr/>';

        @ob_flush();
        flush();

        $i++;
//        if ($i == 30)
//            break;
    }
}
