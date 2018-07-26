<?php
	//connessione al DB
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
	
	
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Magazzino</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigationbar" aria-expanded="false" aria-controls="navbar">
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
        <li><a href="aggiungi.php"><span class="glyphicon glyphicon-plus"></span>&nbsp;Aggiungi Prodotto</a></li>
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
            	<h2 class="">Magazzino</h2>
            </div>
        
        	<div class="form-group">
            	<hr />
            </div>
            
            
            <div class="row">
              
               <?php
          			if ( isset($_SESSION['successoWithdrawl']) ) {
          				
          				?>
          				<div class="form-group">
                      	<div class="alert alert-success">
          				<span class="glyphicon glyphicon-info-sign"></span> <?php echo "Unità rimossa."; ?>
                          </div>
                      	</div>
                          <?php
          			}
          			unset($_SESSION['successoWithdrawl']);
          			?>
          			 <?php
          			if ( isset($_SESSION['successoAdd']) ) {
          				
          				?>
          				<div class="form-group">
                      	<div class="alert alert-success">
          				<span class="glyphicon glyphicon-info-sign"></span> <?php echo "Unità aggiunta."; ?>
                          </div>
                      	</div>
                          <?php
          			}
          			unset($_SESSION['successoAdd']);
          			?>
          			 <?php
          			if ( isset($_SESSION['successoRemove']) ) {
          				
          				?>
          				<div class="form-group">
                      	<div class="alert alert-success">
          				<span class="glyphicon glyphicon-info-sign"></span> <?php echo "Prodotto rimosso."; ?>
                          </div>
                      	</div>
                          <?php
          			}
          			unset($_SESSION['successoRemove']);
          			?>
          			<?php
          			if ( isset($_SESSION['fallimentoWithdrawl']) ) {
          				
          				?>
          				<div class="form-group">
                      	<div class="alert alert-danger">
          				<span class="glyphicon glyphicon-info-sign"></span> <?php echo "Non sono presenti abbastanza unità di questo prodotto, controllare di aver inserito la quantità corretta nel magazzino."; ?>
                          </div>
                      	</div>
                          <?php
          			}
          			unset($_SESSION['fallimentoWithdrawl']);
          			?>
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Prodotto</th>
                      <th>Codice</th>
                      <th>Quantità</th>
                      <!-- se voglio rimuovere un prodotto senza scannerizzare il codice a barre inserisco qui l opzione-->
                      <th>Opzioni</th>
                      
                    </tr>
                  </thead>
                  <tbody>
             
                  <?php
                  //Prendo tutto il contenuto del database
                  $query = "SELECT * FROM magazzino ORDER BY dataUltimaModifica DESC;";

                  $prodottiARR = mysql_query($query);
                  if(mysql_num_rows($prodottiARR)!=0) {
                    while($row = mysql_fetch_array($prodottiARR)){
                      $rows[] = $row;
                    }
                    
                    foreach($rows as $row){ 
                      
                      //Skippo se la quantità è zero, qui faccio vedere solo quello che c'è nel magazzino in prodotti inseriti faccio vedere tutto anche 
                      //Quelli con quantità 0
                      if( $row['quantita'] == 0){
                          continue;
                      }
                     
                      echo '<tr>';
                      echo '<td><a href="infoProdotto.php?cb='.$row['codiceBarre'].'">'.$row['brand']." ".$row['nomeProdotto']."</a> ";
                      $pngBarcode="http://bwipjs-api.metafloor.com/?bcid=code128&text=".$row['codiceBarre']."&scaleY=0.5&parsefnc&alttext=".$row['codiceBarre'];
                      echo '<td><a href="infoProdotto.php?cb='.$row['codiceBarre'].'"><img src="'.$pngBarcode.'" /> </a>'." ";
                      echo '<td>'.$row['quantita']." ";
                      echo '<td><a class="btn btn-success" href="aggiungiUno.php?id='.$row['id'].'">+1</a>';
                      echo ' ';
                      echo '<a class="btn btn-warning" href="prelevaUno.php?id='.$row['id'].'">-1</a>';
                      echo ' ';
                      echo '<a class="btn btn-danger" href="rimuoviUno.php?id='.$row['id'].'">rimuovi</a>';
                      echo '</td>';
                      echo '</tr>';
                      
                      
                    }
                    
                  }
                  else {
                      echo '<font color="red">Non ci sono prodotti nel magazzino premi il tasto + per aggiungere prodotti</font>';
                    }
                    
                   
                  
                  ?>
                  </tbody>
            </table>
            <br><br><br>
            <div style="text-align:center">
            	<div style="float:left;"><a class="btn btn-warning" href="preleva.php">Preleva con Codice a Barre</a></div>
	    
	            <div style="float:right;"><a class="btn btn-success" href="aggiungi.php">Aggiungi con Codice a Barre</a></div>
            </div>
            
            
        </div><!-- end row-->
            
            
        
        </div>
   
    </form>
    </div>	
</div>

<br><br><br><br>
<div class="container">    
<div class="row">
    	<div class="col-md-12">
			<div class="col-md-2">
				<ul class="unstyled">
					<li>GitHub<li>
					<li><a href="#">About us</a></li>
					<li><a href="#">Blog</a></li>
					<li><a href="#">Contact & support</a></li>
					<li><a href="#">Enterprise</a></li>
					<li><a href="#">Site status</a></li>
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="unstyled">
					<li>Applications<li>
					<li><a href="#">Product for Mac</a></li>
					<li><a href="#">Product for Windows</a></li>
					<li><a href="#">Product for Eclipse</a></li>
					<li><a href="#">Product mobile apps</a></li>							
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="unstyled">
					<li>Services<li>
					<li><a href="#">Web analytics</a></li>
					<li><a href="#">Presentations</a></li>
					<li><a href="#">Code snippets</a></li>
					<li><a href="#">Job board</a></li>							
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="unstyled">
					<li>Documentation<li>
					<li><a href="#">Product Help</a></li>
					<li><a href="#">Developer API</a></li>
					<li><a href="#">Product Markdown</a></li>
					<li><a href="#">Product Pages</a></li>							
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="unstyled">
					<li>Services<li>
					<li><a href="#">Help</a></li>
					<li><a href="#">Software</a></li>
					<li><a href="#">Product</a></li>
					<li><a href="#">Pages</a></li>							
				</ul>
			</div>
			<div class="col-md-2">
				<ul class="unstyled">
					<li>More<li>
					<li><a href="#">Training</a></li>
					<li><a href="#">Students & teachers</a></li>
					<li><a href="#">The Shop</a></li>
					<li><a href="#">Plans & pricing</a></li>
					<li><a href="#">Contact us</a></li>
				</ul>
			</div>					
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-8">
				<a href="#">Terms of Service</a>    
				<a href="#">Privacy</a>    
				<a href="#">Security</a>
			</div>
			<div class="span4">
				<p class="muted pull-right">© 2013 Company Name. All rights reserved</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php ob_end_flush(); ?>