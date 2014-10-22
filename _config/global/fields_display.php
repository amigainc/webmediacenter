<?php

function DisplayFields($name, $table, $type, $selected = '', $filter = '', $onchange = '', $disabled = '') {
    $tCols = AdminGetCols($table);
    $id_field = AdminGetPrimaryCol($table);
    $titre_field = AdminGetLibCol($table);
    $combo = '';
    $query = "SELECT * FROM " . $table;
    if ($filter)
        $query.=" WHERE " . $filter; 
	if ($titre_field)
        $query.=" ORDER BY " . $titre_field . " ASC"; 
	

	$result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        $cle = $row[$id_field];
        $text = $row[$titre_field];
        switch ($type) {
            case "CHK": echo '<input ' . $disabled . ' type="checkbox" name="' . $name . '[]" value="' . $cle . '"' . (strpos($selected, $cle) !== false ? ' checked="checked"' : '') . ($onchange ? ' onclick="' . $onchange . '"' : '') . '/>' . $text . '<br/>';
                break;
            case "RAD": echo '<input ' . $disabled . ' type="radio" name="' . $name . '" value="' . $cle . '"' . ($selected == $cle ? ' checked="checked"' : '') . ($onchange ? ' onclick="' . $onchange . '"' : '') . '/>' . $text . '<br/>';
                break;
            case "CBO": $combo.='<option value="' . $cle . '"' . ($selected == $cle ? ' selected="selected"' : '') . '>' . $text . '</option>';
                break;
        }
    } if ($combo)
	{
        echo '<select style="width:70%" ' . $disabled . ' name="' . $name . '"' . ($onchange ? ' onchange="' . $onchange . '"' : '') . '><option value="">-- Aucun/tous --</option>' . $combo . '</select>
		
		<input type="text" name="' . $name . '_filter" onkeyup="filterCbo(\''.$name.'\', $(this).val());" style="width:25%;"/>';
	}
    if ($type == "CHK")
        echo '<a href="javascript:Checking(\'' . $name . '[]\',true);">Tout sélectionner</a> / <a href="javascript:Checking(\'' . $name . '[]\',false);">Tout désélectionner</a><br/>';
}

/* RENVOI DE LA VALEUR */

function RetrieveFields($table, $selected, $type = 'TXT', $filter = '') {
    $tCols = AdminGetCols($table);
    $id_field = AdminGetPrimaryCol($table);
    $titre_field = AdminGetLibCol($table);
    $ret = '';
    $query = "SELECT * FROM " . $table . " WHERE ";
    if (strpos($selected, ',') !== false) {
        $t = explode(',', $selected);
        for ($i = 0; $i < count($t); $i++) {
            $t[$i] = $id_field . "='" . $t[$i] . "'";
        } $query.="(" . implode($t, " OR ") . ")";
    } else {
        $query.=$id_field . "='" . $selected . "'";
    } if ($filter)
        $query.=" AND " . $filter; $result = mysql_query($query);
    while ($row = mysql_fetch_array($result)) {
        switch ($type) {
            case 'TXT': $ret.=$row[$titre_field] . '<br/>';
                break;
            case 'PDF': $ret.=$row[$titre_field] . chr(10);
                break;
            case 'LST': $ret.='<li>' . $row[$titre_field] . '</li>';
                break;
        }
    } if ($ret && $type == 'LST')
        $ret = '<ul>' . $ret . '</ul>'; return $ret;
}