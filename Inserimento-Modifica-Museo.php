<?php
	header( 'content-type: text/html; charset=utf-8' );
	extract($_POST);
	$collegamento = 'mysql:host=localhost;dbname=my_onlinemuseum';
	try {
		$dbConn = new PDO($collegamento , 'onlinemuseum', '');
	}catch(PDOException $e) {
		echo 'Impossibile connettersi al database!';
	}
	$dbConn->exec('set names utf8');
	$flag='';
	$controllocampi='';
	$esito='';
	session_start();
	session_start();
	include_once __DIR__ . '/libs/csrf/csrfprotector.php'; 
	//Impostazioni varie da modificare a piacimento 
	define('UPLOAD_DIR','./immagini/');
	define('MAX_UPLOAD_SIZE',12600000);
	$estensioni = array ("png", "jpg", "gif");
	$wrongUp = 'Something wrong here!';                    // Messaggio di errore quando lo script non riesce ad eseguire l'upload 
	//*************************************** 
	
	if($_SESSION['azione']=='update'){
		$co_mu = $_SESSION['cm1'];
		$risultato1= $dbConn->prepare("SELECT * FROM elenco_musei WHERE codice_museo= :co_mu;");
		if(!(isset($risultato1))){
			echo 'Impossibile eseguire la query!';
			break;
		}
		$risultato1->execute(array(':co_mu' => $co_mu));
		while($row = $risultato1->fetch(PDO::FETCH_ASSOC)){ 
			$codice_museo = htmlspecialchars($row['codice_museo']); $nome = htmlspecialchars($row['nome']); $citta = htmlspecialchars($row['citta']); 
			$indirizzo = htmlspecialchars($row['indirizzo']); $orario_apertura = htmlspecialchars($row['orario_apertura']); $orario_chiusura = htmlspecialchars($row['orario_chiusura']); 
			$descrizione = htmlspecialchars($row['descrizione']); $immagine = $row['immagine_museo'];
		}
	}
	if(isset($crea_museo)){
		if(($codice_museo==null)||($nome==null)||($citta==null)||($indirizzo==null)||($orario_apertura==null)||($orario_chiusura==null)||($descrizione==null)){
			$controllocampi='<p>Compilare tutti i campi</p>';
		}else{
			$uploaded = $_FILES['userimage']['name']; 
			$nomefile = $_FILES['userimage']['tmp_name'];
			$uploaded = htmlentities(strtolower($uploaded));
			$targetFile = UPLOAD_DIR . $_FILES['userimage']['name'];
			$file = $_FILES['userimage']['name']; 
			$uploadedSize = $_FILES['userimage']['size'];
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$contentType = finfo_file($finfo, $nomefile);
			if(in_array(array_pop(explode('.',$file)),$estensioni)) { 
				if($uploadedSize < MAX_UPLOAD_SIZE) {
					$imgInfo = getimagesize($nomefile);
					$contentType = $imgInfo['mime'];
					if(move_uploaded_file($nomefile, $targetFile)) {
						$percorso_img = 'immagini/'.$uploaded;
						$immagine = 'immagini/'.$uploaded;
					}
				}
			} else { 
				print $wrongExt; 
			} 
			if($_SESSION['azione']=='insert'&&$flag=='') {
				$risultato= $dbConn->prepare("INSERT INTO elenco_musei(codice_museo,nome,citta,indirizzo,orario_apertura,orario_chiusura,descrizione,immagine_museo)
								VALUES(:codice_museo,:nome,:citta,:indirizzo,:orario_apertura,:orario_chiusura,:descrizione,:percorso_img);");
				if(!(isset($risultato))){
					echo 'Impossibile eseguire l inserimento!';
					break;
				}
				$risultato->execute(array(':codice_museo' => $codice_museo, ':nome' => $nome,':citta' => $citta,':indirizzo' => $indirizzo,':orario_apertura' => $orario_apertura,':orario_chiusura' => $orario_chiusura,':descrizione' => $descrizione,':percorso_img' => $percorso_img));
				$esito='<p>Modifiche salvate</p>';
			} elseif($_SESSION['azione']=='update') {
				$codice_museo1 = $_POST['codice_museo']; $nome1 = $_POST['nome']; $citta1 = $_POST['citta']; $indirizzo1 = $_POST['indirizzo']; $orario_apertura1 = $_POST['orario_apertura'];
				$orario_chiusura1 = $_POST['orario_chiusura']; $descrizione1 = $_POST['descrizione'];
				$risultato2= $dbConn->prepare("UPDATE elenco_musei SET codice_museo= :codice_museo1, nome= :nome1, citta = :citta1, indirizzo = :indirizzo1, orario_apertura = :orario_apertura1, orario_chiusura = :orario_chiusura1, descrizione = :descrizione1, immagine_museo = :immagine WHERE codice_museo = :codice_museo;");
				if(!(isset($risultato2))){
					echo 'Impossibile eseguire la modifica!';
					break;
				}
				$risultato2->execute(array(':codice_museo1' => $codice_museo1, ':nome1' => $nome1,':citta1' => $citta1,':indirizzo1' => $indirizzo1,':orario_apertura1' => $orario_apertura1,':orario_chiusura1' => $orario_chiusura1,':descrizione1' => $descrizione1,':immagine' => $immagine, ':codice_museo' => $codice_museo));
				$esito='<p>Modifiche salvate</p>';
			}
			$risultato1= $dbConn->prepare("SELECT * FROM elenco_musei WHERE codice_museo= :codice_museo;");
			if(!(isset($risultato1))){
				echo 'Impossibile eseguire la query!';
				break;
			}
			$risultato1->execute(array(':codice_museo' => $codice_museo));
			while($row = $risultato1->fetch(PDO::FETCH_ASSOC)){ 
				$codice_museo = htmlspecialchars($row['codice_museo']); $nome = htmlspecialchars($row['nome']); $citta = htmlspecialchars($row['citta']); 
				$indirizzo = htmlspecialchars($row['indirizzo']); $orario_apertura = htmlspecialchars($row['orario_apertura']); $orario_chiusura = htmlspecialchars($row['orario_chiusura']); 
				$descrizione = htmlspecialchars($row['descrizione']); $immagine = $row['immagine_museo'];
			}
		}  
	} elseif(isset($annulla)){
		$codice_museo = '';$nome='';$citta='';$indirizzo='';$orario_apertura='';$orario_chiusura='';$descrizione='';
	} 
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
<?php print((string)$esito);?>
<?php print((string)$controllocampi);?>
<form enctype="multipart/form-data" action="Inserimento-Modifica-Museo.php" method="post">
<p>Codice Museo
  <input style="margin-left:3%" type="text" name="codice_museo" value="<?php
					if($_SESSION['azione']=='update') { print($codice_museo);}?>" maxlength=4 class="dati" />
</p>
<p>Nome
  <input style="margin-left:9%" type="text" name="nome" value="<?php
					if($_SESSION['azione']=='update') { print($nome);}?>"maxlength=20 class="dati" />
  <input type="submit" value="CREA/MODIFICA MUSEO" name="crea_museo" class="bottone" style="margin-left: 60%"/>
</p>
<p style="margin-top: -3%">Città
  <input style="margin-left:10%" type="text" name="citta" value="<?php
					if($_SESSION['azione']=='update') { print($citta); }?>" maxlength=20 class="dati" />
</p>
<p>Indirizzo
  <input style="margin-left:7%" type="text" name="indirizzo" value="<?php
					if($_SESSION['azione']=='update') { print($indirizzo); }?>" maxlength=30 class="dati" />
</p>
<p style="margin-top: -1%">Orario Apertura
  <input style="margin-left: 1.5%" type="text" name="orario_apertura" value="<?php
					if($_SESSION['azione']=='update') { print($orario_apertura); }?>" class="dati" />
<input type="submit" value="PULISCI CAMPI" name="annulla" class="bottone" style="margin-left: 30%"/>
</p>
<p style="margin-top: -2%">(hh:mm:ss)</p>
<p>Orario Chiusura
  <input style="margin-left:1.5%" type="text" name="orario_chiusura" value="<?php
					if($_SESSION['azione']=='update') { print($orario_chiusura); }?>" class="dati" />
</p>
<p style="margin-top: -2%">(hh:mm:ss)</p>
<p style="margin-top: -1.5%">Descrizione
</p>
<textarea rows="10" cols="50" name="descrizione" class="dati"><?php
					if($_SESSION['azione']=='update') { print($descrizione); }?></textarea>
<p>Immagine Museo  
<input style = "margin-left: 2%" name="userimage" type="file" />   
</p>
<input type="button" value="TORNA ALL'ELENCO MUSEI" name="torna_musei" class="bottone" style="margin-top: 5%; margin-left: 65%" onclick="document.location.href='GestioneMuseo.php'"/>
</form>
</body>
</html>
