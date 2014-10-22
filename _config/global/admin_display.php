<?php

function AdminMainInterface($objet, $table, $tLiaison = '', $filtre = '', $nohead = false, $type = 'ADM', $orderDate = false) {
    if (!$nohead)
        SetHead(1, $objet);
    ?>
    <script>
        function toggleEditor(id) {
            if (!tinyMCE.getInstanceById(id))
                tinyMCE.execCommand('mceAddControl', false, id);
            else
                tinyMCE.execCommand('mceRemoveControl', false, id);
        }
    </script>
    <?php
    if ($type !== null) {
        if (!TestLogged($type)) {
            SetFoot();
            die();
        }
    }

    $liens = true;
    if ($filtre) {
        $query = "SELECT * FROM " . $table . " WHERE " . $filtre;
        $result = mysql_query($query);
        if (mysql_num_rows($result) == 1)
            $liens = false;
    }

    //Suppression de compte
    if (!$liens && isset($_GET['delete']))
        unset($_SESSION['id_visiteur']);

    $mess = AdminDeleteEdit($table);
    //Données à saiver
    $mess.=AdminSaveEdit($table, $filtre);
    if ($mess)
        echo '<div style="color:red;text-align:center;">' . $mess . '<br/>&nbsp;</div>';
    AdminShowEdit($objet, $table, $tLiaison, $filtre, $type);
    $tCols = AdminGetCols($table);
    $id_field = AdminGetPrimaryCol($table);
    $titre_field = AdminGetLibCol($table);
    $date_field = AdminGetDateCol($table);
    if (isset($_GET['recherche']))
        $recherche = $_GET['recherche'];

    $liens = true;
    if ($filtre) {
        $query = "SELECT * FROM " . $table . " WHERE " . $filtre;
        $result = mysql_query($query);
        if (mysql_num_rows($result) == 1)
            $liens = false;
    }
    if ($liens) {
        ?>
        <a href="./">Revenir à l'administration</a> - 
        <a href="?<?php echo ($filtre ? $filtre . '&' : '') ?>new=">Ajouter un enregistrement</a><br/><br/>
        <form method="get">
            <input type="text" name="recherche" value="<?php echo (isset($recherche) ? $recherche : '') ?>"/>
            <input type="submit" value="Filtrer" style="width:100px"/>
        </form>
        <?php echo (isset($recherche) ? '<a href="?">Tout afficher</a>' : '') ?>
        <br/>
        <br/>

    <?php } ?>
    <a name="debut"></a>
    <?php
    //Listing
    $query = "SELECT $table.*";
    $join = '';

    if (is_array($tLiaison)) {
        foreach ($tLiaison as $field => $table_liee) {
            $filtre2 = '';
            if (strpos($table_liee, '?') !== false) {
                $t = explode('?', $table_liee);
                $filtre2 = $t[1];
                $table_liee = $t[0];
            }
            $id_liee = AdminGetPrimaryCol($table_liee);
            $txt_lie = AdminGetLibCol($table_liee);
            $query.=", $table_liee.$txt_lie AS $field";
            $join.=" LEFT JOIN $table_liee ON $table.$field=$table_liee.$id_liee ";
            /* if(!$titre_field) 
              {
              $titre_field='nom_xyz';
              $tCols[]='nom_xyz';

              }
              if($titre_field=='nom_xyz')
              {
              $concat.=$field.",' '";
              } */
        }
    }

    if (isset($concat) && $concat)
        $query.=", concat($concat) as nom_xyz";

    $query.=" FROM $table $join";

    if ($filtre)
        $query.=" WHERE" . $filtre;
    if (isset($recherche) && $recherche) {
        if ($filtre)
            $query.=" AND ";
        else
            $query.=" WHERE ";
        $query.="(";
        for ($i = 0; $i < count($tCols); $i++) {
            $t[] = $table . '.' . $tCols[$i] . " LIKE '%" . $recherche . "%'";
        }
        $query.=implode(' OR ', $t);
        $query.=")";
    }
    if ($orderDate) {
        $query.=" ORDER BY $table.$date_field DESC, $table.$id_field  DESC";
    } else {
        if ($titre_field)
            $query.=" ORDER BY $table.$titre_field  ASC";
    }

    //echo $query;

    $result = mysql_query($query);

    //echo $query;
    ?>
    <table>
        <tr class="titre">
            <td colspan="2">
    <?php echo mysql_num_rows($result) . ' ' . $objet ?>
            </td>
        </tr>
    </table>
    <?php
    while ($row = mysql_fetch_array($result)) {
        $ret = '<tr style="border-bottom:1px dotted #888;">';
        $id = '';

        //Titre
        $titre = '';
        for ($i = 0; $i < count($tCols); $i++) {
            if ($titre_field && $tCols[$i] == $titre_field && $row[$tCols[$i]]) {
                $titre = ', <br/>' . ($orderDate ? $row[$date_field] . ' - ' : '') . $row[$tCols[$i]];
                break;
            } else {
                if ($row[$tCols[$i]] && !is_numeric($row[$tCols[$i]]))
                    $titre.=', <br/>' . $tCols[$i] . '=<b>' . ResumeText($row[$tCols[$i]]) . '</b>';
            }
        }
        if (!$titre) {
            for ($i = 0; $i < count($tCols); $i++) {
                $titre.=', <br/>' . $tCols[$i] . '=<b>' . ResumeText($row[$tCols[$i]]) . '</b>';
            }
        }
        if (substr($titre, 0, 2) == ', ')
            $ret.= '<td style="font-size:11px">' . Encoding::toUTF8(substr($titre, 7)) . '</td>';
        
		//Lien d'édition
        for ($i = 0; $i < count($tCols); $i++) {
            if ($tCols[$i] == $id_field) {
                $id = $row[$tCols[$i]];
                $ret.= '<td style="text-align:right"><a title="Editer" href="?' . ($filtre ? $filtre . '&' : '') . 'edit=' . $id . '"><img src="http://pix.suiterre.fr/icones/pc-de/stuttgart/edit.png" width="30"/></a>';
                if ($liens)
                    $ret.= '<a title="Dupliquer" href="#" onclick="if(confirm(\'Voulez-vous faire une copie de cet enregistrement ? OK pour confirmer, Annuler pour éditer.\')) location.href=\'?' . ($filtre ? $filtre . '&' : '') . 'duplik=' . $id . '\'; else location.href=\'?' . ($filtre ? $filtre . '&' : '') . 'edit=' . $id . '\';"><img src="http://pix.suiterre.fr/icones/pc-de/stuttgart/archives.png" width="30"/></a>';
                $ret.='</td>';
                break;
            }
        }
        $ret.= '</tr>';
        $rows[] = $ret;
    }
    SetPagesResults($rows);
    ?>
    </table>
    <?php
    GraphVues($table, $filtre);
}

