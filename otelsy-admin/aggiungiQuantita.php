<?php
	ob_start();
	session_start();
	require_once 'dbconnect.php';
	$codiceBarre = $_SESSION['codiceBarre'];
	
	
		
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
	
	//Se non viene passato un codice a barre io faccio tornare indietro 
	if(empty($codiceBarre)){
	    header("Location: aggiungi.php");
	}
	
	 $query = mysql_query("SELECT * FROM magazzino where codiceBarre = ".$codiceBarre);
   $prodotto=mysql_fetch_array($query);
	
	if( isset($_POST['btn-login']) ) {	
		
		// prevent sql injections/ clear user invalid inputs
		$quantita = trim($_POST['quantita']);
		$quantita = strip_tags($quantita);
		$quantita = htmlspecialchars($quantita);
		
		
	
		//controllo che il codice a barre non sia vuoto
		if(empty($quantita)){
			$error = true;
			$quantitaError = "Inserire il numero di prodotti con il codice a barre precedentemente scannerizzato.";
		} 
		
		
		// if there's no error, continue 
		if (!$error) {
			
			
		    	$query2 = mysql_query("SELECT * FROM magazzino where codiceBarre = ".$codiceBarre);
        	$prodotto=mysql_fetch_array($query2);
            $query3=mysql_query("INSERT INTO `movimento`( `idProdotto`, `idUtente`, `nomeProdotto`, `prezzo`, `quantitaPrima`, `quantitaDopo`, `dataMovimento`) VALUES (".$prodotto['id'].",".$_SESSION['user'].",'".$prodotto['nomeProdotto']." ".$prodotto['brand']."',".$prodotto['prezzo'].",".$prodotto['quantita'].",".$prodotto['quantita']."+".$quantita.",'".$dt."');");
			$query=mysql_query("UPDATE magazzino SET quantita = quantita + ".$quantita." WHERE codiceBarre = ".$codiceBarre);
			unset($_SESSION['codiceBarre']);
			$_SESSION['successo']=1;
			header("Location: aggiungi.php");
			
				
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
        <li><a href="scan.php"><span class="glyphicon glyphicon-barcode"></span>&nbsp;Scansiona</a></li>
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
        
        	<div class="form-group">
            	<h2 class="">Quantità da inserire</h2>
            </div>
        
        	<div class="form-group">
            	<hr />
            </div>
            
            <?php
			if ( isset($errMSG) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-danger">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
            	</div>
                <?php
			}
			?>
            
            <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
            	<input type="text" name="nomeProdotto" class="form-control" placeholder="Nome Prodotto" value="<?php echo $prodotto['nomeProdotto']; ?>" disabled/>
                </div>
                 
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
            	<input type="number" name="codiceBarre" class="form-control" placeholder="Codice a Barre" value="<?php echo $codiceBarre; ?>" disabled/>
                </div>
                
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
            	<input type="number" name="quantita" class="form-control" placeholder="Quantità" value="<?php echo $quantita; ?>" maxlength="255" autofocus/>
                </div>
                <span class="text-danger"><?php echo $quantitaError; ?></span>
            </div>
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn-login">Inserisci</button>
            </div>
            
            
        
        </div>
   
    </form>
    </div>	

</div>

</body>
</html>
<?php ob_end_flush(); ?>