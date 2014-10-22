<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//http://dpstream.net/film.html#r-le mépris

$tFiles = array();

include "_display.php";

$result=mysql_query("SELECT chemin_film FROM films_films 
	WHERE chemin_film LIKE '%.avi' OR largeur=320
	ORDER BY chemin_film ASC");

while($row=mysql_fetch_array($result)) {

	$path=$row['chemin_film'];

	$film = basename($path);
	if (substr($film, 0, 1) == '(')
		$film = strtolower(substr($film, 7));
	$film = strtolower(substr($film, 0, strlen($film) - 4));
	//$film = preg_replace('/[^\s0-9_\-]/u', '', $film);

	echo '<a target="_blank" href="http://dpstream.net/film.html#r-' . $film . '">' . $path . '</a><br/>';
}

//header('Location: http://dpstream.net/film.html#r-' . $film);


CloseConnection();
