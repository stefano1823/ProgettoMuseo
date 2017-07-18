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
	define('UPLOAD_DIR','./immagini/');
	define('UPLOAD_DIR1','./audio/');
	define('MAX_UPLOAD_SIZE',12600000);
	$estensioni = array ("png", "jpg", "gif");
	$estensioni1 = array ('mp3','wav');
	$wrongUp = 'Something wrong here!';                    // Messaggio di errore quando lo script non riesce ad eseguire l'upload 
	//*************************************** 	
	include_once __DIR__ . '/libs/csrf/csrfprotector.php'; 
	csrfProtector::init();
	$cm = $_SESSION['cm'];
	if($_SESSION['azione']=='update'){
		$co_op = $_SESSION['co1'];
		$risultato1= $dbConn->prepare("SELECT * FROM elenco_opere WHERE codice_opera= :co_op;");
		if(!(isset($risultato1))){
			echo 'Impossibile eseguire la query!';
			break;
		}
		$risultato1->execute(array(':co_op' => $co_op));
		while($row = $risultato1->fetch(PDO::FETCH_ASSOC)){ 
			$codice_opera = htmlspecialchars($row['codice_opera']); $nome_opera = htmlspecialchars($row['nome_opera']); $desc = htmlspecialchars($row['breve_descrizione']); 
			$descrizione_opera = htmlspecialchars($row['descrizione']); $luogo = htmlspecialchars($row['luogo']); $nome_autore = htmlspecialchars($row['autore']); $per_sto = htmlspecialchars($row['periodo_storico']);
			$tecnica = htmlspecialchars($row['tecnica']); $dimensione = htmlspecialchars($row['dimensioni']);$immagine = $row['immagine_opera']; $audio = $row['audio'];
		}
	}
	if(isset($crea_opera)){
		if(($codice_opera==null)||($nome_opera==null)||($desc==null)||($descrizione_opera==null)||($luogo==null)||($nome_autore==null)||($per_sto==null)||($tecnica==null)||($dimensione==null)){
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
			$uploaded1 = $_FILES['useraudio']['name']; 
			$nomefile1 = $_FILES['useraudio']['tmp_name'];
			$uploaded1 = htmlentities(strtolower($uploaded1));
			$targetFile1 = UPLOAD_DIR1 . $_FILES['useraudio']['name'];
			$file1 = $_FILES['useraudio']['name']; 
			$uploadedSize1 = $_FILES['useraudio']['size'];
			$contentType1 = finfo_file($finfo, $nomefile1);
			if(in_array(array_pop(explode('.',$file1)),$estensioni1)) { 
				if($uploadedSize1 < MAX_UPLOAD_SIZE) {
					$imgInfo1 = getimagesize($nomefile1);
					$contentType1 = $imgInfo1['mime'];
					if(move_uploaded_file($nomefile1, $targetFile1)) {
						$percorso_aud = "audio/".$uploaded1;
						$audio = "audio/".$uploaded1;
					}
				}
			} else { 
				print $wrongExt; 
			}
			if($_SESSION['azione']=='insert1'&&$flag=='') {
				$risultato= $dbConn->prepare("INSERT INTO elenco_opere(codice_opera,nome_opera,breve_descrizione,descrizione,luogo,autore,periodo_storico,tecnica,dimensioni,immagine_opera,audio,codice_mus)
							VALUES(:codice_opera,:nome_opera,:desc,:descrizione_opera,:luogo,:nome_autore,:per_sto,:tecnica,:dimensione,:percorso_img,:percorso_aud,:cm);");
				if(!(isset($risultato))){
					echo 'Impossibile eseguire la query!';
					break;
				}
				$risultato->execute(array(':codice_opera' => $codice_opera, ':nome_opera' => $nome_opera,':desc' => $desc,':descrizione_opera' => $descrizione_opera,':luogo' => $luogo,':nome_autore' => $nome_autore,':per_sto' => $per_sto,':tecnica' => $tecnica,':dimensione' => $dimensione,':percorso_img' => $percorso_img,':percorso_aud' => $percorso_aud,':cm' => $cm));
				$esito='<p>Modifiche salvate</p>';
			} elseif($_SESSION['azione']=='update') {
				$codice_opera1 = $_POST['codice_opera']; $nome_opera1 = $_POST['nome_opera']; $descrizione1 = $_POST['desc']; $descrizione_opera1 = $_POST['descrizione_opera']; $luogo1 = $_POST['luogo'];
				$nome_autore1 = $_POST['nome_autore']; $per_sto1 = $_POST['per_sto']; $tecnica1 = $_POST['tecnica']; $dimensione1 = $_POST['dimensione']; 
				$risultato2= $dbConn->prepare("UPDATE elenco_opere SET codice_opera= :codice_opera1, nome_opera= :nome_opera1, breve_descrizione= :descrizione1, descrizione= :descrizione_opera1, luogo = :luogo1, autore = :nome_autore1, periodo_storico = :per_sto1,tecnica= :tecnica1,dimensioni= :dimensione1, immagine_opera= :immagine, audio = :audio WHERE codice_opera = :codice_opera;");
				if(!(isset($risultato2))){
					echo 'Impossibile eseguire la query!';
					break;
				}
				$risultato2->execute(array(':codice_opera1' => $codice_opera1, ':nome_opera1' => $nome_opera1,':descrizione1' => $descrizione1,':descrizione_opera1' => $descrizione_opera1,':luogo1' => $luogo1,':nome_autore1' => $nome_autore1,':per_sto1' => $per_sto1,':tecnica1' => $tecnica1,':dimensione1' => $dimensione1,':immagine' => $immagine,':audio' => $audio,':codice_opera' => $codice_opera));
				$esito='<p>Modifiche salvate</p>';
			}
			$risultato1= $dbConn->prepare("SELECT * FROM elenco_opere WHERE codice_opera= :codice_opera;");
			if(!(isset($risultato1))){
				echo 'Impossibile eseguire la query!';
				break;
			}
			$risultato1->execute(array(':codice_opera' => $codice_opera));
			while($row = $risultato1->fetch(PDO::FETCH_ASSOC)){ 
				$codice_opera = htmlspecialchars($row['codice_opera']); $nome_opera = htmlspecialchars($row['nome_opera']); $desc = htmlspecialchars($row['breve_descrizione']); 
				$descrizione_opera = htmlspecialchars($row['descrizione']); $luogo = htmlspecialchars($row['luogo']); $nome_autore = htmlspecialchars($row['autore']); $per_sto = htmlspecialchars($row['periodo_storico']);
				$tecnica = htmlspecialchars($row['tecnica']); $dimensione = htmlspecialchars($row['dimensioni']);$immagine = $row['immagine_opera']; $audio = $row['audio'];
			}
		}
	} elseif(isset($annulla)){
		$codice_opera = '';$nome_opera='';$desc='';$descrizione_opera='';$luogo='';$nome_autore='';$per_sto='';$tecnica='';$dimensione='';
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
	margin-left:25.5%;
	margin-top:-5.5%
}
p {
	font-family: Georgia;
	font-size: 20px;
	color: black;
	margin-left: 10%;
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
	border: 3px solid black;
}
</style>
</head>

<body>
<h1>GESTIONE OPERA</h1>
<?php print((string)$esito);?>
<?php print((string)$controllocampi);?>
<form enctype="multipart/form-data" action="Inserimento-Modifica-Opera.php" method="post">
<p>Codice Opera
  <input style="margin-left:7%" type="text" name="codice_opera" value="<?php
					if($_SESSION['azione']=='update') { print($codice_opera);}?>" maxlength=4 class="dati" />
</p>
<p>Nome Opera
  <input style="margin-left:7.5%" type="text" name="nome_opera" value="<?php
					if($_SESSION['azione']=='update') { print($nome_opera);}?>" maxlength=35 class="dati" />
  <input type="submit" value="CREA/MODIFICA OPERA" name="crea_opera" class="bottone" style="margin-left: 65%"/>
</p>
<p style="margin-top: -1.5%">Breve Descrizione
<input type="submit" value="PULISCI CAMPI" name="annulla" class="bottone" style="margin-left: 70%"/>
</p>
<textarea rows="5" cols="50" name="desc" class="dati" ><?php
					if($_SESSION['azione']=='update') { print($desc);}?></textarea>
					
<p>Descrizione
</p>
<textarea rows="10" cols="50" style= "margin-top: -3.5%" name="descrizione_opera" class="dati" ><?php
					if($_SESSION['azione']=='update') { print($descrizione_opera);}?></textarea>
<p>Luogo
  <input style="margin-left:12.2%" type="text" name="luogo" value="<?php
					if($_SESSION['azione']=='update') { print($nome_autore);}?>" maxlength=30 class="dati" />
</p>
<p>Autore
  <input style="margin-left:11.8%" type="text" name="nome_autore" value="<?php
					if($_SESSION['azione']=='update') { print($nome_autore);}?>" maxlength=30 class="dati" />
</p>
<p>Periodo Storico
  <input style="margin-left:5.5%" type="text" name="per_sto" value="<?php
					if($_SESSION['azione']=='update') { print($per_sto);}?>" maxlength=10 class="dati" />
</p>
<p>Tecnica
  <input style="margin-left:11.3%" type="text" name="tecnica" value="<?php
					if($_SESSION['azione']=='update') { print($tecnica);}?>" maxlength=50 class="dati" />
</p>
<p>Dimensioni
  <input style="margin-left:8.4%" type="text" name="dimensione" value="<?php
					if($_SESSION['azione']=='update') { print($dimensione);}?>" maxlength=15 class="dati" />
</p>
<p>Immagine Museo
<input style = "margin-left: 4%" name="userimage" type="file" /> 
</p>
<p>Audio
<input style = "margin-left: 12.5%" name="useraudio" type="file" /> 
</p>
<input type="button" value="TORNA ALL'ELENCO DEI MUSEI" name="torna_alla_home" class="bottone" style="margin-top: 5%; margin-left: 65%" onclick="document.location.href='Gestione-Opere.php'"/>
</form>
</body>
</html>