// Voir l'édition
// $tLiaison=array()
function AdminShowEdit($objet, $table, $tLiaison = '', $filtre = '', $type = '') {
    $tCols = AdminGetCols($table);
    $id_field = AdminGetPrimaryCol($table);
    $titre_field = AdminGetLibCol($table);
    $edit = "";
    $new = false;
    if (isset($_GET['edit']))
        $edit = $_GET['edit'];
    if (isset($_GET['duplik']))
        $edit = $_GET['duplik'];
    if (isset($_GET['new']))
        $new = true;
    if ($edit || $new) {
        ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
        <?php if ($edit) { ?>
                <input type="hidden" name="<?php echo $id_field ?>" value="<?php echo (isset($_GET['duplik']) ? '' : $edit) ?>"/>
        <?php } ?>
            <table width="100%">
                <tr class="titre">
                    <td colspan="2">
            <?php
            if ($edit)
                echo "Modification : ";
            else
                echo "Nouveau : ";
            echo $objet
            ?>
                    </td>
                </tr>
                        <?php
                        $tValues = AdminGetValues($table, $tCols, $id_field, $edit);
                        //Mise en forme en fonction de la structure
                        $query = "SHOW FULL COLUMNS FROM " . $table;
                        $result = mysql_query($query);
                        while ($row = mysql_fetch_array($result)) {
                            $disabled = "";
                            if ($edit && $row['Key'] == "PRI")
                                $disabled = ' disabled="disabled"';

                            if ($filtre) {
                                $t = explode('=', $filtre);
                                if ($row['Field'] == $t[0]) {
                                    $disabled = ' disabled="disabled"';
                                    $tValues[$t[0]] = $t[1];
                                    if (substr($t[1], 0, 1) == "'")
                                        $tValues[$t[0]] = substr($t[1], 1, strlen($t[1]) - 2);
                                }
                            }
                            $comment = $row['Comment'];
                            if ($type !== null && $type !== 'ADM' && strpos(strtoupper($comment), "NE PAS TOUCHER") !== false)
                                $disabled = ' disabled="disabled"';
                            $nom = str_replace('_', ' ', $row['Field']) . ($comment ? '<br/><i style="font-size:0.7em;color:red">' . $comment . '</i>' : '');
                            $liaison = false;
                            if (is_array($tLiaison)) {
                                foreach ($tLiaison as $field => $table_liee) {
                                    $filtre = '';
                                    if (strpos($table_liee, '?') !== false) {
                                        $t = explode('?', $table_liee);
                                        $filtre = $t[1];
                                        $table_liee = $t[0];
                                    }
                                    if ($field == $row['Field']) {
                                        $liaison = true;
                                        echo '<tr><td>' . $nom . '</td>';
                                        echo '<td>';
                                        if ($row['Type'] == 'varchar(3)' || $row['Type'] == 'int(11)') {
                                            //Comptes --> liaison directe avec Personnes pour avoir nom et prenom, voire email
                                            if ($table_liee == 'comptes') {
                                                $type = '';
                                                $combo = '';
                                                if (!$tValues[$field])
                                                    $tValues[$field] = $_SESSION['id_visiteur'];
                                                $query = "SELECT c.id_compte, p.nom, p.prenom, c.email, c.type_compte
                                FROM comptes c LEFT JOIN personnes p ON p.id_compte=c.id_compte
                                ORDER BY c.type_compte DESC, p.nom ASC";
                                                $result2 = mysql_query($query);
                                                while ($row2 = mysql_fetch_array($result2)) {
                                                    if ($type != $row2['type_compte']) {
                                                        if ($type != '')
                                                            $combo.='</optgroup>';
                                                        switch ($row2['type_compte']) {
                                                            case 'PRO' :
                                                                $combo.='<optgroup label="Professionnels">';
                                                                break;
                                                            case 'PAR' :
                                                                $combo.='<optgroup label="Particuliers">';
                                                                break;
                                                            case 'PRA' :
                                                                $combo.='<optgroup label="Praticiens">';
                                                                break;
                                                            case 'AUT' :
                                                                $combo.='<optgroup label="Autres">';
                                                                break;
                                                            case 'ADM' :
                                                                $combo.='<optgroup label="Administrateurs">';
                                                                break;
                                                        }
                                                        $type = $row2['type_compte'];
                                                    }
                                                    $cle = $row2['id_compte'];
                                                    $text = trim($row2['prenom'] . ' ' . $row2['nom']);
                                                    if ($text == '')
                                                        $text = $row2['email'];
                                                    $combo.='<option value="' . $cle . '"' . ($tValues[$field] == $cle ? ' selected="selected"' : '') . '>' . $text . '</option>';
                                                }
                                                if ($type != '')
                                                    $combo.='</optgroup>';
                                                echo '<select name="' . $field . '" ' . $disabled . '><option value="">-- Aucun/tous --</option>' . $combo . '</select>';
                                            }
                                            else {
                                                DisplayFields($field, $table_liee, 'CBO', $tValues[$field], $filtre, '', $disabled);
                                            }
                                        }
                                        else
                                            DisplayFields($field, $table_liee, 'CHK', $tValues[$field], $filtre);
                                        echo '</td></tr>';
                                        break;
                                    }
                                }
                            }
                            if ($liaison == false) {
                                if (isset($_GET['duplik']) && $row['Field'] == $id_field)
                                    $tValues[$row['Field']] = '';
                                switch ($row['Type']) {
                                    case "text":
                                        echo '<tr><td colspan="2">' . $nom . '<br/>';
                                        echo '<a href="javascript:toggleEditor(\'' . $row['Field'] . '\');">Basculer le mode d\'édition</a>';
                                        echo '<textarea style="width:600px;height:400px" name="' . $row['Field'] . '" id="' . $row['Field'] . '">' . $tValues[$row['Field']] . '</textarea>';
                                        echo '</td></tr>';
                                        break;
                                    case "tinyint(1)":
                                        echo '<tr><td>' . $nom . '</td>';
                                        echo '<td>
                        <select name="' . $row['Field'] . '">
                            <option value="0"' . ($tValues[$row['Field']] == '0' ? ' selected="selected"' : '') . '>Non</option>
                            <option value="1"' . ($tValues[$row['Field']] == '1' ? ' selected="selected"' : '') . '>Oui</option>
                        </select>';
                                        echo '</td></tr>';
                                        break;
                                    case "int(11)":
                                        if ($row['Field'] == $id_field) {
                                            if ($edit) {
                                                echo '<tr><td class="required">' . $nom . '</td>';
                                                echo '<td><input type="text"' . $disabled . ' name="' . $row['Field'] . '" value="' . $tValues[$row['Field']] . '"/>';
                                                echo '</td></tr>';
                                            }
                                        } else {
                                            echo '<tr><td>' . $nom . '</td>';
                                            echo '<td><input type="text"' . $disabled . ' name="' . $row['Field'] . '" value="' . $tValues[$row['Field']] . '"/>';
                                            echo '</td></tr>';
                                        }
                                        break;
                                    case 'varchar(10)':
                                        echo '<tr><td>' . $nom . '</td>';
                                        echo '<td>';
                                        if (!isset($tValues[$row['Field']]))
                                            $tValues[$row['Field']] = date('Y-m-d');
                                        if ($tValues[$row['Field']] == '')
                                            $tValues[$row['Field']] = date('Y-m-d');
                                        ComboDate($row['Field'], $tValues[$row['Field']]);
                                        echo '</td></tr>';
                                        break;
                                    case 'varchar(255)':
                                        if (substr($row['Field'], 0, 8) == 'fichier_') {
                                            echo '<tr><td>' . $nom . '</td>';
                                            echo '<td><input type="file"' . $disabled . ' name="' . $row['Field'] . '" value=""/>';
                                            if ($tValues[$row['Field']])
                                                echo '<br/>' . $tValues[$row['Field']] . '';
                                            break;
                                        }
                                    default:
                                        $limit = '';
                                        $max = '';
                                        if (strtolower(substr($row['Type'], 0, 7)) == 'varchar') {
                                            $limit = parseInt(substr($row['Type'], 8));
                                            $max = ' (max ' . $limit . ' caractères)';
                                            $limit = ' maxlength="' . $limit . '"';
                                        }
                                        echo '<tr><td>' . $nom . $max . '<br/></td>';
                                        echo '<td>';
                                        if (substr($row['Type'], 0, 4) == 'enum') {
                                            echo '<select name="' . $row['Field'] . '">';
                                            $liste = explode(',', str_replace("'", "", substr($row['Type'], 6, strlen($row['Type']) - 7)));
                                            for ($i = 0; $i < count($liste); $i++) {
                                                echo '<option value="' . $liste[$i] . '">' . $liste[$i] . '</option>';
                                            }
                                            echo '</select>';
                                        } else {
                                            echo '<input type="text"' . $disabled . $limit . ' name="' . $row['Field'] . '" value="' . Encoding::toUTF8($tValues[$row['Field']]) . '"/>';
                                            if (substr($tValues[$row['Field']], 0, 4) == 'http') {
                                                $list = @getImageSize($tValues[$row['Field']]);
                                                if ($list === false)
                                                    echo '<br/><a href="' . $tValues[$row['Field']] . '" target="_blank">Lien direct</a>';
                                                else
                                                    echo '<img src="' . $tValues[$row['Field']] . '" style="max-width:100%"/>';
                                            }
                                        }
                                        echo '</td></tr>';
                                        break;
                                }
                            }
                        }
                        ?>
                <tr>
                    <td colspan="2" style="text-align:center">
                        <input name="btnSave" type="submit" value="Enregistrer"/>
                <?php if ($edit) {
                    ?><br/><a href="#" onclick="javascript:ConfirmClick('?delete=<?php echo $edit ?>', 'cet enregistrement');">Supprimer l'enregistrement</a>
        <?php } ?>
                    </td>
                </tr>
            </table>
        </form>
        <br/>
        <hr/>
        <br/>
        <?php
    }
}

