<?php
(include 'core.php');
include_once __DIR__ . '/libs/csrf/csrfprotector.php'; 
csrfProtector::init();
if(isset($_POST['register'])) {
	define('MAX1',16);
	define('MAX2',6);
	define('MAX3',20);
	define('MAX4',60);
	$username = isset($_POST['username']) ? clear($_POST['username']) : false;
	$password = isset($_POST['password']) ? clear($_POST['password']) : false;
	$email = isset($_POST['email']) ? clear($_POST['email']) : false;
	if(empty($username) || empty($password) || empty($email)) {
		$stringa1 = 'Riempi tutti i campi.<br /><br /><a href="javascript:history.back();">Indietro</a>';
		print($stringa1);
	} elseif(strlen($username) > MAX1) {
		$stringa2 = 'Username troppo lungo. Massimo 16 caratteri.<br /><br /><a href="javascript:history.back();">Indietro</a>';
		print($stringa2);
	} elseif(strlen($password) < MAX2 || strlen($password) > MAX3) {
		$stringa3 = 'Lunghezza della password non valida. Minimo 6 caratteri e massimo 20.<br /><br /><a href="javascript:history.back();">Indietro</a>';
		print($stringa3);
	} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$stringa4 ='Indirizzo email non valido.';
		print($stringa4);
	} elseif(strlen($email) > MAX4) {
		$stringa5 = "Lunghezza dell' indirizzo email non valida. Massimo 60 caratteri.<br /><br /><a href='javascript:history.back();'>Indietro</a>";
		print($stringa5);
	} elseif(mysql_num_rows(mysql_query("SELECT * FROM users WHERE username LIKE '$username'")) > 0) {
		$stringa6 = 'Username gi� in uso. Sei pregato di sceglierne un altro.<br /><br /><a href="javascript:history.back();">Indietro</a>';
		print($stringa6);
	} elseif(mysql_num_rows(mysql_query("SELECT * FROM users WHERE email LIKE '$email'")) > 0) {
		$stringa7 = 'Indirizzo email gi� in uso. Sei pregato di sceglierne un altro.<br /><br /><a href="javascript:history.back();">Indietro</a>';
		print($stringa7);
	} else {
		if(mysql_query("INSERT INTO users (username, password, email) VALUES ('$username','$password','$email')")) {
			$stringa8 = 'Registrazione andata a buon fine.<br /><br /><a href="javascript:window.history.go(-2);">Indietro</a>';
			print($stringa8);
		} else {
			$stringa9 = 'Errore nella query: '.mysql_error();
			print($stringa9);
		}
	}
} else {
	?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Your Website</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css" type="text/css" />
<script type="text/javascript"></script>
</head>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<div class="container">
	<section id="content">
		<h1>Form di Registrazione</h1>
		<form>
			<div class="wrapper">
				<i class="fa fa-user"></i>
				<input type="text" name="username" placeholder="Username" required maxlength="16"/>
			</div>
			<div class="wrapper">
				<i class="fa fa-lock"></i>
				<input type="password" name="password" placeholder="Password" required maxlength="20"/>
			</div>
			<div class="wrapper">
				<input type="email" name="email" placeholder="Email" required maxlength="60"/>
			</div>
			<div>
				<input type="submit" name="register" value="Registrati" />
			</div>
		</form>
	</section>
</div>
</form>
</body>
</html>
	<?php
}
?>
