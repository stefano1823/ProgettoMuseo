<?php
	header( 'content-type: text/html; charset=utf-8' );
	extract($_POST);
	session_start();
	$dbConn = new mysqli('localhost', 'onlinemuseum', '','my_onlinemuseum');
	if ($dbConn) {} else {
		echo 'Impossibile connettersi al database!';
		break;
	}
	$dbConn->set_charset('utf8');
	$output='';
	if($_SESSION['azione']=='invio') {
		$co = $_SESSION['co'];
		$risultato= $dbConn->query("SELECT codice_opera, nome_opera, breve_descrizione, descrizione, luogo, autore, periodo_storico, tecnica, dimensioni, immagine_opera, audio FROM elenco_opere WHERE codice_opera = '$co';");
		if($risultato){}else{
			echo 'Impossibile eseguire la query!';
			break;
		}
	} 
	$dbConn->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Finestra Utente 1</title>
<style type="text/css">
body {
	background-color: #EDEDED;
}
h6 {
	font-family: Times new Roman;
	font-size: 17px;
	color: black;
	font-weight:lighter;
}
table,tr,td {
	border: 1px solid black;
	margin-top: -2%;
	margin-left: 2%;
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
	margin-left: 85%;
}
input.bottone:hover {
	border: 2px solid black;
}
div {
	font-family: Times new Roman;
	font-size: 20px;
	color: #DC143C;
	margin-left: 2%;
}
</style>
</head>

<body>
<form action="FinestraUtente1.php" method="post">
<?php 
if($_SESSION['azione']=='invio') {  $row = $risultato->fetch_assoc(); $codice = htmlspecialchars($row['codice_opera']); $audio1 = htmlspecialchars($row['audio']); $immagine =htmlspecialchars($row['immagine_opera']); $output.="<h6 style=\"font-weight:bold; font-size: 30px; margin-left: 2%; margin-top: -2%\">"; $output.= $row['nome_opera']; $output.="</h6>";
$output.="<img src=$immagine WIDTH=\"500\" HEIGHT=\"450\" alt=\"ERRORE\" style=\"margin-top: -3%; margin-left: 2%\"/>";
$output.= "<div style=\"margin-top: 1.5%\">LUOGO <h6 style=\"margin-top:-0.2%\">"; $output.= htmlspecialchars($row['luogo']); $output.= "</h6></div>"; 
$output.= "<div style=\"margin-top: -3%\">AUTORE <h6 style=\"margin-top:-0.2%\">"; $output.= htmlspecialchars($row['autore']); $output.= "</h6></div>";  
$output.= "<div style=\"margin-top: -3%\">PERIODO STORICO <h6 style=\"margin-top:-0.2%\">"; $output.= htmlspecialchars($row['periodo_storico']); $output.= "</h6></div>";
$output.= "<div style=\"margin-top: -3%\">TECNICA <h6 style=\"margin-top:-0.2%\">"; $output.= htmlspecialchars($row['tecnica']); $output.= "</h6></div>";
$output.= "<div style=\"margin-top: -3%\">DIMENSIONI <h6 style=\"margin-top:-0.2%\">"; $output.= htmlspecialchars($row['dimensioni']); $output.= "</h6></div>";  
$output.= "<div style=\"margin-top: -3%\">DESCRIZIONE <h6 style=\"margin-top:-0.2%\">"; $output.= htmlspecialchars($row['breve_descrizione']); $output.="</br>";  $output.= htmlspecialchars($row['descrizione']); $output.= "</h6></div>";  
} ?>
<h6 style="margin-left: 2%; margin-top: 1%"><?php print($output);?></h6></br>
<?php 
$stringa = "<div style=\"margin-left:2%; margin-top: -4%\">QR-CODE</div>";
print($stringa);
echo "<img src='/qr-code/php/qr_img.php?d=$codice' style=\"margin-left: 2%\" WIDTH=\"150\" HEIGHT=\"150\" />";
if($audio1 != null) {
	$stringa1 = "<div style=\"margin-left:2%; margin-top: 1%\">RIPRODUZIONE AUDIO</div>";
	print($stringa1);
	echo "<embed src=\"$audio1\" width=\"300\" height=\"50\" autostart =\"false\" style=\"margin-left: 2%; margin-top: 1%;\" ></embed>";
}
?>
<input type="button" value="INDIETRO" name="indietro" onclick="document.location.href='FinestraUtente.php'" class="bottone"/>
</form>
</body>
</html>