function AdminSaveEdit($table, $filtre = '') {
    $ret = '';

    if (isset($_POST['btnSave'])) {
        $ret = 'Modifié';
        $tCols = AdminGetCols($table);
        $id_field = AdminGetPrimaryCol($table);
        $titre_field = AdminGetLibCol($table);



        for ($i = 0; $i < count($tCols); $i++) {
            ${$tCols[$i]} = '';
            if (isset($_POST[$tCols[$i]])) {
                if (is_array($_POST[$tCols[$i]]))
                    ${$tCols[$i]} = implode(',', $_POST[$tCols[$i]]);
                else
                    ${$tCols[$i]} = $_POST[$tCols[$i]];
            }
            if (isset($_POST[$tCols[$i] . '_jour'])) {
                ${$tCols[$i]} = $_POST[$tCols[$i] . '_annee'] . '-' . $_POST[$tCols[$i] . '_mois'] . '-' . $_POST[$tCols[$i] . '_jour'];
            }

            //Fichier
            if (isset($_FILES[$tCols[$i]]) && $_FILES[$tCols[$i]]['tmp_name']) {
                ${$tCols[$i]} = basename($_FILES[$tCols[$i]]['name']);
                $target = '../upload/' . $table . '/' . basename($_FILES[$tCols[$i]]['name']);
                move_uploaded_file($_FILES[$tCols[$i]]['tmp_name'], $target);

                $im = imagecreatefromjpeg($target);
                if (!$im)
                    $im = imagecreatefrompng($target);
                if (!$im)
                    $im = imagecreatefromgif($target);
                if ($im) {
                    //resize
                    $list = getimagesize($target);
                    $w0 = $list[0];
                    $h0 = $list[1];
                    $ratio = 1;
                    if ($w0 > 800) {
                        $w = 800;
                        $h = $h0 * 800 / $w0;
                        $im2 = imagecreatetruecolor($w, $h);
                        imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, $w0, $h0);
                        $im = $im2;
                        $w0 = $w;
                        $h0 = $h;
                    }

                    if ($h0 > 800) {
                        $h = 800;
                        $w = $w0 * 800 / $h0;
                        $im2 = imagecreatetruecolor($w, $h);
                        imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, $w0, $h0);
                        $im = $im2;
                    }
                    imagejpeg($im, $target, 65);
                }
            }
        }
        //Ajout
        // TODO : faire un insert avec tous les champs ?
        if (${$id_field} == '') {
            $query = "INSERT INTO " . $table . " (" . $id_field . ") VALUES ('')";
            $result = mysql_query($query);
            if (!${$id_field})
                ${$id_field} = mysql_insert_id();
            $ret = 'Ajouté';
        }
        //MAJ
        if (${$id_field} !== '') {
            $query = "SELECT * FROM " . $table . " WHERE " . $id_field . "='" . ${$id_field} . "'";
            $result = mysql_query($query);
            if (mysql_num_rows($result) == 0) {
                $query = "INSERT INTO " . $table . " (" . $id_field . ") VALUES ('" . ${$id_field} . "')";
                $result = mysql_query($query);
                if (!${$id_field})
                    ${$id_field} = mysql_insert_id();
                $query = "SELECT * FROM " . $table . " WHERE " . $id_field . "='" . ${$id_field} . "'";
                $result = mysql_query($query);
                $ret = 'Ajouté';
            }
            while ($row = mysql_fetch_array($result)) {
                for ($i = 0; $i < count($tCols); $i++) {
                    if ((isset($_FILES[$tCols[$i]]) && ${$tCols[$i]}) || !isset($_FILES[$tCols[$i]]))
                        UpdatePart($table, $tCols[$i], $id_field, ${$id_field}, $row, ${$tCols[$i]});
                }
                if ($filtre) {
                    $t = split('=', $filtre);
                    UpdatePart($table, $t[0], $id_field, ${$id_field}, $row, $t[1]);
                }
            }
        }
        if (isset($_GET['edit']))
            unset($_GET['edit']);
        if (isset($_GET['duplik']))
            unset($_GET['duplik']);
        if (isset($_GET['new']))
            unset($_GET['new']);
    }
    return $ret;
}

