<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	
	
		
// if session is not set this will redirect to login page
	if( !isset($_SESSION['user']) ) {
		header("Location: ../login.php");
		exit;
	}
	// select loggedin users detail
	$res=mysql_query("SELECT * FROM utente WHERE idUtente=".$_SESSION['user']);
	$userRow=mysql_fetch_array($res);
	if($userRow['privilegi']==0){
			header("Location: ../otelsy-user/index.php");
	}
	
	
	
	$error = false;
	
	
	if( isset($_POST['btn-login']) ) {	
		
		// prevent sql injections/ clear user invalid inputs
		$codiceBarreInserito = trim($_POST['codiceBarreInserito']);
		$codiceBarreInserito = strip_tags($codiceBarreInserito);
		$codiceBarreInserito = htmlspecialchars($codiceBarreInserito);
		
		
	
		//controllo che il codice a barre non sia vuoto
		if(empty($codiceBarreInserito)){
			$error = true;
			$codiceBarreInseritoError = "Inserire un codice a barre.";
		} 
		
		
		// if there's no error, continue 
		if (!$error) {
			
		
			$query="SELECT codiceBarre FROM magazzino WHERE codiceBarre='$codiceBarreInserito'";
			$query=mysql_query("SELECT codiceBarre FROM magazzino WHERE codiceBarre='$codiceBarreInserito'");
			$row=mysql_fetch_array($query);
			$count = mysql_num_rows($query); // if uname/pass correct it returns must be 1 row
			
			//Se il prodotto è già inserito nel database vengo postato ad pagina quantità 
			if( $count == 1 ) {
			    $_SESSION['codiceBarre'] = $row["codiceBarre"];
				header("Location: bivio.php");
			}
			//Se il prodotto non è inserito nel database vengo portato a pagina dove aggiungere nome descrizione quantità
			else if($count == 0) {
			    $_SESSION['codiceBarre'] = $codiceBarreInserito;
			    header("Location: aggiungiProdotto.php");
			}
			else{
			    echo "ERRORE Contattare al più presto il centro di supporto Otelsy";
			}
				
		}
		
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Check in</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><img src="image/logo.png" alt="Prodotti"></a>
      <a class="navbar-brand" href="index.php">Inventory</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        
        <li class="active"><a href="aggiungi.php"><span class="glyphicon glyphicon-plus"></span>&nbsp;Aggiungi Prodotto</a></li>
        <li><a href="preleva.php"><span class="glyphicon glyphicon-minus"></span>&nbsp;Preleva Prodotto</a></li>
        
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav> 
<br><br>
<div class="container">

	<div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
    	<div class="col-md-12">
        
        	<div style="text-align:center">
        	    <br><br><br><br>
        	    <?p
            	<a class="btn btn-warning btn-lg" href="prelevaUno.php">Preleva un singolo prodotto</a>
	            <br><br>
            	<a class="btn btn-warning btn-lg" href="preleva.php">Preleva più prodotti</a>
            	<br><br>
	    
	            <a class="btn btn-success btn-lg" href="aggiungi.php">Aggiungi un singolo prodotto</a>
                <br><br>
	            <a class="btn btn-success btn-lg" href="aggiungi.php">Aggiungi più prodotti</a>
            </div>
            
            
        
        </div>
   
    </form>
    </div>	

</div>

</body>
</html>
<?php ob_end_flush(); ?>