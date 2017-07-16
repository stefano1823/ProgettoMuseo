<?php
	header( 'content-type: text/html; charset=utf-8' );
	extract($_POST);
	$dbConn = new mysqli("localhost", "onlinemuseum", "","my_onlinemuseum");
	if (!$dbConn) {
		echo "Impossibile connettersi al database!";
		break;
	}
	$dbConn->set_charset("utf8");
	$output="";
	session_start();
	$cm = $_SESSION["museo_scelto"];
	$risultato2= $dbConn->query("SELECT codice_museo FROM elenco_musei WHERE nome = '$cm';");
	$row = $risultato2->fetch_assoc();
	$cod_mus = $row['codice_museo'];
	$risultato= $dbConn->query("SELECT codice_opera, nome_opera, breve_descrizione, immagine_opera FROM elenco_opere WHERE codice_mus = '$cod_mus';");
	if(!$risultato){
		echo "Impossibile eseguire la query!";
		break;
	}
	while(($row = $risultato->fetch_assoc()) != NULL){
		$output.="<tr>";
		$output.="<td> <input type=\"radio\" name=\"scelta\" value=\"$row[codice_opera]\"> </td>";
		$cont = 0;
		foreach ($row as $key => $value) {
			++$cont;
			if ($cont < 4) {
				$output.="<td class=\"dato\">$value</td>";
			} elseif($cont == 4) {
				$output.= "<td><img src=$value WIDTH=\"450\" HEIGHT=\"400\" alt=\"ERRORE\" /></td>";
			}
		}
		$output.="</tr>";
	}
	$risultato1= $dbConn->query("SELECT nome FROM elenco_musei WHERE codice_museo = '1200';");
	if(!$risultato1){
		echo "Impossibile eseguire la query!";
		break;
	}
	$numero_righe = $risultato1->num_rows;
	while(($row1 = $risultato1->fetch_assoc()) != NULL){
		foreach ($row1 as $key1 => $value1) {
			$output1 = $value1;
		}
	}
	if($numero_righe > 1) {
		print("Impossibile avere più musei con le stesse chiavi!");
	} 
	if(isset($vai_alla_scheda)) {
		$_SESSION["azione"]="invio";
		$_SESSION["co"]=$scelta;
		header("Location: FinestraUtente1.php");
	}
	$dbConn->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Finestra Utente</title>
<style type="text/css">
body {
	background-color: #EDEDED;
}
h1 {
	font-family: Arial;
	font-size: 50px;
	color: black;
	text-align: center;
	background-color: white;
}
p {
	font-family: Georgia;
	font-size: 35px;
	color: black;
	margin-left: 2%;
}
table,tr,td {
	text-align: center;
	margin-top: -2%;
	margin-left: 2%;
	border: 1px solid black;
}
p.colonne {
	font-family: Georgia;
	font-size: 25px;
	color: black;
}
td.dato{
	font-family: "Times New Roman";
	font-size: 15px;
	color: black;
	border: 1px solid black;
}
input.bottone {
	font-family: Georgia;
	font-size: 25px;
	font-weight: bold;
	color: black;
	background-color: #EEE8AA;
	text-align: center;
	border: 1px solid black;
	margin-top: 5%;
	margin-left: 80%;
}
input.bottone:hover {
	border: 2px solid black;
}
</style>
</head>

<body>
<h1><?php print($cm); ?></h1>
<p>Elenco Opere</p>
<form action="FinestraUtente.php" method="post">
<table width="1150">
	<tr>
		<td width="20"></td>
		<td width="150"><p class="colonne">Codice Opera</p></td>
		<td width="240"><p class="colonne">Nome Opera</p></td>
		<td width="560"><p class="colonne">Breve Descrizione</p></td>
		<td width="370"><p class="colonne">Immagine</p></td>
	</tr>
	<tr>
		<?php
			print($output);
		?>
	</tr>
</table>
	<?php
		print("<input type=\"submit\" value=\"VAI ALLA SCHEDA\" name=\"vai_alla_scheda\" class=\"bottone\"/>");
	?>
</form>
</body>
</html>