function AdminDeleteEdit($table) {
    $ret = '';
    if (isset($_GET['delete'])) {
        $delete = $_GET['delete'];
        $tCols = AdminGetCols($table);
        $id_field = AdminGetPrimaryCol($table);
        $titre_field = AdminGetLibCol($table);
        $query = "DELETE FROM " . $table . " WHERE " . $id_field . "='" . $delete . "'";
        $result = mysql_query($query);
        $ret = 'Enregistrement supprimé.';
        if (isset($_GET['edit']))
            unset($_GET['edit']);
        if (isset($_GET['duplik']))
            unset($_GET['duplik']);
        if (isset($_GET['new']))
            unset($_GET['new']);
    }
    return $ret;
}

/////////////////////////////////////////////
/////////////////////////////////////////////
/////////////////////////////////////////////
function AdminGetCols($table) {
    //Recherche des colonnes de la table
    $tCols = array();
    $i = 0;
    $query = "SHOW COLUMNS FROM " . $table;
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        $tCols[$i] = $row['Field'];
        $i++;
    }
    return $tCols;
}

function AdminGetPrimaryCol($table) {
    //Recherche des colonnes de la table
    $tCols = array();
    $i = 0;
    $query = "SHOW COLUMNS FROM " . $table;
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        if ($row['Key'] == "PRI")
            return $row['Field'];
        elseif (substr($row['Field'], 0, 3) == 'id_')
            return $row['Field'];
        elseif (substr($row['Field'], 0, 5) == 'code_')
            return $row['Field'];
    }
    return "";
}

