<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include '_display.php';


SetHead('Statistiques');

showGraph('Films par année', "SELECT '' as photo, annee_film AS libelle, annee_film AS indice, count(*) AS nb FROM films_films GROUP BY annee_film ORDER BY annee_film ASC", 'annee');

showGraph('Films par genre', "SELECT '' as photo, g.nom_genre AS libelle, g.id_genre AS indice, count(*) AS nb FROM films_genre_film gf INNER JOIN films_genres g ON g.id_genre=gf.id_genre GROUP BY gf.id_genre ORDER BY g.nom_genre ASC", 'genre');

showGraph('Films par acteur', "SELECT a.photo, a.nom_acteur AS libelle, a.id_acteur AS indice, count(*) AS nb FROM films_acteur_film af INNER JOIN films_acteurs a ON a.id_acteur=af.id_acteur GROUP BY af.id_acteur ORDER BY nb DESC limit 25", 'acteur');

SetFoot();


function showGraph($titre, $query, $onclick) {

$limit=250;
$max=0;
$result=mysql_query($query);
echo mysql_error();
while($row=mysql_fetch_array($result)) {
	if($row['nb']>$max) $max=$row['nb'];
}
?>
	<div>
	<h2><?php echo $titre ?></h2>
	<table width="100%" style="border-collapse:collapse;table-layout:fixed">
		<tr>
			<?php 
			$result=mysql_query($query);
			while($row=mysql_fetch_array($result)): ?>
			<td title="<?php echo $row['libelle'] ?>" style="cursor:pointer;vertical-align:bottom;text-align:center;font-size:8px;" onclick="location.href='./?<?php echo $onclick ?>=<?php echo $row['indice'] ?>'">
				<?php echo $row['nb'] ?><br/>
				<span style="display:block;background-color:white;width:100%;height:<?php echo $limit*$row['nb']/$max ?>px;background-position:center center;background-image:url('<?php echo $row['photo'] ?>'); background-size:cover;"></span>
			</td>
			<?php
			endwhile; ?>
		</tr>
		<tr>
			<?php 
			$result=mysql_query($query);
			while($row=mysql_fetch_array($result)): ?>
			<td style="vertical-align:bottom;font-size:6px;text-align:center;">
				<?php echo $row['libelle'] ?>
			</td>
			<?php
			endwhile; ?>
		</tr>
	</table>
</div>
<?php
}