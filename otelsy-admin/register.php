<?php
	ob_start();
	session_start();
	if( isset($_SESSION['user'])!="" ){
		header("Location: home.php");
	}
	include_once 'dbconnect.php';

	$error = false;

	if ( isset($_POST['btn-signup']) ) {
		
		// clean user inputs to prevent sql injections
		$name = trim($_POST['name']);
		$name = strip_tags($name);
		$name = htmlspecialchars($name);
		
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$pass = trim($_POST['pass']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);
		
		$cognome = trim($_POST['cognome']);
		$cognome = strip_tags($cognome);
		$cognome = htmlspecialchars($cognome);
		
		$via = trim($_POST['via']);
		$via = strip_tags($via);
		$via = htmlspecialchars($via);
		
		$telefono = trim($_POST['telefono']);
		$telefono = strip_tags($telefono);
		$telefono = htmlspecialchars($telefono);
		
		$dataNascita = date('Y-m-d',strtotime($_POST['dataNascita']));
		
		$numeroResidenza = trim($_POST['numeroResidenza']);
		$numeroResidenza = strip_tags($numeroResidenza);
		$numeroResidenza = htmlspecialchars($numeroResidenza);
		
		$cittaResidenza = trim($_POST['cittaResidenza']);
		$cittaResidenza = strip_tags($cittaResidenza);
		$cittaResidenza = htmlspecialchars($cittaResidenza);
		
		$capResidenza = trim($_POST['capResidenza']);
		$capResidenza = strip_tags($capResidenza);
		$capResidenza = htmlspecialchars($capResidenza);
		
		$provinciaResidenza = trim($_POST['provinciaResidenza']);
		$provinciaResidenza = strip_tags($provinciaResidenza);
		$provinciaResidenza = htmlspecialchars($provinciaResidenza);
		
		$statoResidenza = trim($_POST['statoResidenza']);
		$statoResidenza = strip_tags($statoResidenza);
		$statoResidenza = htmlspecialchars($statoResidenza);
		
		$statoResidenza = trim($_POST['statoResidenza']);
		$statoResidenza = strip_tags($statoResidenza);
		$statoResidenza = htmlspecialchars($statoResidenza);
		
		$tipoDocumento = trim($_POST['tipoDocumento']);
		$tipoDocumento = strip_tags($tipoDocumento);
		$tipoDocumento = htmlspecialchars($tipoDocumento);
		
		$numeroDocumento = trim($_POST['numeroDocumento']);
		$numeroDocumento = strip_tags($numeroDocumento);
		$numeroDocumento = htmlspecialchars($numeroDocumento);
		
		$emissioneDocumento = trim($_POST['emissioneDocumento']);
		$emissioneDocumento = strip_tags($emissioneDocumento);
		$emissioneDocumento = htmlspecialchars($emissioneDocumento);
		
		$scadenzaDocumento = trim($_POST['scadenzaDocumento']);
		$scadenzaDocumento = strip_tags($scadenzaDocumento);
		$scadenzaDocumento = htmlspecialchars($scadenzaDocumento);
		
		$scadenzaDocumento = trim($_POST['scadenzaDocumento']);
		$scadenzaDocumento = strip_tags($scadenzaDocumento);
		$scadenzaDocumento = htmlspecialchars($scadenzaDocumento);



		// basic name validation
		if (empty($name)) {
			$error = true;
			$nameError = "Please enter your first name.";
		} else if (strlen($name) < 3) {
			$error = true;
			$nameError = "Name must have atleat 3 characters.";
		} else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
			$error = true;
			$nameError = "Name must contain alphabets and space.";
		}
		
		// basic cognome validation
		if (empty($cognome)) {
			$error = true;
			$cognomeError = "Please enter your last name.";
		} else if (strlen($cognome) < 1) {
			$error = true;
			$cognomeError = "Last name must have at least 3 characters.";
		} else if (!preg_match("/^[a-zA-Z ]+$/",$cognome)) {
			$error = true;
			$cognomeError = "Last name must contain alphabets and space.";
		}
		
		//basic email validation
		if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
			$error = true;
			$emailError = "Please enter valid email address.";
		} else {
			// check email exist or not
			$query = "SELECT email FROM utente WHERE email='$email';";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			if($count!=0){
				$error = true;
				$emailError = "Provided Email is already in use.";
			}
		}
		
		//basic data validation
		function ControlloData($dataNascita){
	if(!ereg("^[0-9]{2}/[0-9]{2}/[0-9]{4}$", $dataNascita)){
		return false;
	}else{
		$arrayData = explode("/", $dataNascita);
		$Giorno = $arrayData[0];
		$Mese = $arrayData[1];
		$Anno = $arrayData[2];
		if(!checkdate($Mese, $Giorno, $Anno)){
			return false;
		}else{
			return true;
		}
	}
}
		
		// password validation
		if (empty($pass)){
			$error = true;
			$passError = "Please enter password.";
		} else if(strlen($pass) < 6) {
			$error = true;
			$passError = "Password must have atleast 6 characters.";
		}
		
		// date validation TODO
		// VIA VALIDATION TODO
		// numeroresidenza validation VALIDATION TODO
		//VALIDAZIONE DA CONTROLLARE PER TUTTI
		
		
		// password encrypt using SHA256();
		$password = hash('sha256', $pass);
		
		// Chiave unica
		$key = uniqid (rand () . "_",true); 
		//controllo se chiave già esiste in table stanze
                  	$sql2=mysql_query("SELECT * FROM stanze where chiave1='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave2='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave3='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave4='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave5='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave6='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave7='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave8='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave9='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    $sql2=mysql_query("SELECT * FROM stanze where chiave10='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
                    //Controllo se gi esiste in utente
                    $sql2=mysql_query("SELECT * FROM utente where chiave='".$key."'");
                  	while(mysql_num_rows($sql2)!=0) {
                      $key = uniqid (rand () . "_",true);
                    }
		// MD5 della chiave
		//$md5_key = md5($key);
		//sha-256 della chiave in md5
		//$sha_key = hash('sha256', $md5_key);
		
		//A me sul server principale però serve la chiave in chiaro per poter mostrare il qr quando accedono
		//Devo creare un secondo database con le chiavi criptate dove accedono gli hotel
		
		//Per prendere la png del qr:
		/*$content = file_get_contents('https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $key);
                //Store in the filesystem.
                $fp = fopen("code/key_" . $key . ".png", "w");
                fwrite($fp, $content);
                fclose($fp);
                */
                
        //Immagine
        
	
		
		// There is an argument that this is unnecessary with base64 encoded data, but
		// better safe than sorry :)
		
		if(!empty($_FILES['$fotoDocumento']['tmp_name']) 
		     && file_exists($_FILES['$fotoDocumento']['tmp_name'])) {
		    $fotoDocumento= addslashes(file_get_contents($_FILES['$fotoDocumento']['tmp_name']));
		}
			$fotoDocumento = base64_encode($fotoDocumento);
		
		
		// if there's no error, continue to signup
		if( !$error ) {
			
			//Eseguo la query e scrivo nel database
			$query = "INSERT INTO utente(nome,cognome,email,password,telefono,viaResidenza,dataNascita,chiave,numeroResidenza,cittaResidenza,capResidenza,provinciaResidenza,statoResidenza,tipoDocumento,numeroDocumento,emissioneDocumento,scadenzaDocumento,fotoDocumento) VALUES('$name','$cognome','$email','$password','$telefono','$via', '$dataNascita','$key','$numeroResidenza','$cittaResidenza','$capResidenza','$provinciaResidenza','$statoResidenza','$tipoDocumento','$numeroDocumento','$emissioneDocumento','$scadenzaDocumento','$fotoDocumento')";
			$res = mysql_query($query);
			
			
			
			if ($res) {
				$errTyp = "success";
				$errMSG = "Successfully registered, you may login now";
				unset($name);
				unset($email);
				unset($pass);
				unset($cognome);
				unset($telefono);
				unset($via);
				unset($dataNascita);
				unset($chiave);
				unset($numeroResidenza);
			} else {
				$errTyp = "danger";
				$errMSG = "Something went wrong, try again later...";	
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

<div class="container">

	<div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
    	<div class="col-md-12">
        
        	<div class="form-group">
            	<h2 class="">Registrati</h2>
            </div>
        
        	<div class="form-group">
            	<hr />
            </div>
            
            <?php
			if ( isset($errMSG) ) {
				
				?>
				<div class="form-group">
            	<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
            	</div>
                <?php
			}
			?>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                
            	<input type="text" name="name" class="form-control" placeholder="Nome" maxlength="50" value="<?php echo $name ?>" />
            	<span class="text-danger"><?php echo $nameError; ?></span>
            	
            	<input type="text" name="cognome" class="form-control" placeholder="Cognome" maxlength="50" value="<?php echo $cognome ?>" />
                <span class="text-danger"><?php echo $cognomeError; ?></span>
                
                
                </div>
                <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            	<input type="date" name="dataNascita" class="form-control" placeholder="Data di nascita" onfocus="(this.type='date')" onblur="if(this.value==''){this.type='text'}" maxlength="50" value="<?php echo $dataNascita ?>" />
                </div>
                <span class="text-danger"><?php echo $dataNascitaError; ?></span>
            </div>
            
            
            
            
            
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
            	<input type="number" name="telefono" class="form-control" placeholder="Telefono" maxlength="50" value="<?php echo $telefono ?>" />
                </div>
            </div>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
            	<input type="email" name="email" class="form-control" placeholder="Email" maxlength="40" value="<?php echo $email ?>" />
                </div>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
            	<input type="password" name="pass" class="form-control" placeholder="Password" maxlength="500" />
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
            	
            	<input type="text" name="via" class="form-control" placeholder="Via" maxlength="500" value="<?php echo $via ?>" />
                <span class="text-danger"><?php echo $viaError; ?></span>
            	
            	<input type="number" name="numeroResidenza" class="form-control" placeholder="Numero" maxlength="5" value="<?php echo $numeroResidenza ?>" />
                <span class="text-danger"><?php echo $numeroResidenzaError; ?></span>
                
                <input type="text" name="cittaResidenza" class="form-control" placeholder="Città" maxlength="500" value="<?php echo $cittaResidenza ?>" />
                <span class="text-danger"><?php echo $cittaResidenzaError; ?></span>
                
                <input type="number" name="capResidenza" class="form-control" placeholder="CAP" maxlength="10" value="<?php echo $capResidenza ?>" />
                <span class="text-danger"><?php echo $capResidenzaError; ?></span>
                
                <input type="text" name="provinciaResidenza" class="form-control" placeholder="Provincia" maxlength="2" value="<?php echo $provinciaResidenza ?>" />
                <span class="text-danger"><?php echo $provinciaResidenzaError; ?></span>
                
                <input type="text" name="statoResidenza" class="form-control" placeholder="Stato" maxlength="500" value="<?php echo $statoResidenza ?>" />
                <span class="text-danger"><?php echo $statoResidenzaError; ?></span>
                
                </div>
                
                
            </div>
            
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-file"></span></span>
            	
            	<input type="text" name="tipoDocumento" class="form-control" placeholder="Tipo documento" maxlength="500" value="<?php echo $tipoDocumento ?>" />
                <span class="text-danger"><?php echo $tipoDocumentoError; ?></span>
            	
            	<input type="text" name="numeroDocumento" class="form-control" placeholder="Numero del documento" maxlength="500" value="<?php echo $numeroDocumento ?>" />
                <span class="text-danger"><?php echo $numeroDocumentoError; ?></span>
                
                <input type="date" name="emissioneDocumento" class="form-control" placeholder="Data emissione documento" onfocus="(this.type='date')" onblur="if(this.value==''){this.type='text'}" maxlength="500" value="<?php echo $emissioneDocumento ?>" />
                <span class="text-danger"><?php echo $emissioneDocumentoError; ?></span>
                
                <input type="date" name="scadenzaDocumento" class="form-control" placeholder="Data scadenza documento" onfocus="(this.type='date')" onblur="if(this.value==''){this.type='text'}" maxlength="500" value="<?php echo $scadenzaDocumento ?>" />
                <span class="text-danger"><?php echo $scadenzaDocumentoError; ?></span>
                
                <input type="file" name="fotoDocumento" class="form-control" placeholder="Foto documento" />
                <span class="text-danger"><?php echo $fotoDocumentoError; ?></span>
                
                </div>
                
                
                
            </div>
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
            </div>
            
            <div class="form-group">
            	<hr />
            </div>
            
            <div class="form-group">
            	<a href="index.php">Sei già registrato? Accedi qui.</a>
            </div>
        
        </div>
   
    </form>
    </div>	

</div>

</body>
</html>
<?php ob_end_flush(); ?>