function AdminGetContentCol($table) {
    //Recherche des colonnes de la table
    $tCols = array();
    $i = 0;
    $query = "SHOW FULL COLUMNS FROM " . $table;
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        if ($row['Type'] == "text" || $row['Type'] == "longtext")
            return $row['Field'];
        //echo $row['Field'].'='.$row['Type'].', ';
    }
    $query = "SHOW FULL COLUMNS FROM " . $table;
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        if (substr($row['Type'], 0, 7) == "varchar")
            return $row['Field'];
        //echo $row['Field'].'='.$row['Type'].', ';
    }
    return "";
}

function AdminGetLibCol($table) {
    //Recherche des colonnes de la table
    $tCols = array();
    $i = 0;
    $query = "SHOW COLUMNS FROM " . $table;
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        if (substr($row['Field'], 0, 4) == 'nom_' ||
                substr($row['Field'], 0, 8) == 'libelle_' ||
                substr($row['Field'], 0, 5) == 'titre' ||
                $row['Field'] == 'titre' ||
                $row['Field'] == 'raison_sociale') {
            return $row['Field'];
        }
    }
    //Pas de champ nom, libellé ou titre, alors on va concaténer les valeurs
    /* $query="SHOW COLUMNS FROM ".$table;
      $result = mysql_query($query);
      while($row = mysql_fetch_array($result))
      {
      if ($row['Key']!='PRI')
      {
      return $row['Field'];
      }
      } */
    return '';
}

