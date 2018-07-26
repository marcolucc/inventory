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
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome - <?php echo $userRow['nome']; ?></title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://www.link.com">TITOLO</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="update.php"><span class="glyphicon glyphicon-qrcode"></span>&nbsp;Cambia codice</a></li>
            <li><a href="http://www.link.com">link</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hi <?php $email=$userRow['email']; echo $email; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav> 

	<div id="wrapper">

<script>
  setTimeout(function() {
    window.location.reload();
}, 3000);
</script>

	<div class="container">
    
    	<div class="page-header">
    	<h3>Benvenuto <?php echo $userRow['nome']; ?></h3>
    	</div>
        
        <div class="row">
        <div class="col-lg-12">
        <h2 style="text-align: center;"><?php 
                $query = "SELECT * FROM stanze WHERE idUtente=".$_SESSION['user'];
                $stanzaARR = mysql_query($query);
                $stanzaARR = mysql_fetch_array($stanzaARR);
                
                if (is_null($stanzaARR["idHotel"])){
                  echo "Effettua una prenotazione ad un hotel";
                }
                else{
                  
                  $query = "SELECT * FROM hotel WHERE idHotel=".$stanzaARR["idHotel"];
                  $hotelARR = mysql_query($query);
                  $hotelARR = mysql_fetch_array($hotelARR);
                  $stringa= $hotelARR["nome"]." ";
                  for ($i=0;$i<$hotelARR["stelle"];$i++)
                  {
                      $stringa = $stringa."⭐";
                  }
                  echo $stringa;
                }
///Non serve più il numero di camera (Rivedi inserimento su db)
                
                
              /*
              $content = file_get_contents('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $row["chiave"]);
              //Store in the filesystem.
                $fp = fopen("code/key_" . $key . ".png", "w");
                fwrite($fp, $content);
                fclose($fp);*/
			        ?></h2>
			        <h3 style="text-align: center;"><?php
			                if (is_null($stanzaARR["idHotel"])){}
			                else{
			                  echo "🚪 ". $stanzaARR["numeroStanza"];
			                }
			         ?></h3>
        </div>
        
        </div>
        <div class="container container-table">
    <div class="row vertical-center-row">
        <div class="text-center col-md-4 col-md-offset-4" ><a href="
        <?php $query = "SELECT chiave FROM utente WHERE email='$email';";
			        $key = mysql_query($query);
			        $row = mysql_fetch_array($key);
              echo $row["chiave"];
              $qr = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $row["chiave"];
              ?>"><img src='<?php echo $qr; ?>'/></a>
              <h3 style="text-align: center;"><a href="tel:<?php
                                                                if (is_null($stanzaARR["idHotel"])){}
			                                                          else{
                                                                $query = "SELECT telefono FROM hotel WHERE idHotel=".$stanzaARR["idHotel"];
                                                                $c = mysql_query($query);
                                                                $riga = mysql_fetch_array($c);
                                                                $telefono=$riga["telefono"];  
                                                                echo $telefono;
			                                                          }
                                                                  
              
                                                            ?>"><span class="<?php
                                                                if (is_null($stanzaARR["idHotel"])){}
			                                                          else{
			                                                            echo "glyphicon glyphicon-phone";} ?>"></span>&nbsp;<?php
                                                                if (is_null($stanzaARR["idHotel"])){}
			                                                          else{
			                                                            echo $telefono;} ?></a>
			         </h3>
              </div>
              
              
    </div>
</div>
        
        
    </div>
    
    </div>
    
    <script src="assets/jquery-1.11.3-jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
</body>
</html>
<?php ob_end_flush(); ?>