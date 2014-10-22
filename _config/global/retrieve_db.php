<?php
/*
 * All the functions to display datas from database
 */

///////////////////////////////////////////////

function TestDisplay($texte, $cats, $div = 200) {

    global $tKeys;
    //séparateurs ; = obligatoires
    //séparateurs , = ou
    $texte = str_replace(chr(10), ' ', strtolower(html_entity_decode($texte)) . ' ');
    $texte = str_replace(chr(13), ' ', $texte);
    $texte = str_replace(',', ' ', $texte);
    $texte = str_replace('&hellip;', ' ', $texte);
    $texte = str_replace('&nbsp;', ' ', $texte);
    $texte = str_replace('&rsquo;', '\'', $texte);
    $texte = str_replace('&rdquo;', '\'', $texte);
    $texte = str_replace(';', ' ', $texte);
    $texte = str_replace(':', ' ', $texte);
    $texte = str_replace('?', ' ', $texte);
    $texte = str_replace('!', ' ', $texte);
    $texte = str_replace('.', ' ', $texte);
    $texte = str_replace('(', ' ', $texte);
    $texte = str_replace(')', ' ', $texte);
    $texte = str_replace('[', ' ', $texte);
    $texte = str_replace(']', ' ', $texte);
    $texte = str_replace('/', ' ', $texte);
    $texte = str_replace('-', ' ', $texte);
    $texte = str_replace('\'', ' ', $texte);
    $texte = str_replace('"', ' ', $texte);

    while (strpos($texte, '  ') !== false)
        $texte = str_replace('  ', ' ', $texte);
    $nbMots = count(split(' ', $texte));
    $cats = explode(';', $cats);

    for ($i = 0; $i < count($cats); $i++) {
        $min = 5;
        $sscats = explode(',', $cats[$i]);
        $tot = 0;

        for ($j = 0; $j < count($sscats); $j++) {
            $cat = $sscats[$j];

            if (isset($tKeys[$cat])) {
                $t = array();

                for ($j = 0; $j < count($tKeys[$cat]); $j++) {
                    $tot+=TestWord($tKeys[$cat][$j], $texte);
                }
                $max[$cat] = round($tot * $div / $nbMots, 0);

                if ($max[$cat] > 5)
                    $max[$cat] = 5;

                if ($min > $max[$cat])
                    $min = $max[$cat];
            }
            else {
                return 0;
            }
        }
        //Test si au moins 1 a plus de 3 étoiles
        $bOK = false;

        if (isset($max)) {

            if ($min >= 3)
                $bOK = true;
        }

        if (!$bOK)
            return 0;
    }

    if (count($max) == 1) {

        foreach ($max as $cat => $valeur)
            return $valeur;
    }

    if (count($max) > 1) {
        $moy = 0;
        $aspect = false;

        foreach ($max as $cat => $valeur) {
            $moy+=$valeur;
        }
        //print_r($max);
        return round($moy / count($max));
    }
    return 0;
}

// Test de la présence d'un mot et de ses variantes

function TestWord($word, $text) {
    $i = 0;
    return TestWord2($word, $text);
    $max = 0;

    for ($j = 0; $j < count($t); $j++) {

        if ($t[$j] > 0) {

            if ($t[$j] > $max)
                $max = $t[$j];
        }
    }
    return $max;
}

//Test de la présence d'un mot

function TestWord2($word, $text) {
    $l = strlen($text);
    $i = 0;
    $start = 0;
    /* while($i!==false)
      {
      $i=strpos($text,$word,$i);

      if($i!==false)
      {

      if($start==0) $start=$i;

      if($i>0)

      if(substr($text,$i-1,1)==' ') $nb++;
      }
      } */

    if ($word) {
        $start = strpos($text, $word . ' ');

        if (substr($text, $start - 1, 1) != ' ' && substr($text, $start - 1, 1) != '\'')
            $start = false;

        if ($start !== false) {
            $nb = substr_count($text, $word . ' ');
            //echo $word.', ';
            return $nb;
        }
    }
}

