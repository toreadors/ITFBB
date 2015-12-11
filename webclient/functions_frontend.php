<?php
// Funktionen des Fronends

function ausgabe()
{ 
    $handle = verbinde("localhost", "root", "", "infosystem");
	if (!empty($_GET['sort'])) 
	{
		$sortart = $_GET['sort']; 
		$sortfield = $_GET['sort_field']; 
        $sortart = $handle->real_escape_string($sortart);
        $sortfield = $handle->real_escape_string($sortfield);
	}
	else
	{
		$sortfield = "timestamp";
		$sortart = "DESC";
	}
	$sqlfrage = "SELECT infos.id,titel,infos.timestamp,username FROM infos join users on userid = users.id ORDER BY $sortfield $sortart";
	$erg = $handle->query($sqlfrage);
	$erg->data_seek(0);
	$anz_rows = $erg->num_rows;
	if ($anz_rows > 0)
	{
		echo "<FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
		echo "<b>Bulletin Board by RL</b>";
		echo " <INPUT TYPE=\"submit\" VALUE=\"Aktualisieren\">";
		echo "</FORM><br>";

		if(isset($_SESSION['uname']))
		{
			echo "<FORM action=\"" . $_SERVER['PHP_SELF'] . "?site=eingabe\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Neue Nachricht\"></FORM>";
		}
		else
		{
			echo "<FORM action=\"" . $_SERVER['PHP_SELF'] . "?site=login\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Einloggen\"></FORM>";
		}
		
		echo "<table borders=0>";
		echo "<tr bgcolor=\"#ACC8F0\"><td width=\"56%\"><a href=\".?sort_field=titel&sort=DESC\">Titel</a></td>";
		echo "<td width=\"22%\"><a href=\".?sort_field=timestamp&sort=ASC\">Gepostet am</a></td>";
		echo "<td width=\"22%\" align=\"right\">von</td></tr>";
		for($count=$anz_rows; $count > 0; $count--)
		{
			$row = $erg->fetch_assoc();
			
			if(bcmod($count,'2')==0)
			{
				echo "<tr bgcolor=\"#F2F2F2\">";
			}
			else
			{
				echo "<tr bgcolor=\"#BDBDBD\">";
			}
						
			echo "<td><a href=.?site=zeige&id=" . $row['id'] . ">" . $row['titel'] . "</a></td>" ;
			echo "<td>" . $row['timestamp'] . "</td>";
			echo "<td align=\"right\">" . $row['username'] . "</td></tr>";	
		}
		echo "</table>";
		
		if(isset($_SESSION['uname']))
		{
			?>
			<br><FORM onsubmit="if(confirm('Wollen Sie sich wirklich ausloggen?') == false) {return false;}"
			action="<?php echo $_SERVER['PHP_SELF'];?>?site=logout" method="post">
			<INPUT TYPE="submit" VALUE="Ausloggen"></FORM>
			<?php
		}
		else
		{
			echo "<br><FORM action=\"" . $_SERVER['PHP_SELF'] . "?site=register\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Registrieren\"></FORM><br>";
		}
	}
	else
	{
		echo "<FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">";
		echo "<b>Bulletin Board by RL</b>";
		echo " <INPUT TYPE=\"submit\" VALUE=\"Aktualisieren\">";
		echo "</FORM><br>";
		if(isset($_SESSION['uname']))
		{
			echo "<FORM action=\"" . $_SERVER['PHP_SELF'] . "?site=eingabe\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Neue Nachricht\"></FORM><br>";
		}
		else
		{
			echo "<FORM action=\"" . $_SERVER['PHP_SELF'] . "?site=login\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Einloggen\"></FORM><br>";
		}
		echo "<b>Noch keine Eintr&auml;ge vorhanden</b>";
		if(isset($_SESSION['uname']))
		{
			?>
			<br><br><FORM onsubmit="if(confirm('Wollen Sie sich wirklich ausloggen?') == false) {return false;}"
			action="<?php echo $_SERVER['PHP_SELF'];?>?site=logout" method="post">
			<INPUT TYPE="submit" VALUE="Ausloggen"></FORM>
			<?php
		}
		else
		{
			echo "<br><FORM action=\"" . $_SERVER['PHP_SELF'] . "?site=register\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Registrieren\"></FORM><br>";
		}		
	}
}

function zeige()
{
	$handle = verbinde("localhost", "root", "", "infosystem");
	$id = $handle->real_escape_string($_GET['id']);
	$sqlfrage = "SELECT * FROM infos where id = $id";
	$erg = $handle->query($sqlfrage);
	$erg->data_seek(0);
	$row = $erg->fetch_assoc();
	if ($erg->num_rows != 0)
	{
		echo "<pre>";
		var_dump($row);
		echo "</pre>";
		echo "<b>Titel:</b> " . $row['titel'] . "<br><br>";
		echo $row['text'];
		echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "?site=reply&id=$id\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Antworten\"></FORM>";
		echo "<br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Zurück\"></FORM>";
	}
	else
	{
		echo "Datensatz nicht gefunden";
		echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Zurück\"></FORM>";
	}
}