function AdminGetEmailCol($table) {
    //Recherche des colonnes de la table
    $tCols = array();
    $i = 0;
    $query = "SHOW COLUMNS FROM " . $table;
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        if (substr($row['Field'], 0, 5) == 'email' ||
                substr($row['Field'], 0, 4) == 'mail_') {
            return $row['Field'];
        }
    }
    //Pas de champ nom, libellé ou titre, alors on va concaténer les valeurs
    /* $query="SHOW COLUMNS FROM ".$table;
      $result = mysql_query($query);
      while($row = mysql_fetch_array($result))
      {
      if ($row['Key']!='PRI')
      {
      return $row['Field'];
      }
      } */
    return '';
}

function AdminGetDateCol($table) {
    //Recherche des colonnes de la table
    $tCols = array();
    $i = 0;
    $query = "SHOW COLUMNS FROM " . $table;
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        if (substr($row['Field'], 0, 4) == 'date') {
            return $row['Field'];
        }
    }
    //Pas de champ nom, libellé ou titre, alors on va concaténer les valeurs
    /* $query="SHOW COLUMNS FROM ".$table;
      $result = mysql_query($query);
      while($row = mysql_fetch_array($result))
      {
      if ($row['Key']!='PRI')
      {
      return $row['Field'];
      }
      } */
    return '';
}

function AdminGetValues($table, $tCols, $id_field, $id_value = '') {
    $query = "SELECT * FROM " . $table . " WHERE " . $id_field . "='" . $id_value . "'";
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        for ($i = 0; $i < count($tCols); $i++) {
            $tValues[$tCols[$i]] = $row[$tCols[$i]];
        }
    }
    return $tValues;
}