///////////////////////////////////////
///////////////////////////////////////
///////////////////////////////////////

function AddCommands($commands = '') {

    if ($commands) {
        $libelle = str_replace('"', '\"', $libelle);
        $pc = round(100 / count($commands), 0);
        $monUrl = urlencode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        if (isset($_GET['id_professionnel']))
            $monUrl = "http://srvcgx.com/?p=" . $_GET['id_professionnel'];

        if (isset($_GET['id_article']))
            $monUrl = "http://srvcgx.com/?a=" . $_GET['id_article'];

        if (isset($_GET['id_evenement']))
            $monUrl = "http://srvcgx.com/?e=" . $_GET['id_evenement'];
        echo '<div id="commands">';

        foreach ($commands as $code => $libelle) {
            echo '<div class="menu">';

            switch ($code) {

                case 'IMPORT':
                    echo '<a href="?vcard=' . ($_GET['id_professionnel'] ? $_GET['id_professionnel'] : $libelle) . '"><img src="http://images.services-conjugaux.com/_global/32x32/address.png"/><br/>Importer</a>';
                    break;
                /* case 'LOCATE':
                  echo '<a target="_blank" href="http://maps.google.com/maps/?q='.$libelle.'"><img src="http://images.services-conjugaux.com/_global/32x32/world.png"/><br/>Localiser</a>';
                  break; */

                case 'CALL':
                    echo '<a href="tel:' . $libelle . '"><img src="http://images.services-conjugaux.com/_global/32x32/phone.png"/><br/>Appeler</a>';
                    break;

                case 'FACEBOOK':
                    echo '<a target="_blank" href="http://facebook.com/share.php?u=' . $monUrl . '"><img src="http://images.services-conjugaux.com/_global/32x32/facebook.png"/><br/>Facebook</a>';
                    break;

                case 'TWITTER':
                    echo '<a target="_blank" href="http://twitter.com/?status=' . utf8_decode($libelle) . ' : ' . $monUrl . '"><img src="http://images.services-conjugaux.com/_global/32x32/twitter.png"/><br/>Twitter</a>';
                    break;

                case 'EMAIL':
                    echo '<a href="mailto:?subject=' . $libelle . '&body=Je te recommande cette page de Services-Conjugaux.com : ' . $monUrl . '"><img src="http://images.services-conjugaux.com/_global/32x32/email.png"/><br/>Envoyer à un ami</a>';
                    break;
            }
            echo '</div>';
        }
        echo '</div>';
    } else {
        ?>
        <!-- AddThis Button BEGIN -->
        <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4d80b47459a95aae"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
        <script type="text/javascript">
            var addthis_localize = {
                share_caption: "Partager sur vos réseaux",
                email_caption: "Par e-mail",
                email: "E-Mail",
                favorites: "Favoris",
                more: "Plus..." };
            var addthis_config = {"data_track_clickback":true};</script>
        <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4d80b47459a95aae"></script>
        <!-- AddThis Button END -->
        <?php
    }
}

function GetNaturesAsCheckboxes($niveau, $titre, $selected = '') {
    $ret = '';
    $query = "SELECT * FROM natures WHERE niveau_nature=" . $niveau . " ORDER BY code_nature ASC";
    $result = mysql_query($query);

    while ($row = mysql_fetch_array($result)) {
        $ret.='<input type="checkbox" name="' . $titre . '[]" value="' . $row['code_nature'] . '"' . (strpos($selected, $row['code_nature']) !== false ? ' checked="checked"' : '') . '/>' . utf8_encode($row['libelle_nature']) . '<br/>';
    }
    $ret.='<a href="javascript:Checking(\'' . $titre . '[]\',true);">Tout sélectionner</a> / <a href="javascript:Checking(\'' . $titre . '[]\',false);">Tout désélectionner</a><br/>';
    echo $ret;
}

function SetRadios($name, $tDatas, $selected = '') {

    foreach ($tDatas as $cle => $text) {
        echo '<input type="radio" name="' . $name . '" value="' . $cle . '"' . ($selected == $cle ? ' checked="checked"' : '') . '/>' . $text . '<br/>';
    }
}

