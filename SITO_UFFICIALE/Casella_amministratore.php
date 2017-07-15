<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Casella Amministratore</title>
<style type="text/css">
body {
	background-color: #EDEDED;
}
p {
	font-family: Times new Roman;
	font-size: 20px;
	color: black;
	text-align: center;
	margin-top: 5%;
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
</style>
</head>
<body>
<p>QUALE OPERAZIONE VUOI EFFETTUARE?</p></br>
<input type="button" value="GESTIRE ACCOUNT" name="gestire_account" onclick="document.location.href='ModificaAccount.php'" style="margin-left: 27%" class="bottone"/>
<input type="button" value="GESTIRE MUSEI/OPERE" name="gestire_musei_opere" onclick="document.location.href='GestioneMuseo.php'" class="bottone"/>
</body>
</html>