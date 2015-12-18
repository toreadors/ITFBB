<?php
// Funktionen des Backends

function verbinde($host,$user,$pw,$db)
{
	$handle = new mysqli($host,$user,$pw,$db);
		if ($handle->connect_errno)
		{
			echo "Fehler beim Verbindungsaufbau: " . $handle->connect_errno;
			exit("Ursache: " . $handle->connect_error);
		}
	return $handle;
}

function holeletztethreadID()
{
	$verb_handle = verbinde("localhost", "root", "", "infosystem");
	$lastIDSQL = "SELECT threadid FROM infos ORDER BY threadid DESC LIMIT 1";
	$liderg = $verb_handle->query($lastIDSQL);
	if ($liderg->num_rows == 0) $lastthreadID = 0;
	else
	{
		$liderg->data_seek(0);
		$lidrow = $liderg->fetch_assoc();
		$lastthreadID = $lidrow['threadid'];
	}
	return $lastthreadID;
}

function getthreadid($id)
{
	$verb_handle = verbinde("localhost", "root", "", "infosystem");
	$sqlfrage = "SELECT threadid from infos where id = '$id'";
	$sqlerg = $verb_handle->query($sqlfrage);
	$sqlerg->data_seek(0);
	$ergrow = $sqlerg->fetch_assoc();
	$threadid = $ergrow['threadid'];
	return $threadid	;
}


function eintragen($handle, $text, $titel, $reply_to, $threadid)
{
    // UmySQL Injections zu erschweren
    $titel = $handle->real_escape_string($titel);
    $text = $handle->real_escape_string($text);
	$username = $handle->real_escape_string($_SESSION['uname']);
	
	$userfrage = "SELECT id FROM users WHERE username = '$username'";
	$usererg = $handle->query($userfrage);
	if ($usererg->num_rows == 0) 
	{
		echo "Nutzer nicht gefunden";
	}
	else
	{	
		$usererg->data_seek(0);
		$userrow = $usererg->fetch_assoc();
		$userid = $userrow['id'];
		$sqlfrage = "INSERT INTO infos (text, titel, timestamp, userid, threadid, inreplyto) VALUES ('$text', '$titel', now(), '$userid', '$threadid', '$reply_to')";
		//Debug
		echo $sqlfrage;
		$handle->query($sqlfrage);
		echo "<br>Eintrag erfolgt.<br><br>";
	}
}

function machesalz() {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charslength = strlen($chars);
    $randomString = '';
    for ($i = 0; $i < 22; $i++) {
        $randomString .= $chars[rand(0, $charslength - 1)];
    }
    return $randomString;
}

function verschl_passwd($pw)
{
	$salz = machesalz();
	$options = ['salt' => $salz];
	$hpw = password_hash($pw, PASSWORD_BCRYPT, $options);
	$pwarray = [ 'hpw' => $hpw, 'salz' => $salz ];
	return $pwarray;
}

function pruefe_pw($pw,$hpw,$salt)
{
	$options = ['salt' => $salt];
	$check_pw = password_hash($pw, PASSWORD_BCRYPT, $options);
	if($hpw == $check_pw) { return true; }
	else { return false; } 
}

?>