function eingabe($reply_to)
{
	// Wenn alle Werte da sind, trage diese ein
	if(isset($_POST['eintragen']) and !empty($_POST['Text']) and !empty($_POST['Titel']))
	{	
		// verbinde mit Datenbank
		$verb_handle = verbinde("localhost", "root", "", "infosystem");
		eintragen($verb_handle, $_POST['Text'], $_POST['Titel'], $reply_to);
		echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"OK\"></FORM>";
	}
	else
	{
		?>
		<b>Neue Nachricht</b><br><br>
		<form action="./index.php?site=reply&id=<?php echo $reply_to;?>" method="post">
		Author: <?php echo $_SESSION['uname'];?><br><br>
		Titel: <input type="Text" name="Titel" size="30"><br><br>
		Text: <textarea name="Text" cols="70" rows="12"></textarea><br><br>
		<input type="Submit" name="eintragen" value="Eintragen">
		</form>
		<?php
		echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"abbrechen\"></FORM>";
	}
}

function login()
{
	// Wenn alle Werte da sind, trage diese ein
	if(isset($_POST['login']) and !empty($_POST['username']))
	{
		// verbinde mit Datenbank
		
		$uname = $_POST['username'];
		$pw = $_POST['password'];
		
		$handle = verbinde("localhost", "root", "", "infosystem");
		$usersql = "SELECT password, salz FROM users where username = '$uname'";
		$lerg = $handle->query($usersql);
		
		if ($lerg->num_rows == 0) echo "Nutzer nicht gefunden";
		else
		{
			$lerg->data_seek(0);
			$lrow = $lerg->fetch_assoc();
			$hpw = $lrow['password'];
			$salz= $lrow['salz'];
		
			echo "User: $uname mit Passwort: $pw <br>";
			if (pruefe_pw($pw,$hpw,$salz)) 
			{ 
				echo "wurde erfolgreich eingeloggt.";
				$_SESSION['uname'] = $uname;
				echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"OK\"></FORM>";
			}
			else 
			{
				echo "Falsches Paswort!";
				echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "?site=login\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"Nochmal versuchen\"></FORM>";
				
			}
		}
	}
	else
	{
		?>
		<b>Einloggen</b><br><br>
		<form action="./index.php?site=login" method="post">
		Username: <input type="Text" name="username" size="10"> 
		Passwort: <input type="Text" name="password" size="10">
		<input type="Submit" name="login" value="Login">
		</form>
		<?php
		echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"abbrechen\"></FORM>";
	}
}

function raushier()
{
	session_destroy();
	echo "Sie wurden ausgeloggt!.";
	echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"zur Startseite\"></FORM>";
}

function registrieren()
{
	// Wenn alle Werte da sind, trage diese ein
	if(isset($_POST['register']) and !empty($_POST['username']) and !empty($_POST['password']))
	{
		// verbinde mit Datenbank
		$verb_handle = verbinde("localhost", "root", "", "infosystem");
		echo "-----------<br>";
				
		$uname = $_POST['username'];
		$vpw = verschl_passwd($_POST['password']);
		$salz = $vpw['salz'];
		$hpw = $vpw['hpw'];
		$usereintrag = "INSERT INTO users (username, password, salz) VALUES ('$uname','$hpw','$salz')";
		$handle = verbinde("localhost", "root", "", "infosystem");
		$handle->query($usereintrag);
		echo "User $uname wurde angelegt";
		
		echo "<br>-----------<br>";
		echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"OK\"></FORM>";
	}
	else
	{
		?>
		<b>Neuer Nutzer</b><br><br>
		<form onsubmit="
		
		var namelaenge = this.username.value.length;
		if(namelaenge <= '2')
		{
			alert('Bitte einen Namen mit mindestens 3 Zeichen wählen');
			return false;
		} 
		var pwlaenge = this.password.value.length;
		if(pwlaenge <= '5')
		{
			alert('Bitte ein Passwort mit mindestens 6 Zeichen wählen');
			return false;
		} 
		if(this.password.value != this.password2.value)
		{
		alert('Passworteingabe unterscheidet sich!'); 
		return false;
		}
		var pattern = /\ /g;
		var uresult = pattern.test( this.username.value );
		if (uresult == true)
		{
			alert('Username darf keine Leerzeichen enthalten');
			return false;
		}
		"		
		action="./index.php?site=register" method="post">
		Username: <input type="Text" name="username" size="10"><br>
		Passwort eingeben: <input type="Text" name="password" size="10"><br>
		Passwort bestätigen: <input type="Text" name="password2" size="10">
		<input type="Submit" name="register" value="Registrieren">
		</form>
		<?php
		echo "<br><br><FORM action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\"><INPUT TYPE=\"submit\" VALUE=\"abbrechen\"></FORM>";
	}
}

?>