function GraphVues($table, $filtre = '') {
    $tCols = AdminGetCols($table);
    $id_field = AdminGetPrimaryCol($table);
    $titre_field = AdminGetLibCol($table);
    if (isset($_GET['recherche']))
        $recherche = $_GET['recherche'];
    for ($i = 0; $i < count($tCols); $i++) {
        if (substr($tCols[$i], 0, 3) == 'nb_' || substr($tCols[$i], 0, 7) == 'clicks_') {
            //Graphique
            ?>
            <br/>
            <hr/>
            <br/>
            <table width="600">
                <tr class="titre">
                    <td>Graphique selon <?php echo $tCols[$i] ?></td>
                </tr>
                <tr>
                    <td>
            <?php
            $query = "SELECT * FROM " . $table . " WHERE NOT " . $titre_field . "='' ";
            if ($filtre)
                $query.=" AND " . $filtre;
            if ($recherche) {
                $query.=" AND (";
                for ($j = 0; $j < count($tCols); $j++) {
                    $t[] = $tCols[$j] . " LIKE '%" . $recherche . "%'";
                }
                $query.=implode(' OR ', $t) . ')';
            }
            $query.=" ORDER BY " . $tCols[$i] . " DESC";
            $result = mysql_query($query);
            $largeur = 100;
            $max = -1;
            while ($row = mysql_fetch_array($result)) {
                if ($max == -1)
                    $max = $row[$tCols[$i]];
                if ($max == 0)
                    $max = 1;
                echo '<div class="textgraph">
							<img style="vertical-align:middle" src="http://images.services-conjugaux.com/?image=graph/bluesky.gif" height="21" width="' . round($largeur * $row[$tCols[$i]] / $max, 0) . '">';
                $lien = '';
                switch ($table) {
                    case "evenements":
                    case "articles":
                        $lettre = substr($table, 0, 1);
                    case "appels_temoins":
                        $lettre = 't';
                        $cat = strtoupper(str_replace('_', ' ', $table)) . ' : ';
                        if (!$row['date_debut'] || ($row['date_debut'] && $row['date_debut'] > date('Y-m-d')))
                            echo '
										<a target="_blank" href="http://facebook.com/share.php?u=http://srvcgx.com/?' . $lettre . '=' . $row[$id_field] . '&title=' . $cat . $row[$titre_field] . '"><img style="vertical-align:middle" src="http://images.services-conjugaux.com/?image=32x32/facebook.png&h=21" height="21"/></a>
										<a target="_blank" href="http://twitter.com/?status=' . ($row['date_debut'] ? FormatDate($row['date_debut']) . ' - ' : '') . $cat . substr($row[$titre_field], 0, 100) . ' : http://srvcgx.com/?' . substr($table, 0, 1) . '=' . $row[$id_field] . '"><img style="vertical-align:middle" src="http://images.services-conjugaux.com/?image=32x32/twitter.png&h=21" height="21"/></a>
										<a target="_blank" href="http://www.viadeo.com/shareit/share/?title=' . $cat . substr($row[$titre_field], 0, 100) . '&url=http://srvcgx.com/?' . substr($table, 0, 1) . '=' . $row[$id_field] . '"><img style="vertical-align:middle" src="http://perso-sdt.univ-brest.fr/~wthese/merscidoc/edsm/pictures/logo_viadeo.png" height="21"/></a>
										';
                        break;
                    //http://www.viadeo.com/shareit/share/?url=http%3A%2F%2Fservices-conjugaux.com%2Findex.php%3Fsms_ss%3Dviadeo%26at_xt%3D4d9ad02e4c8ab568%252C0&title=Services+Conjugaux+en+ligne+-+Conseils%2C+forums%2C+d%C3%A9marches+en+ligne&urlaffiliate=32005&encoding=UTF-8
                }
                echo '[' . $row[$tCols[$i]] . '] <a title="Editer" href="?' . ($filtre ? $filtre . '&' : '') . 'edit=' . $row[$id_field] . '">' . ($row['date_debut'] ? FormatDate($row['date_debut']) . ' - ' : '') . $row[$titre_field] . '
							</a></div>';
            }
            ?>
                    </td>
                </tr>
            </table>
            <?php
        }
    }
}

