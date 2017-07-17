<?php
	header( 'content-type: text/html; charset=utf-8' );
	extract($_POST);
	$dbConn = new mysqli('localhost', 'onlinemuseum', '','my_onlinemuseum');
	if (!(isset($dbConn))) {
		echo 'Impossibile connettersi al database!';
		break;
	}
	$dbConn->set_charset('utf8');
	$flag='';
	$controllocampi='';
	$esito='';
	session_start();
	$dimensione_max = '12600000';                         // Dimensione massima delle foto 
	$upload_dir = './immagini';    							// Cartella dove posizione le foto 
	$upload_dir1 = './audio'; 								// Cartella dove posizione l'audio
	$estensioni = array ('png', 'jpg', 'gif'); 
	$estensioni1 = array ('mp3','wav');	// Tipi di File consentiti 
	$noSubmitSend = 'Nessun upload eseguito!';            // Messaggio di errore quando viene richiamato direttamente lo script PHP 
	$wrongExt = 'Estensione file non valida!';            // Messaggio di errore per tipo di file non consentito 
	$tooBig = 'Il file eccede la dimensione max!';        // Messaggio di errore per file troppo grande 
	$thatsAll = 'Foto caricata con successo!';            // Messaggio di OK per upload corretto 
	$wrongUp = 'Something wrong here!';                    // Messaggio di errore quando lo script non riesce ad eseguire l'upload 
	//*************************************** 	
	
	include_once __DIR__ . '/libs/csrf/csrfprotector.php'; 
	csrfProtector::init();
	function doUpload($upload_dir) { 
	define('MAX1',122);
	define('MIN1',97);
	define('MAX2',9);
    $nomefile = $_FILES['userimage']['tmp_name']; 
    $nomereale = $_FILES['userimage']['name']; 
    $nomereale = htmlentities(strtolower($nomereale)); 
    if (is_uploaded_file($nomefile)) { 
        $newname = ($nomereale); 
         
        $ext = end(explode('.',$nomereale)); 
        $filename = explode('.',$nomereale); 
        if (file_exists($upload_dir.'/'.$nomereale)) { 
            $filename[0] .= '.'; 
            for ($a=0;$a<=MAX2;$a++) 
                $filename[0] .= chr(rand(MIN1,MAX1)); 
            $newname = $filename[0] . '.' . $ext; 
        } 
        $newname = str_replace(' ', '_', $newname); 
        move_uploaded_file($nomefile,($upload_dir.'/'.$newname)); 
		$_SESSION['nome_img1'] = $newname;
    } else print $wrongUp; 
} 
	function doUpload1($upload_dir1) {  
	define('MAX3',122);
	define('MIN2',97);
	define('MAX4',9);
    $nomefile = $_FILES['useraudio']['tmp_name']; 
    $nomereale = $_FILES['useraudio']['name']; 
    $nomereale = htmlentities(strtolower($nomereale)); 
    if (is_uploaded_file($nomefile)) { 
        $newname = ($nomereale); 
         
        $ext = end(explode('.',$nomereale)); 
        $filename = explode('.',$nomereale); 
        if (file_exists($upload_dir1.'/'.$nomereale)) { 
            $filename[0] .= '.'; 
            for ($a=0;$a<=MAX4;$a++) 
                $filename[0] .= chr(rand(MIN2,MAX3)); 
            $newname = $filename[0] . '.' . $ext; 
        } 
        $newname = str_replace(' ', '_', $newname); 
        move_uploaded_file($nomefile,($upload_dir1.'/'.$newname)); 
		$_SESSION['nome_aud'] = $newname;
    } else print $wrongUp; 
}
	$cm = $_SESSION['cm'];
	if($_SESSION['azione']=='update'){
		$co_op = $_SESSION['co1'];
		$risultato1= $dbConn->query("SELECT * FROM elenco_opere WHERE codice_opera='$co_op';");
		if(!(isset($risultato1))){
			echo 'Impossibile eseguire la query!';
			break;
		}
		$row = $risultato1->fetch_assoc();
		$codice_opera = htmlspecialchars($row['codice_opera']); $nome_opera = htmlspecialchars($row['nome_opera']); $desc = htmlspecialchars($row['breve_descrizione']); 
		$descrizione_opera = htmlspecialchars($row['descrizione']); $luogo = htmlspecialchars($row['luogo']); $nome_autore = htmlspecialchars($row['autore']); $per_sto = htmlspecialchars($row['periodo_storico']);
		$tecnica = htmlspecialchars($row['tecnica']); $dimensione = htmlspecialchars($row['dimensioni']);$immagine = $row['immagine_opera']; $audio = $row['audio'];
	}
	if(isset($crea_opera)){
		if(($codice_opera==null)||($nome_opera==null)||($desc==null)||($descrizione_opera==null)||($luogo==null)||($nome_autore==null)||($per_sto==null)||($tecnica==null)||($dimensione==null)){
			$controllocampi='<p>Compilare tutti i campi</p>';
		}else{
			$file = $_FILES['userimage']['name']; 
			if(in_array(array_pop(explode('.',$file)),$estensioni)) { 
			// Controllo la dimensione del file... 
				$dimensione_file = $_FILES['userimage']['size']; 
				if ($dimensione_file > $dimensione_max) { 
					print $tooBig; 
				} else { 
					doUpload($upload_dir); 
					$nome_immagine = $_SESSION['nome_img1'];
					$percorso_img = 'immagini/'.$nome_immagine;
					$immagine = 'immagini/'.$nome_immagine;
				} 
			} 
			$file1 = $_FILES['useraudio']['name']; 
			if(in_array(array_pop(explode('.',$file1)),$estensioni1)) { 
				// Controllo la dimensione del file... 
				$dimensione_file1 = $_FILES['useraudio']['size']; 
				if ($dimensione_file1 > $dimensione_max) { 
					print $tooBig; 
				} else { 
					doUpload1($upload_dir1); 
					$nome_audio = $_SESSION['nome_aud'];
					$percorso_aud = 'audio/'.$nome_audio;
					$audio = 'audio/'.$nome_audio;
				} 				
			} 
			if($_SESSION['azione']=='insert1'&&$flag=='') {
				$risultato= $dbConn->query("INSERT INTO elenco_opere(codice_opera,nome_opera,breve_descrizione,descrizione,luogo,autore,periodo_storico,tecnica,dimensioni,immagine_opera,audio,codice_mus)
							VALUES('$codice_opera','$nome_opera','$desc','$descrizione_opera','$luogo','$nome_autore','$per_sto','$tecnica','$dimensione','$percorso_img','$percorso_aud','$cm');");
				if(!(isset($risultato))){
					echo 'Impossibile eseguire la query!';
					break;
				}
				$esito='<p>Modifiche salvate</p>';
			} elseif($_SESSION['azione']=='update') {
				$codice_opera1 = $_POST['codice_opera']; $nome_opera1 = $_POST['nome_opera']; $descrizione1 = $_POST['desc']; $descrizione_opera1 = $_POST['descrizione_opera']; $luogo1 = $_POST['luogo'];
				$nome_autore1 = $_POST['nome_autore']; $per_sto1 = $_POST['per_sto']; $tecnica1 = $_POST['tecnica']; $dimensione1 = $_POST['dimensione']; 
				$risultato2= $dbConn->query("UPDATE elenco_opere SET codice_opera=$codice_opera1, nome_opera='$nome_opera1', breve_descrizione='$descrizione1', descrizione='$descrizione_opera1', luogo = '$luogo1', autore = '$nome_autore1', periodo_storico = '$per_sto1',tecnica='$tecnica1',dimensioni='$dimensione1', immagine_opera='$immagine', audio = '$audio' WHERE codice_opera = '$codice_opera';");
				if(!(isset($risultato2))){
					echo 'Impossibile eseguire la query!';
					break;
				}
				$esito='<p>Modifiche salvate</p>';
			}
			$risultato1= $dbConn->query("SELECT * FROM elenco_opere WHERE codice_opera='$codice_opera';");
			if(!(isset($risultato1))){
				echo 'Impossibile eseguire la query!';
				break;
			}
			$row = $risultato1->fetch_assoc();
			$codice_opera = htmlspecialchars($row['codice_opera']); $nome_opera = htmlspecialchars($row['nome_opera']); $desc = htmlspecialchars($row['breve_descrizione']); 
			$descrizione_opera = htmlspecialchars($row['descrizione']); $luogo = htmlspecialchars($row['luogo']); $nome_autore = htmlspecialchars($row['autore']); $per_sto = htmlspecialchars($row['periodo_storico']);
			$tecnica = htmlspecialchars($row['tecnica']); $dimensione = htmlspecialchars($row['dimensioni']);$immagine = $row['immagine_opera']; $audio = $row['audio'];
		}
	} elseif(isset($annulla)){
		$codice_opera = '';$nome_opera='';$desc='';$descrizione_opera='';$luogo='';$nome_autore='';$per_sto='';$tecnica='';$dimensioni='';
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