function SetCheckes($name, $tDatas, $selected = '') {

    foreach ($tDatas as $cle => $text) {
        echo '<input type="checkbox" name="' . $name . '[]" value="' . $cle . '"' . (strpos($selected, $cle) !== false ? ' checked="checked"' : '') . '/>' . $text . '<br/>';
    }
}

//Utilisée sur la partie Mobile

function ListeNatures($codes) {

    if ($codes == 'all' || $codes == '') {
        echo "Tous<br/>";
        return;
    }
    $query = "SELECT * FROM natures WHERE ";
    $t = explode(',', $codes);

    for ($i = 0; $i < count($t); $i++) {
        $t[$i] = "code_nature='" . $t[$i] . "'";
    }
    $query.="(" . implode(' OR ', $t) . ")";
    $result2 = mysql_query($query);

    while ($row2 = mysql_fetch_array($result2)) {
        echo utf8_encode($row2['libelle_nature']) . ',';
    }
    echo '<br/>';
}

/* * ********** */

function ListeArticles($query, $titre, $recherche = '') {
    $result = mysql_query($query);
    $ok = false;
    $t = array();

    if ($result) {
        $nombre = mysql_num_rows($result);

        if ($nombre > 0) {
            $ok = true;
            $t = array();
            ?>
            <a name="debut"></a>
            <table width="100%">
                <tr class="titre">
                    <td style="text-align:center" colspan="2"><?php echo $titre ?></td>
                </tr>
            </table>
            <?php
            $recherche = strtolower(utf8_encode(html_entity_decode($recherche)));

            while ($row = mysql_fetch_array($result)) {
                $titre = utf8_encode($row['titre_article']);

                if (!$titre)
                    $titre = utf8_encode($row['titre']);
                $vues = $row['nb_vues'];
                $date = FormatDate(substr($row['date_edition'], 0, 10));

                if (!$date)
                    $date = FormatDate(substr($row['date_debut'], 0, 10));
                $details = utf8_encode(strip_tags(html_entity_decode($row['details'])));
                $id_article = FindLastValidatedArticle($row['id_article']);

                if ($id_article != $row['id_article']) {
                    $query = "SELECT * FROM articles WHERE id_article=" . $id_article;
                    $result2 = mysql_query($query);

                    while ($row2 = mysql_fetch_array($result2)) {
                        $titre = utf8_encode($row2['titre_article']);
                        $details = strip_tags(utf8_encode($row2['details']));
                        $vues = $row2['nb_vues'];
                        $date = FormatDate(substr($row2['date_edition'], 0, 10));
                    }
                }

                if (!isset($t['K' . $id_article])) {

                    if ($recherche) {
                        $i = strpos(strtolower($details), $recherche);

                        if ($i !== false) {
                            $min = $i - 50;

                            if ($min < 0)
                                $min = 0;
                            $details = '...' . substr($details, $min, 100) . '...';
                            $details = str_ireplace($recherche, '<span style="background-color:#FE0C00;color:white;">' . $recherche . '</span>', $details);
                        }
                        else {
                            $details = substr($details, 0, 100);
                        }
                        $i = strpos(strtolower($titre), strtolower($recherche));

                        if ($i !== false) {
                            $titre = str_ireplace($recherche, '<span style="background-color:#FE0C00;color:white;">' . $recherche . '</span>', $titre);
                        }
                    } else {
                        $details = substr($details, 0, 100);
                    }
                    $rows[] = '<tr><td>
                        <a href="/index.php?id_article=' . $row['id_article'] . '">' . $titre . '</a>
                        ' . $details . '...' . '
                        </td>
						<td style="font-size:10px;text-align:right;width:15%;">Le ' . $date . '<br/>' . $vues . ' vues</td>
                                                </tr>';
                }
            }
            SetPagesArticles($rows);
        }
    }
}

