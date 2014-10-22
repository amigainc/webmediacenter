<?php

function sentiment($texte, $lang = 'fr') {

    if (!in_array($lang, array('fr', 'de', 'en')))
        return 0;

    $json = url_get_contents('https://textalytics.com/core/sentiment-1.2?key=ecb3a2a288bed734c47bd89918219d47&of=json&txt=' . urlencode($texte) . '&model=' . $lang . '-general&entities=&concepts=&ud=', false, false, true);

    $decode = json_decode($json, 1);

    if (isset($decode['score']))
        return $decode['score'];

    return 0;
}
