<?php
	header( 'content-type: text/html; charset=utf-8' );
	extract($_POST);
	$dbConn = new mysqli("localhost", "onlinemuseum", "","my_onlinemuseum");
	if (!$dbConn) {
		die("Impossibile connettersi: " . mysql_error());
	}
	$dbConn->set_charset("utf8");
	$flag="";
	$controllocampi="";
	$esito="";
	session_start();
	//Impostazioni varie da modificare a piacimento 
	$dimensione_max = '12600000';                         // Dimensione massima delle foto 
	$upload_dir = './immagini';    // Cartella dove posizione le foto 
	$estensioni = array ("png", "jpg", "gif");             // Tipi di File consentiti 
	$noSubmitSend = 'Nessun upload eseguito!';            // Messaggio di errore quando viene richiamato direttamente lo script PHP 
	$wrongExt = 'Estensione file non valida!';            // Messaggio di errore per tipo di file non consentito 
	$tooBig = 'Il file eccede la dimensione max!';        // Messaggio di errore per file troppo grande 
	$thatsAll = 'Foto caricata con successo!';            // Messaggio di OK per upload corretto 
	$wrongUp = 'Something wrong here!';                    // Messaggio di errore quando lo script non riesce ad eseguire l'upload 
	//*************************************** 
	 
