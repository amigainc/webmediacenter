<?php


// Turn off output buffering
ini_set('output_buffering', 'off');
// Turn off PHP output compression
ini_set('zlib.output_compression', false);

//Flush (send) the output buffer and turn off output buffering
//ob_end_flush();
while (@ob_end_flush());

// Implicitly flush the buffer(s)
ini_set('implicit_flush', true);
ob_implicit_flush(true);

//prevent apache from buffering it for deflate/gzip
//header("Content-type: text/plain");
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

include "_display.php";

for ($i = 0; $i < 1000; $i++) {
    echo ' ';
}



if(isset($_GET['delete'])) {
    unlink($_GET['delete']);
    echo $_GET['delete'].' supprimé.';
    die();
}

scan(Encoding::toLatin1('D:\\Documents\\Mes videos\\'), $tFiles);

setLinks($tFiles);

function scan($dir, & $tFiles) {
    if (!is_dir($dir))
        return;
    
    echo "+ ";
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


        $ext = strtolower(substr($film, -3, 3));
        switch ($ext) {
            case 'mov':
            case 'mp4':
            case 'mp2':
            case 'peg':
            case 'mpg':
            case 'm4v':
            case 'avi':
            case 'mkv':
            case 'wmv':
            case 'flv':
                $neutre = substr($film, 0, strlen($film) - 4);
                if (!isset($tFiles[$neutre]))
                    $tFiles[$neutre] = array();
                $tFiles[$neutre][] = $dir . $film;
                break;
            default:
                break;
        }
    }
}

function setLinks($tFiles) {

    foreach ($tFiles as $paths) {
        if (count($paths) > 1) {
            foreach ($paths as $path) {
                echo "$path <a target='_blank' href='?delete=".urlencode($path)."'>Supprimer</a><br/>";
            }
            echo '<hr/>';
        }
    }
}
