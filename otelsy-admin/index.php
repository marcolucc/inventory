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
<link rel="stylesheet" href="assets/css/card.css" type="text/css" />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<style type="text/css">
	.blue{
		background: blue; /* make this whatever you want */
	}
</style>
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
	<div class="row">

		<section class="content">
			<h1>Dashboard</h1>
			<div class="col-md-8">
				<h3 class="title">
					Ultimi 7 giorni
				</h3>
			
				<div class="panel panel-default">
					<div class="panel-body">
						
						<div class="table-container">
							<table class="table table-filter">
								<tbody>
									<?php
                  //Prendo tutto il contenuto del database
                  $ciclo =0;
                  $query = "SELECT * FROM magazzino WHERE dataUltimaModifica > DATE_SUB(NOW(), INTERVAL 7 DAY) ORDER BY dataUltimaModifica DESC;";
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
                      
                    	echo '<tr data-status="last">';
                    	echo '<td>';
                      echo '<div class="media-body">';
                      echo '<span class="media-meta pull-right">'.$row['dataUltimaModifica'].'</span>';
                      echo '<h4 class="title">';
                      echo $row['brand'].$row['nomeProdotto'];
                      echo '</h4>';
                      echo '<p class="summary">Sono presenti '.$row['quantita'].' unità <br> Costo Unitario: '.$row['prezzo'].' <br> Costo totale: '.$row['prezzo']*$row['quantita'].'';
                      echo '<span class="pull-right "><mark>DEPOSITO</mark></span>';
                      echo '</p>';
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                      
                      $ciclo++;
                      
                    }
                    
                  }
                  else {
                      echo '<font color="red">Non ci sono prodotti nel magazzino premi il tasto + per aggiungere prodotti</font>';
                    }
                    
                   
                  
                  ?>
												
											

								</tbody>
							</table>
						</div>
						
					</div>
					
				</div>
				
				
		</section>
		
		<div class="col-md-4">
				<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<div id="card" class="weater">
					<div class="city-selected">
						<article>

							<div class="info">
								<h2>Valore Magazzino</h2>
								<div class="night">Aggiornato al <?php echo (new \DateTime())->format('Y-m-d H:i'); ?></div>
								<?php 
								$result = mysql_query('SELECT SUM(prezzo * quantita) AS value_sum FROM magazzino'); 
								$row = mysql_fetch_assoc($result); 
								$sum = $row['value_sum'];
								echo '<div class="temp">'.$sum.' €</div>';
								?>
								

								
							</div>

							<div class="icon">
							
							</div>

						</article>
						
						<figure style="background-image: url(image/logo.png)"></figure>
					</div>

					<div class="days">
						<div class="row row-no-gutter">
							<div class="col-md-4">
								<div class="day">
									<h1>Numero prodotti presenti</h1>
									<?php 
										$result = mysql_query('SELECT COUNT(*) as value_count FROM magazzino'); 
										$row = mysql_fetch_assoc($result); 
										$count = $row['value_count'];
										echo $count;
									?>
								</div>
							</div>

							<div class="col-md-4">
								<div class="day">
									<h1>Prodotti in esaurimento</h1>
									<?php 
										$result = mysql_query('SELECT COUNT(*) as prodottiEs FROM magazzino WHERE quantita <= quantitaCritica AND quantita != 0'); 
										$row = mysql_fetch_assoc($result); 
										$count = $row['prodottiEs'];
										echo $count;
									?>
								</div>
							</div>

							<div class="col-md-4">
								<div class="day">
									<h1>Prodotti inseriti nell'ultimo mese</h1>
									<?php 
										$result = mysql_query('SELECT COUNT(*) as prodottiMese FROM magazzino WHERE dataUltimaModifica > DATE_SUB(NOW(), INTERVAL 30 DAY)'); 
										$row = mysql_fetch_assoc($result); 
										$count = $row['prodottiMese'];
										echo $count;
									?>
									
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
			
		</div><!--<div class="col-md-4"> -->
		
		
	</div>
</div>
</div>

<div class="container">
	<div class="row">
		<div class="col">
		<section class="content">
		
				<h3 class="title">
					Prodotti in esaurimento
				</h3>
			
				<div class="panel panel-default">
					<div class="panel-body">
						
						<div class="table-container">
							<table class="table table-filter">
								<tbody>
									<?php
                  //Prendo tutto il contenuto del database
                  
                  $query1 = "SELECT * FROM magazzino WHERE quantita <= quantitaCritica AND quantita != 0;";
									$prodottiARR1 = mysql_query($query1);
                  if(mysql_num_rows($prodottiARR1)!=0) {
                    while($row1 = mysql_fetch_array($prodottiARR1)){
                      $rows1[] = $row1;
                  	}
                  foreach($rows1 as $row1){ 
                      
                      //Skippo se la quantità è zero, qui faccio vedere solo quello che c'è nel magazzino in prodotti inseriti faccio vedere tutto anche 
                      //Quelli con quantità 0
                      
                    	echo '<tr data-status="last">';
                    	echo '<td>';
                      echo '<div class="media-body">';
                      echo '<span class="media-meta pull-right">'.$row1['dataUltimaModifica'].'</span>';
                      echo '<h4 class="title">';
                      echo $row1['brand'].$row1['nomeProdotto'];
                      echo '</h4>';
                      echo '<p class="summary"> Sta per teminare';
                      echo '<span class="pull-right "><mark>IN ESAURIMENTO</mark></span>';
                      echo '</p>';
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                      
                      
                      
                    }
                    
                  }
                  else {
                      echo '<font>Non ci sono prodotti in esaurimento</font>';
                    }
                    
                   
                  
                  ?>
												
											

								</tbody>
							</table>
						</div>
						
					</div>
					
				</div>
				
				
		</section>
		
		
		
		
	</div>
	<div class="col">
		<section class="content">
		
				<h3 class="title">
					Prodotti terminati
				</h3>
			
				<div class="panel panel-default">
					<div class="panel-body">
						
						<div class="table-container">
							<table class="table table-filter">
								<tbody>
									<?php
                  //Prendo tutto il contenuto del database
                  
                  $query2 = "SELECT * FROM magazzino WHERE quantita = 0;";
									$prodottiARR2 = mysql_query($query2);
                  if(mysql_num_rows($prodottiARR2)!=0) {
                    while($row2 = mysql_fetch_array($prodottiARR2)){
                      $rows2[] = $row2;
                  	}
                  foreach($rows2 as $row2){ 
                      
                      
                    	echo '<tr data-status="last">';
                    	echo '<td>';
                      echo '<div class="media-body">';
                      echo '<span class="media-meta pull-right">'.$row2['dataUltimaModifica'].'</span>';
                      echo '<h4 class="title">';
                      echo $row2['brand'].$row2['nomeProdotto'];
                      echo '</h4>';
                      echo '<p class="summary">Prodotto terminato ';
                      echo '<span class="pull-right "><mark>TERMINATO</mark></span>';
                      echo '</p>';
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                      
                     
                    }
                    
                  }
                  else {
                      echo '<font>Non ci sono prodotti esauriti</font>';
                    }
                    
                   
                  
                  ?>
												
											

								</tbody>
							</table>
						</div>
						
					</div>
					
				</div>
				
				
		</section>
		
		
		
		
	</div>
	
	
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