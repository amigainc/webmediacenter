<?php

function stripAccents($stripAccents) {
    return strtr($stripAccents, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

function stripAccentsUTF8($texte) {
    $texte = mb_strtolower($texte, 'UTF-8');
    $texte = str_replace(array('à', 'â', 'ä', 'á', 'ã', 'å', 'î', 'ï', 'ì', 'í', 'ô', 'ö', 'ò', 'ó', 'õ', 'ø', 'ù', 'û', 'ü', 'ú', 'é', 'è', 'ê', 'ë', 'ç', 'ÿ', 'ñ',), array('a', 'a', 'a', 'a', 'a', 'a', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'e', 'e', 'e', 'e', 'c', 'y', 'n',), $texte);
    return $texte;
}

function ResumeText($text) {
    $ret = strip_tags($text);
    $ret = html_entity_decode($ret);
    if (strlen($ret) > 50)
        $ret = substr($ret, 0, 47) . '...';

    return $ret;
}

function delTree($dir) {
    if (!is_dir($dir))
        return;

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return @rmdir($dir);
}

function compressString($content) {
    return urlencode(base64_encode(gzcompress($content)));
}

function uncompressString($content) {
    return (strpos($content, ' ') === false) ? @gzuncompress(base64_decode(urldecode($content))) : $content;
}