function doUpload($file, $upload_dir) { 
    global $thatsAll; 
    $nomefile = $_FILES['userimage']['tmp_name']; 
    $nomereale = $_FILES['userimage']['name']; 
    $nomereale = htmlentities(strtolower($nomereale)); 
    if (is_uploaded_file($nomefile)) { 
        $newname = ($nomereale); 
         
        $ext = end(explode('.',$nomereale)); 
        $filename = explode('.',$nomereale); 
        if (file_exists($upload_dir.'/'.$nomereale)) { 
            $filename[0] .= '.'; 
            for ($a=0;$a<=9;$a++) 
                $filename[0] .= chr(rand(97,122)); 
            $newname = $filename[0] . '.' . $ext; 
        } 
        $newname = str_replace(' ', '_', $newname); 
        @move_uploaded_file($nomefile,($upload_dir.'/'.$newname)); 
		$_SESSION["nome_img"] = $newname;
    } else print $wrongUp; 
}  
	if($_SESSION["azione"]=="update"){
		$co_mu = $_SESSION["cm1"];
		$risultato1= $dbConn->query("SELECT * FROM elenco_musei WHERE codice_museo='$co_mu';");
		if(!$risultato1){
			die("Impossibile eseguire la query: " . mysql_error());
		}
		$row = $risultato1->fetch_assoc();
		$codice_museo = $row['codice_museo']; $nome = $row['nome']; $citta = $row['citta']; 
		$indirizzo = $row['indirizzo']; $orario_apertura = $row['orario_apertura']; $orario_chiusura = $row['orario_chiusura']; 
		$descrizione = $row['descrizione']; $immagine = $row['immagine_museo'];
	}
	if(isset($crea_museo)){
		if(($codice_museo==null)||($nome==null)||($citta==null)||($indirizzo==null)||($orario_apertura==null)||($orario_chiusura==null)||($descrizione==null)){
			$controllocampi="<p>Compilare tutti i campi</p>";
		}else{
			$file = $_FILES['userimage']['name']; 
			if(in_array(array_pop(explode('.',$file)),$estensioni)) { 
			// Controllo la dimensione del file... 
				$dimensione_file = $_FILES['userimage']['size']; 
				if ($dimensione_file > $dimensione_max) { 
					print $tooBig; 
				} else { 
					doUpload($file, $upload_dir); 
					$nome_immagine = $_SESSION["nome_img"];
					$percorso_img = "immagini/".$nome_immagine;
					$immagine = "immagini/".$nome_immagine;
				} 
			} 
			if($_SESSION["azione"]=="insert"&&$flag=="") {
				$risultato= $dbConn->query("INSERT INTO elenco_musei(codice_museo,nome,citta,indirizzo,orario_apertura,orario_chiusura,descrizione,immagine_museo)
								VALUES('$codice_museo','$nome','$citta','$indirizzo','$orario_apertura','$orario_chiusura','$descrizione','$percorso_img');");
				if(!$risultato){
					die("Impossibile eseguire la query: " . mysql_error());
				}
				$esito="<p>Modifiche salvate</p>";
			} elseif($_SESSION["azione"]=="update") {
				$codice_museo1 = $_POST['codice_museo']; $nome1 = $_POST['nome']; $citta1 = $_POST['citta']; $indirizzo1 = $_POST['indirizzo']; $orario_apertura1 = $_POST['orario_apertura'];
				$orario_chiusura1 = $_POST['orario_chiusura']; $descrizione1 = $_POST['descrizione'];
				$risultato2= $dbConn->query("UPDATE elenco_musei SET codice_museo=$codice_museo1, nome='$nome1', citta = '$citta1', indirizzo = '$indirizzo1', orario_apertura = '$orario_apertura1', orario_chiusura = '$orario_chiusura1', descrizione = '$descrizione1', immagine_museo = '$immagine' WHERE codice_museo = '$codice_museo';");
				if(!$risultato2){
					die("Impossibile eseguire la query: " . mysql_error());
				}
				$esito="<p>Modifiche salvate</p>";
			}
			$risultato1= $dbConn->query("SELECT * FROM elenco_musei WHERE codice_museo='$codice_museo';");
			if(!$risultato1){
				die("Impossibile eseguire la query: " . mysql_error());
			}
			$row = $risultato1->fetch_assoc();
			$codice_museo = $row['codice_museo']; $nome = $row['nome']; $citta = $row['citta']; 
			$indirizzo = $row['indirizzo']; $orario_apertura = $row['orario_apertura']; $orario_chiusura = $row['orario_chiusura']; 
			$descrizione = $row['descrizione']; 
		} 
	} elseif(isset($annulla)){
		$codice_museo = "";$nome="";$citta="";$indirizzo="";$oradio_apertura="";$oradio_chiusura="";$descrizione="";
	} 
	$dbConn->close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento senza titolo</title>
<style type="text/css">
body {
	background-color: #EDEDED;
}
h1 {
	font-family: Arial;
	font-size: 50px;
	color: black;
	text-align: center;
}
input.dati {
	font-family: "Times New Roman";
	font-size: 20px;
	color: black;
	background-color: white;
	border: 1px solid black;
}
textarea {
	font-family: "Times New Roman";
	font-size: 20px;
	color: black;
	background-color: white;
	border: 1px solid black;
	margin-left:27.5%;
	margin-top:-2.5%
}
p {
	font-family: Georgia;
	font-size: 20px;
	color: black;
	margin-left: 15%;
}
input.bottone {
	font-family: Georgia;
	font-size: 30px;
	font-weight: bold;
	color: black;
	background-color: #EEE8AA;
	text-align: center;
	border: 1px solid black;
}
input.bottone:hover {
	border: 2px solid black;
}
</style>
</head>

<body>
<h1>GESTIONE MUSEO</h1>
<?php print("$esito");?>
<?php print("$controllocampi");?>
<form enctype="multipart/form-data" action="Inserimento-Modifica-Museo.php" method="post">
<p>Codice Museo
  <input style="margin-left:3%" type="text" name="codice_museo" value="<?php
					if($_SESSION["azione"]=="update") { print($codice_museo);}?>" maxlength=4 class="dati" />
</p>
<p>Nome
  <input style="margin-left:9%" type="text" name="nome" value="<?php
					if($_SESSION["azione"]=="update") { print($nome);}?>"maxlength=20 class="dati" />
  <input type="submit" value="CREA/MODIFICA MUSEO" name="crea_museo" class="bottone" style="margin-left: 60%"/>
</p>
<p style="margin-top: -3%">Città
  <input style="margin-left:10%" type="text" name="citta" value="<?php
					if($_SESSION["azione"]=="update") { print($citta); }?>" maxlength=20 class="dati" />
</p>
<p>Indirizzo
  <input style="margin-left:7%" type="text" name="indirizzo" value="<?php
					if($_SESSION["azione"]=="update") { print($indirizzo); }?>" maxlength=30 class="dati" />
</p>
<p style="margin-top: -1%">Orario Apertura
  <input style="margin-left: 1.5%" type="text" name="orario_apertura" value="<?php
					if($_SESSION["azione"]=="update") { print($orario_apertura); }?>" class="dati" />
<input type="submit" value="PULISCI CAMPI" name="annulla" class="bottone" style="margin-left: 30%"/>
</p>
<p style="margin-top: -2%">(hh:mm:ss)</p>
<p>Orario Chiusura
  <input style="margin-left:1.5%" type="text" name="orario_chiusura" value="<?php
					if($_SESSION["azione"]=="update") { print($orario_chiusura); }?>" class="dati" />
</p>
<p style="margin-top: -2%">(hh:mm:ss)</p>
<p style="margin-top: -1.5%">Descrizione
</p>
<textarea rows="10" cols="50" name="descrizione" class="dati"><?php
					if($_SESSION["azione"]=="update") { print($descrizione); }?></textarea>
<p>Immagine Museo  
<input style = "margin-left: 2%" name="userimage" type="file" />   
</p>
<input type="button" value="TORNA ALL'ELENCO MUSEI" name="torna_musei" class="bottone" style="margin-top: 5%; margin-left: 65%" onclick="document.location.href='GestioneMuseo.php'"/>
</form>
</body>
</html>
