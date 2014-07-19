<?php
require_once('config.php');

function db_connect() {
	$mysqli = new mysqli(
			$GLOBALS['db_host'],
			$GLOBALS['db_user'],
			$GLOBALS['db_pass'],
			$GLOBALS['db_name']
	);
	if (!$mysqli->select_db($GLOBALS['db_name'])) {

		if (!$mysqli->query("CREATE DATABASE ".$GLOBALS['db_name'])) {
			die("Unable to create database: ".$mysqli->error());
		}

		if (!$mysqli) {
			die("Database creation error: ".$mysqli->error());
		}

	}
	return $mysqli;
}

function db_get_all($mysqli) {
	$rows = $mysqli->query("SELECT * FROM bins");
	$bins = array();
	while ($row = mysqli_fetch_assoc($rows)) {
		array_push($bins, $row);
	}
	return $bins;
}

function db_get($mysqli, $id) {
	$row = $mysqli->query("SELECT * FROM bins WHERE url='$id'");
	return mysqli_fetch_assoc($row);
}

function db_entries($mysqli) {
	$rows = $mysqli->query("SELECT id FROM bins");
	return mysqli_num_rows($rows);
}
?>