function SetPagesResults($rows) {
    $limit = 7;
    $page = 1;
    $compte = 0;
    echo '
<div id="page1" style="display:block;position:relative">
<table width="100%">';
    for ($i = 0; $i < count($rows); $i++) {
        echo $rows[$i];
        $compte++;
        if ($compte > $limit) {
            echo '
    </table><table width="100%" style="table-layout:fixed"><tr><td align="left">';
            if ($page > 1)
                echo '<a href="#debut" onclick="ShowPage(\'' . ($page - 1) . '\');"><img src="http://pix.suiterre.fr/icones/koloria/Button_Rewind.png" title="Page ' . ($page - 1) . '"></a>';
            echo '</td><td align="center"><b>Page ' . $page . '</b></td><td style="text-align:right"> ';
            if ($i < count($rows))
                echo '<a href="#debut" onclick="ShowPage(\'' . ($page + 1) . '\');"><img src="http://pix.suiterre.fr/icones/koloria/Button_FastForward.png" title="Page ' . ($page + 1) . '"></a>';
            echo '</td></tr></table></div>';
            $compte = 0;
            $page++;
            echo '<div id="page' . $page . '" style="display:none;position:relative"><table width="100%">';
        }
    }
    if ($page > 1)
        echo '</table><table width="100%" style="table-layout:fixed"><tr><td align="left"><a href="#debut" onclick="ShowPage(\'' . ($page - 1) . '\');"><img src="http://images.services-conjugaux.com/?h=32&image=prev.png" title="Page ' . ($page + 1) . '"></a></td><td align="center"><b>Page ' . $page . '</b></td><td>&nbsp;</td></tr>';
    echo '</table></div>';
}

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
// Ajoute des balises au texte brut
function SetTxtToHtml($texte) {
    if ($texte == '')
        return '';
    if (substr(trim($texte), 0, 1) == '<')
        return $texte;
    echo strpos($texte, 0, 1);
    $texte = htmlentities($texte);
    $texte = str_replace(chr(9), ' ', $texte);
    while (strpos($texte, '  ') !== false)
        $texte = str_replace('  ', ' ', $texte);
    for ($i = 0; $i < strlen($texte); $i++) {
        $lettre = substr($texte, $i, 1);
        //Majuscule précédée d'une minuscule
        if (IsLettreMaj($lettre) && $i > 2) {
            for ($j = 1; $j < 6; $j++) {
                if (substr($texte, $i - $j, 1) !== ' ')
                    break;
            }
            $j = $i - $j;
            if (IsLettreMin(substr($texte, $j, 1)) || substr($texte, $j, 1) == ';') {
                $texte = substr($texte, 0, $j + 1) . '</p><p>' . substr($texte, $j + 1);
                $i+=10;
                /* while(substr($texte,$j,1)!==strtoupper(substr($texte,$j,1)) && $j>0)
                  {
                  $j--;
                  }
                  $j--;
                  $texte=substr($texte,0,$j).'</p><p>'.substr($texte,$j); */
            }
        }
    }
    $texte = str_replace(chr(10), '</p><p>', '<p>' . $texte . '</p>');
    $texte = str_replace(chr(13), '</p><p>', $texte);
    $texte = str_replace(chr(9), ' ', $texte);
    while (strpos($texte, '<p>&nbsp;</p>') !== false)
        $texte = str_replace('<p>&nbsp;</p>', '', $texte);
    while (strpos($texte, '<p> </p>') !== false)
        $texte = str_replace('<p> </p>', '', $texte);
    return $texte;
}

function IsLettreMaj($lettre) {
    switch ($lettre) {
        case 'A':
        case 'B':
        case 'C':
        case 'D':
        case 'E':
        case 'F':
        case 'G':
        case 'H':
        case 'I':
        case 'J':
        case 'K':
        case 'L':
        case 'M':
        case 'N':
        case 'O':
        case 'P':
        case 'Q':
        case 'R':
        case 'S':
        case 'T':
        case 'U':
        case 'V':
        case 'W':
        case 'X':
        case 'Y':
        case 'Z':
            return true;
    }
    return false;
}

function IsLettreMin($lettre) {
    switch ($lettre) {
        case 'a':
        case 'b':
        case 'c':
        case 'd':
        case 'e':
        case 'f':
        case 'g':
        case 'h':
        case 'i':
        case 'j':
        case 'k':
        case 'l':
        case 'm':
        case 'n':
        case 'o':
        case 'p':
        case 'q':
        case 'r':
        case 's':
        case 't':
        case 'u':
        case 'v':
        case 'w':
        case 'x':
        case 'y':
        case 'z':
            return true;
    }
    return false;
}

function UpdatePart($table, $field, $id_field, $id_value, $row, $value) {
    if ($row[$field] !== $value) {
        $query = "UPDATE " . $table . " SET " . $field . "='" . mysql_real_escape_string($value) . "' WHERE " . $id_field . "='" . $id_value . "'";
        mysql_query($query);
        echo mysql_error();
    }
}