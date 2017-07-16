<?php
	extract($_POST);
	$dbConn = new mysqli("localhost", "onlinemuseum", "","my_onlinemuseum");
	if (!$dbConn) {
		echo "Impossibile connettersi al database!";
		break;
	}
	$output="";
	$flag = false;
	$esito = "";
	$controllocampi="";
	session_start();
	
	$risultato= $dbConn->query("SELECT id, username, password, email FROM users WHERE tipo = '0';");
	if(!$risultato){
		echo "Impossibile eseguire la query!";
		break;
	}
	while(($row = $risultato->fetch_assoc()) != null){
		$output.="<tr>";
		$output.="<td> <input type=\"radio\" name=\"scelta\" value=\"$row[id]\"> </td>";
		foreach ($row as $key => $value) {
			$output.="<td class=\"dato\">$value</td>";
		}
		$output.="</tr>";
	}
	if(isset($elimina_account)) {
		$codice_account=$scelta;
		$risultato1= $dbConn->query("DELETE FROM users WHERE id = '$codice_account';");
		if(!$risultato1){
			echo "Impossibile eseguire la query!";
			break;
		}
		$esito="<p>Account Eliminato</p>";
		header("Location: ModificaAccount.php");
	} elseif(isset($modifica)) {
		$flag = true;
		$co_ac = $scelta;
		$_SESSION["ca"]=$co_ac;
		$risultato2= $dbConn->query("SELECT username, password, email FROM users WHERE id='$co_ac';");
		if(!$risultato2){
			echo "Impossibile eseguire la query!";
			break;
		}
		$row1 = $risultato2->fetch_assoc();
		$username = htmlspecialchars($row1['username']); $password = htmlspecialchars($row1['password']); $email = htmlspecialchars($row1['email']);
	}
	if(isset($modifica_account)) {
		$ident = $_SESSION["ca"];
			if(($username==null)||($password==null)||($email==null)){
				$controllocampi="<p>Compilare tutti i campi</p>";
			} else {
				$username1 = $_POST['username']; $password1 = $_POST['password']; $email1 = $_POST['email'];
				$risultato3= $dbConn->query("UPDATE users SET username='$username1', password = '$password1', email = '$email1' WHERE id = '$ident';");
				if(!$risultato3){
					echo "Impossibile eseguire la query!";
					break;
				}
				$esito="<p>Modifiche salvate</p>";
				$risultato2= $dbConn->query("SELECT username, password, email FROM users WHERE id='$ident';");
				if(!$risultato2){
					echo "Impossibile eseguire la query!";
					break;
				}
				$row1 = $risultato2->fetch_assoc();
				$username = htmlspecialchars($row1['username']); $password = htmlspecialchars($row1['password']); $email = htmlspecialchars($row1['email']);
			}
	}elseif(isset($annulla)){
		$username = "";$password="";$email="";
	} 
	$dbConn->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modifica Account</title>
<style type="text/css">
body {
	background-color: #EDEDED;
}
h1 {
	font-family: Arial;
	font-size: 40px;
	color: black;
	text-align: center;
}
table.contenitore,tr.contenitore ,td.contenitore  {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
}
input.dati {
	font-family: "Times New Roman";
	font-size: 20px;
	color: black;
	background-color: white;
	border: 1px solid black;
}
p {
	font-family: Georgia;
	font-size: 20px;
	color: black;
	margin-left: 5%;
}
input.bottone {
	font-family: Georgia;
	font-size: 25px;
	font-weight: bold;
	color: black;
	background-color: #EEE8AA;
	text-align: center;
	border: 1px solid black;
}
input.bottone:hover {
	border: 2px solid black;
}
p.colonne {
	font-family: Georgia;
	font-size: 20px;
	color: black;
}
table,tr,td {
	text-align: center;
	margin-left: 4%;
	border: 1px solid black;
}
p.nome {
	font-family: Georgia;
	font-size: 25px;
	color: black;
	margin-left: 5%;
}
</style>
</head>

<body>
<form action="ModificaAccount.php" method="post">
<h1>GESTIONE ACCOUNT</h1>
<?php print("$esito"); ?>
<?php print("$controllocampi");?>
<table width="1000">
	<tr>
		<td width="10"></td>
		<td width="40"><p class="colonne">ID</p></td>
		<td width="170"><p class="colonne">Username</p></td>
		<td width="170"><p class="colonne">Password</p></td>
		<td width="170"><p class="colonne">Email</p></td>
	</tr>
	<tr>
		<?php
			print($output);
		?>
	</tr>
</table>
<table class="contenitore">
	<tr class="contenitore">
		<td class="contenitore"><input type="submit" value="ELIMINA ACCOUNT" name="elimina_account" class="bottone" style="margin-top: 5%; margin-left: 40%"/></td>
		<td class="contenitore"><input type="submit" value="MODIFICA ACCOUNT" name="modifica" class="bottone" style="margin-top: 5%; margin-left: 50%"/></td>
	</tr>
</table>
<p class="nome">MODIFICA ACCOUNT</p>
<p>Username
  <input style="margin-left:3.5%" type="text" name="username" value="<?php
					if($flag == true) { print($username);}?>" maxlength=16 class="dati" />
  <input type="submit" value="MODIFICA ACCOUNT" name="modifica_account" class="bottone" style="margin-left: 35%"/>
</p>
<p>Password
  <input style="margin-left:3.8%" type="text" name="password" value="<?php
					if($flag == true) { print($password);}?>" maxlength=32 class="dati" />
   <input type="submit" value="PULISCI CAMPI" name="annulla" class="bottone" style="margin-left: 38%"/>
</p>
<p>Email
	<input style="margin-left:6.5%" type="text" name="email"  value="<?php
					if($flag == true) { print($email);}?>"  maxlength=60 class="dati" />
</p>
<input type="button" value="TORNA ALLA HOME" name="torna_alla_home" class="bottone" style="margin-left: 75%; margin-top: 10%" onclick="document.location.href='index.php'"/>
</form>
</body>
</html>