function SetPagesArticles($rows) {
    $limit = 10;
    $page = 1;
    $compte = 0;
    echo '<div id="page' . $page . '"><table width="100%">';

    for ($i = 0; $i < count($rows); $i++) {
        echo $rows[$i];
        $compte++;

        if ($compte > $limit) {
            echo '
                </table><table width="100%" style="table-layout:fixed"><tr><td align="left">';

            if ($page > 1)
                echo '<a href="#debut" onclick="ShowPage(\'' . ($page - 1) . '\');">Page ' . ($page - 1) . '</a>';
            echo '</td><td align="center"><b>Page ' . $page . '</b></td><td style="text-align:right"> ';

            if ($i < count($rows))
                echo '<a href="#debut" onclick="ShowPage(\'' . ($page + 1) . '\');">Page ' . ($page + 1) . '</a>';
            echo '</td></tr></table></div>';
            $compte = 0;
            $page++;
            echo '<div id="page' . $page . '" style="display:none"><table width="100%">';
        }
    }

    if ($page > 1)
        echo '</table><table width="100%"><tr><td align="left"><a href="#debut" onclick="ShowPage(\'' . ($page - 1) . '\');">Page ' . ($page - 1) . '</a></td></tr>';
    echo '</table></div>';
}

/* * ************** */

//Trouver le dernier article validé

function FindLastValidatedArticle($id_article) {
    $query = "SELECT id_article FROM articles WHERE id_precedent_article=" . $id_article . " AND NOT id_validateur=0";
    $result = mysql_query($query);

    if ($result) {

        while ($row = mysql_fetch_array($result)) {
            $id_article = FindLastValidatedArticle($row['id_article']);
        }
    }
    return $id_article;
}

//Affichage d'une combo listant tous les pays

function ComboPays($name, $selected = '') {
    $ret = '<select name="' . $name . '">';

    global $liste_pays;

    foreach ($liste_pays as $code => $nom) {
        $ret.='<option value="' . $code . '"' . ($selected == $code ? ' selected="selected"' : '') . '>' . $nom . '</option>';
    }
    $ret.='</select>';
    echo $ret;
}

function GetPays($selected = '') {

    global $liste_pays;

    foreach ($liste_pays as $code => $nom) {

        if ($selected == $code)
            echo $nom;
    }
}

//Affichage d'une série de 3 combos pour gérer la date

function ComboDate($name, $selected = '') {
    $ret = '';
    $sJ = '';
    $sM = '';
    $sA = '';

    if ($selected) {
        $sJ = substr($selected, 8, 2);
        $sM = substr($selected, 5, 2);
        $sA = substr($selected, 0, 4);
    }
    //Jour
    $ret.=ComboJour($name, $sJ);
    //Mois
    $ret.=ComboMois($name, $sM);
    //Année
    $ret.=ComboAnnee($name, $sA);
    $ret.='<br/><a href="javascript:document.forms[0].' . $name . '_jour.value=\'' . date('d') . '\';document.forms[0].' . $name . '_mois.value=\'' . date('m') . '\';document.forms[0].' . $name . '_annee.value=\'' . date('Y') . '\';">Mettre la date du jour</a>';
    echo $ret;
}

function ComboJour($name, $selected = '') {
    $ret = '<select name="' . $name . '_jour">';

    for ($i = 1; $i < 32; $i++) {
        $code = ($i < 10 ? '0' : '') . $i;
        $ret.='<option value="' . $code . '"' . ($selected == $code ? ' selected="selected"' : '') . '>' . $code . '</option>';
    }
    $ret.='</select> ';
    return $ret;
}

