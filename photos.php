<?php

include '_display.php';

$r=mysql_query("SELECT * FROM films_acteurs WHERE photo='' OR photo IS NULL ORDER BY rand()");

while($row=mysql_fetch_array($r)) {
	echo '<br/>';
	echo $row['nom_acteur'].' : ';
    $image = rawurlencode($row['nom_acteur']);
    $query = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".$image."&imgsz=large&as_filetype=jpg";

    $json = url_get_contents($query);
    $data = json_decode($json);
	$results=array();
    foreach ($data->responseData->results as $result) {
		$size=@getimagesize($result->url);
		if($size[0] && strlen($result->url)<255) {
			$results[] = array("url" => $result->url, "alt" => $result->title);
			break;
		}
    }
	if(count($results)==0) continue;
	
    echo $results[0]['url'];

    mysql_query("UPDATE films_acteurs SET photo='".mysql_real_escape_string($results[0]['url'])."' WHERE id_acteur=".$row['id_acteur']);
}
echo '<hr/>';

$r=mysql_query("SELECT a.*, (SELECT COUNT(*) FROM films_acteur_film WHERE id_acteur=a.id_acteur) nb FROM films_acteurs a WHERE a.photo!='' ORDER BY rand()");
echo mysql_error();

while($row=mysql_fetch_array($r)) {
	$size=@getimagesize($row['photo']);
	if(!$size[0]) {
		mysql_query("UPDATE films_acteurs SET photo='' WHERE id_acteur=".$row['id_acteur']);
		echo '<br/>DELETE '.$row['photo'];
	}
}
echo '<hr/>';




closeConnection();




