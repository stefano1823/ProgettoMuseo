<?php 
	extract($_POST);
	$collegamento = 'mysql:host=localhost;dbname=my_onlinemuseum';
	try {
		$dbConn = new PDO($collegamento , 'onlinemuseum', '');
	}catch(PDOException $e) {
		echo 'Impossibile connettersi al database!';
	}
	$output='';
	$risultato= $dbConn->prepare("SELECT nome FROM elenco_musei;");
	if(!(isset($risultato))){
		echo 'Impossibile eseguire la query!';
		break;
	}
	$risultato->execute();
	while($row = $risultato->fetch(PDO::FETCH_ASSOC)){ 
		foreach ($row as $key => $value) {
			$output.="<option value=\"$value\"> $value </option>";
		}
	}
	session_start();
	if(isset($invio)) {
		$nome_museo = $_POST['COMBO'];
		$_SESSION['museo_scelto']=$nome_museo;
		header('Location: FinestraUtente.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bootstrap Agency Template</title>

<link rel="stylesheet" href="css/style1.css">
<!-- Bootstrap -->

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<form method="post">
<nav class="navbar navbar-default">
  <div class="container-fluid"> 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    </div>
    
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home<span class="sr-only">(current)</span></a> </li>
               <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">Gallery<span class="caret"></span></a>
          <select id="autore" name="COMBO" class="dropdown-menu">
			<?php print($output);?>
		  </select>
        </li>
        <li class="active"><a href="#">Eventi<span class="sr-only">(current)</span></a> </li>
        <li class="active"></li>
         <li class="active"><a href="login.php">Login <span class="sr-only">(current)</span></a> </li>
         <li class="active"><a href="registrazione.php">Registrazione <span class="sr-only">(current)</span></a> </li>
      </ul>
        <div class="form-group">
          <input type="submit" class="btn btn-default navbar-right" name="invio" value="Submit"/>
        </div>
      </form>
        
 
      
    </div>
    <!-- /.navbar-collapse --> 
  </div>
  <!-- /.container-fluid --> 
</nav>

<!-- HEADER -->
<header>
  <div class="jumbotron">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <h1 class="text-center">MUSEI ONLINE</h1>
        <img src="download.jpg" width="1142" height="600" alt=""/> </div>
      </div>
    </div>
  </div>
</header>
<!-- / HEADER --> 

<!--  SECTION-1 --><!-- FOOTER -->
<div class="container">
  <div class="row"></div>
</div>
<footer class="text-center">
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <p>Executed by : Menolascina Gerardo, Netti Stefano, Vitale Alessia</p>
      </div>
    </div>
  </div>
</footer>
<!-- / FOOTER --> 
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.3.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.js"></script>
</body>
</html>
