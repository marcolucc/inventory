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
	if($userRow['privilegi']==1){
			header("Location: ../otelsy-admin/index.php");
	}
	
	
	
	if ( !empty($_GET['cb'])) {
        $codiceBarre = $_REQUEST['cb'];
    }
    if ( $codiceBarre==null ) {
        header("Location: index.php");
    } 
    
	$error = false;
	
	$query1="SELECT * FROM magazzino WHERE codiceBarre=".$codiceBarre;
	echo $query1;
	$prodotto = mysql_query($query1);
    $prodotto = mysql_fetch_array($prodotto);
	
	
	if( isset($_POST['btn-login']) ) {	
		
		// prevent sql injections/ clear user invalid inputs
		
		// prevent sql injections/ clear user invalid inputs
		$quantita = trim($_POST['quantita']);
		$quantita = strip_tags($quantita);
		$quantita = htmlspecialchars($quantita);
		
		$codiceBarre = trim($_POST['codiceBarre']);
		$codiceBarre = strip_tags($codiceBarre);
		$codiceBarre = htmlspecialchars($codiceBarre);
		
		$nomeProdotto = trim($_POST['nomeProdotto']);
		$nomeProdotto = strip_tags($nomeProdotto);
		$nomeProdotto = htmlspecialchars($nomeProdotto);
	
		//controllo che il codice a barre non sia vuoto
		
		if(empty($nomeProdotto)){
			$error = true;
			$nomeProdottoError = "Inserire il nome del prodotto.";
		} 
		if(empty($quantità)){
			$quantità = 0;
		} 
		
		
		// if there's no error, continue 
		if (!$error) {
			
		
		date_default_timezone_set('Europe/Rome');
		$dt=date('Y-m-d H:i:s');
		$query2 = mysql_query("SELECT * FROM magazzino where codiceBarre = ".$codiceBarre);
        $prodotto=mysql_fetch_array($query2);
		$query1=mysql_query("INSERT INTO `movimento`( `idProdotto`, `idUtente`, `nomeProdotto`, `prezzo`, `quantitaPrima`, `quantitaDopo`, `dataMovimento`) VALUES (".$prodotto['id'].",".$_SESSION['user'].",'".$nomeProdotto." ".$prodotto['brand']."',0,".$prodotto['quantita'].",$quantita,'".$dt."');");
		    
		$query = mysql_query("UPDATE `magazzino` SET `quantita`=".$quantita.",`nomeProdotto`='".$nomeProdotto."',`dataUltimaModifica`='".$dt."' WHERE `codiceBarre`=".$codiceBarre);
		
		header("Location: infoProdotto.php?cb=".$codiceBarre);
				
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
<style type="text/css">

@page{
  size: auto;
  margin: 3mm;
}

</style>
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
        <li class="active"><a href="scan.php"><span class="glyphicon glyphicon-barcode"></span>&nbsp;Scansiona</a></li>
        <li ><a href="aggiungi.php"><span class="glyphicon glyphicon-plus"></span>&nbsp;Aggiungi Prodotto</a></li>
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
            	<h2 class="">Informazioni prodotto</h2>
            	<div align="right">
				  <button class="btn btn-primary" onclick="printContent('codice')" name="btn-login"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Stampa</button>
            
				</div>
            </div>
        
        	<div class="form-group">
            	<hr />
            </div>
            
            <!-- 
          <button onclick="printContent('div1')">Print Content</button>
          -->
            <?php
			if ( isset($_SESSION['successoCreazione']) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-success">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo "Prodotto creato ed aggiunto con successo al magazzino"; ?>
                </div>
            	</div>
                <?php
			}
			unset($_SESSION['successoCreazione']);
			?>
            
            <div class="form-group">
            	
                <?php
                    $pngBarcode="http://bwipjs-api.metafloor.com/?bcid=code128&text=".$codiceBarre."&scaleY=0.5&parsefnc&alttext=".$codiceBarre;
                    echo '<div id="codice" align="center"> <img src="'.$pngBarcode.'" />'."</div><br>";
                ?>
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
            	<input type="text" name="nomeProdotto" class="form-control" placeholder="Nome Prodotto" value="<?php echo $prodotto['nomeProdotto']; ?>" />
                </div>
                <span class="text-danger"><?php echo $nomeProdottoError; ?></span>
                 
                
            	<input type="hidden" name="codiceBarre" class="form-control" placeholder="" value="<?php echo $codiceBarre; ?>" />
                
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
            	<input type="number" name="quantita" class="form-control" placeholder="Quantità" value="<?php echo $prodotto['quantita']; ?>" />
                </div>
                <span class="text-danger"><?php echo $quantitaError; ?></span>
            </div>
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn-login">Applica Modifiche</button>
            </div>
            
            
        
        </div>
   
    </form>
    </div>	

</div>

</body>
<script>
function printContent(el){
	var restorepage = document.body.innerHTML;
	var printcontent = document.getElementById(el).innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorepage;
}
</script> 
</html>
<?php ob_end_flush(); ?>