function ComboMois($name, $selected = '') {
    $ret = '<select name="' . $name . '_mois">';
    $ret.='<option value="01"' . ($selected == '01' ? ' selected="selected"' : '') . '>Janvier</option>';
    $ret.='<option value="02"' . ($selected == '02' ? ' selected="selected"' : '') . '>Février</option>';
    $ret.='<option value="03"' . ($selected == '03' ? ' selected="selected"' : '') . '>Mars</option>';
    $ret.='<option value="04"' . ($selected == '04' ? ' selected="selected"' : '') . '>Avril</option>';
    $ret.='<option value="05"' . ($selected == '05' ? ' selected="selected"' : '') . '>Mai</option>';
    $ret.='<option value="06"' . ($selected == '06' ? ' selected="selected"' : '') . '>Juin</option>';
    $ret.='<option value="07"' . ($selected == '07' ? ' selected="selected"' : '') . '>Juillet</option>';
    $ret.='<option value="08"' . ($selected == '08' ? ' selected="selected"' : '') . '>Août</option>';
    $ret.='<option value="09"' . ($selected == '09' ? ' selected="selected"' : '') . '>Septembre</option>';
    $ret.='<option value="10"' . ($selected == '10' ? ' selected="selected"' : '') . '>Octobre</option>';
    $ret.='<option value="11"' . ($selected == '11' ? ' selected="selected"' : '') . '>Novembre</option>';
    $ret.='<option value="12"' . ($selected == '12' ? ' selected="selected"' : '') . '>Décembre</option>';
    $ret.='</select> ';
    return $ret;
}

function ComboAnnee($name, $selected = '') {
    $ret = '<select name="' . $name . '_annee">';

    for ($i = date('Y') + 5; $i >= 1900; $i--) {
        $ret.='<option value="' . $i . '"' . ($selected == $i ? ' selected="selected"' : '') . '>' . $i . '</option>';
    }
    $ret.='</select> ';
    return $ret;
}

////////////////////////////////////////////////////////////////
// Renvoyer une date à partir des combos

function RetrieveDateFromCombos($name) {

    if (isset($_REQUEST[$name . '_annee']))
        return $_REQUEST[$name . '_annee'] . '-' . $_REQUEST[$name . '_mois'] . '-' . $_REQUEST[$name . '_jour'];
    return '';
}

function ComboJourSemaine($name, $selected = '') {
    ?><select name="<?php echo $name ?>">
        <option value="LU"<?php echo ($selected == 'LU' ? ' selected="selected"' : '') ?>>Lundi</option>
        <option value="MA"<?php echo ($selected == 'MA' ? ' selected="selected"' : '') ?>>Mardi</option>
        <option value="ME"<?php echo ($selected == 'ME' ? ' selected="selected"' : '') ?>>Mercredi</option>
        <option value="JE"<?php echo ($selected == 'JE' ? ' selected="selected"' : '') ?>>Jeudi</option>
        <option value="VE"<?php echo ($selected == 'VE' ? ' selected="selected"' : '') ?>>Vendredi</option>
        <option value="SA"<?php echo ($selected == 'SA' ? ' selected="selected"' : '') ?>>Samedi</option>
        <option value="DI"<?php echo ($selected == 'DI' ? ' selected="selected"' : '') ?>>Dimanche</option>
    </select>
    <?php
}

function ComboHeure($name, $from, $to, $selected = '') {
    ?>
    <select name="<?php echo $name ?>">
        <option value="--:--">--:--</option>
    <?php
    for ($i = $from; $i < $to; $i++) {

        for ($j = 0; $j < 2; $j++) {
            $min = $j * 30;
            $heure = ($i < 10 ? "0" : "") . $i . ":" . ($min < 10 ? "0" : "") . $min;
            echo '<option value="' . $heure . '"' . ($selected == $heure ? ' selected="selected"' : '') . '>' . $heure . '</option>';
        }
    }
    ?>
    </select>
        <?php
    }

/////////////////////////////////////////////
/////////////////////////////////////////////
/////////////////////////////////////////////

    function GetPersonne($id) {
        $query = "SELECT nom, prenom FROM personnes WHERE id_compte=" . $id;
        $result = mysql_query($query);
        $ret = '';

        while ($row = mysql_fetch_array($result)) {
            $ret = trim(utf8_encode($row['prenom'] . ' ' . $row['nom']));
        }

        if ($ret == '') {
            $query = "SELECT email FROM comptes WHERE id_compte=" . $id;
            $result = mysql_query($query);

            while ($row = mysql_fetch_array($result)) {
                $ret = utf8_encode($row['email']);
            }
        }
        echo $ret;
    }