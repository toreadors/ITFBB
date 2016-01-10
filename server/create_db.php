<?php
$mysqli = new mysqli("localhost", "root", "", "");
if ($mysqli->connect_errno)
{
 echo "Fehler beim Verbindungsaufbau: " . $mysqli->connect_errno;
 echo "Ursache: " . $mysqli->connect_error;
}
$mysqli->query("DROP DATABASE IF EXISTS infosystem");
$mysqli->query("CREATE DATABASE infosystem");
$mysqli->select_db("infosystem");
$mysqli->query("DROP TABLE IF EXISTS infos");
$mysqli->query("DROP TABLE IF EXISTS users");
$mysqli->query("CREATE TABLE infos(id INT AUTO_INCREMENT PRIMARY KEY,text VARCHAR(512),titel VARCHAR(64), threadid INT, inreplyto INT, timestamp TIMESTAMP, userid INT(10))");
$mysqli->query("CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(64), password VARCHAR(255), salz VARCHAR(255), timestamp TIMESTAMP)");
$mysqli->query("CREATE TABLE lastchange(threadid INT UNIQUE, timestamp TIMESTAMP)");

echo "Tabelle angelegt<br />";
?>