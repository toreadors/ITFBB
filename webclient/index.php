<?php
session_start();
include("functions.php");
//echo "<pre>";
//var_dump($_SESSION);
//echo "</pre><br><br>";

?>

<html>
<head>
<title>ITF-Bulletin Board</title>
</head>
<body>

<?php

if(isset($_GET['site'])) $site=$_GET['site'];
else $site="";

switch($site) {
	case "eingabe":
		eingabe();
		break;
	case "zeige":
		zeige();
		break;
	case "register":
		registrieren();
		break;
	case "login":
		login();
		break;
	case "logout":
		raushier();
		break;
	default:
		ausgabe();
}


?>
</body>
</html>
