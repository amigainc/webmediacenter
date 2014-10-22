<?php



function SetConnection() {

	global $con;

	//Connection
	$con = mysql_connect("localhost","root","");

	if (!$con) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db('films',$con);
	//Login
    LogOut();
    LogIn();
}
