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
	if($userRow['privilegi']==1){
			header("Location: ../otelsy-admin/index.php");
	}
	
	
	$error = false;
	
	//Se non viene passato un codice a barre io faccio tornare indietro 
	if(empty($codiceBarre)){
	   header("Location: aggiungi.php");
	}
	
	if( isset($_POST['btn-login']) ) {	
		
		// prevent sql injections/ clear user invalid inputs
		$nomeProdotto = trim($_POST['nomeProdotto']);
		$nomeProdotto = strip_tags($nomeProdotto);
		$nomeProdotto = htmlspecialchars($nomeProdotto);
		
		$quantita = trim($_POST['quantita']);
		$quantita = strip_tags($quantita);
		$quantita = htmlspecialchars($quantita);
		
		
	
		//controllo che il nome prodotto non sia vuoto
		if(empty($nomeProdotto)){
			$error = true;
			$nomeProdottoError = "Inserire il nome del prodotto.";
		} 
		
		if(empty($quantita)){
			$error = true;
			$quantitaError = "Inserire il numero di prodotti con il codice a barre precedentemente scannerizzato.";
		} 
		
		// if there's no error, continue 
		if (!$error) {
			
			
		
			date_default_timezone_set('Europe/Rome');
		  $dt=date('Y-m-d H:i:s');
		  
			$query = mysql_query("INSERT INTO magazzino (nomeProdotto, codiceBarre, quantita,dataInserimento,dataUltimaModifica) VALUES ('".$nomeProdotto."',".$codiceBarre.", ".$quantita.",'".$dt."','".$dt."'); ");
			$query2 = mysql_query("SELECT * FROM magazzino where codiceBarre = ".$codiceBarre);
        	$prodotto=mysql_fetch_array($query2);
      		$query1=mysql_query("INSERT INTO `movimento`( `idProdotto`, `idUtente`, `nomeProdotto`, `prezzo`, `quantitaPrima`, `quantitaDopo`, `dataMovimento`) VALUES (".$prodotto['id'].",".$_SESSION['user'].",'".$prodotto['nomeProdotto']." ".$prodotto['brand']."',".$prodotto['prezzo'].",0,".$prodotto['quantita'].",'".$dt."');");
    	
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
            	<h2 class="">Prodotto e quantità da inserire</h2>
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
            	<input type="number" name="codiceBarre" class="form-control" placeholder="codiceBarre" value="<?php echo $codiceBarre; ?>" maxlength="255" disabled/>
                </div>
                <span class="text-danger"><?php echo $codiceBarreError; ?></span>
                
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
            	<input type="text" name="nomeProdotto" class="form-control" placeholder="Nome prodotto" value="<?php echo $nomeProdotto; ?>" autofocus/>
                </div>
                <span class="text-danger"><?php echo $nomeProdottoError; ?></span>
                
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
            	<input type="number" name="quantita" class="form-control" placeholder="Quantità" value="<?php echo $quantita; ?>" maxlength="255" />
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