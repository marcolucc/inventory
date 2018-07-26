<?php
ob_start();
session_start();
require_once 'dbprodotti.php';

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function blia_it($codiceBarre){
    //Ecco come usare la funz. bila_it
    //$array = blia_it(5449000000439);
    //echo $array[0];
    $esiste = already_exists($codiceBarre);
    if ($esiste==true){
        $query = "SELECT * FROM prodotto WHERE codiceBarre=".$codiceBarre;
        $prodotto = mysql_query($query);
        $prodotto=mysql_fetch_array($prodotto);
        return array ($prodotto['codiceBarre'],$prodotto['nomeProdotto'],$prodotto['brand'], $prodotto['produttore'],$prodotto['indirizzoProduttore'],$prodotto['prezzo']);
    }
    else{
        $uri = 'https://www.blia.it/utili/prezzi/?ean='.$codiceBarre;
        $get = file_get_contents($uri);
        $get = (explode( '<table', $get ) );
        $codiceBarreRicevuto = get_string_between($get[3], 'Codice a barre (EAN)', 'Prodotto');
        $codiceBarreRicevuto = get_string_between($codiceBarreRicevuto, '<td>', '</td>');
        $nomeProdottoRicevuto = get_string_between($get[3], 'Prodotto', 'Prezzo');
        $nomeProdottoRicevuto = get_string_between($nomeProdottoRicevuto, '<td>', '</td>');
        $prezzoRicevuto = get_string_between($get[3], 'Prezzo', 'Fornitore');
        
        $query = "INSERT INTO `prodotto`(`codiceBarre`, `nomeProdotto`, `brand`, `produttore`, `indirizzoProduttore`, `prezzo`) VALUES ('".$codiceBarreRicevuto."','".$nomeProdottoRicevuto."','','','','".$prezzoRicevuto."')";
    
        $prodotto = mysql_query($query);
        return array ($codiceBarreRicevuto, $nomeProdottoRicevuto, $prezzoRicevuto);
    }
}


function buycott_com($codiceBarre){
    $esiste = already_exists($codiceBarre);
    if ($esiste==true){
        $query = "SELECT * FROM prodotto WHERE codiceBarre=".$codiceBarre;
        $prodotto = mysql_query($query);
        $prodotto=mysql_fetch_array($prodotto);
        return array ($prodotto['codiceBarre'],$prodotto['nomeProdotto'],$prodotto['brand'], $prodotto['produttore'],$prodotto['indirizzoProduttore'],$prodotto['prezzo']);
    }
    else{
        $uri = 'https://www.buycott.com/upc/'.$codiceBarre;
        $get = @file_get_contents($uri);
        if($get === FALSE) { return "ERROR"; }
        else{
            $nomeProdottoRicevuto = get_string_between($get, '<meta property="og:title" content="', '" />');
            $codiceBarreRicevuto = get_string_between($get, "<h1>EAN ", '</h1>');
            $html = (explode( '<tbody>', $get ) );
            $table=$html[1];
            $table=strstr($table, 'Description', true);
            
            $produttore = get_string_between($table, 'Manufacturer', 'UPC');
            $produttore = get_string_between($produttore, '<a href', '></td');
            $produttore = get_string_between($produttore, '">', '</');
            
            $indirizzoProduttore = get_string_between($get, "GS1 Address</td>", '/td>');
            $indirizzoProduttore = get_string_between($indirizzoProduttore, "td>", '<');
            
            $brand = get_string_between($table, 'Brand', 'Manufacturer');
            $brand = get_string_between($brand, '<a href', '></td');
            $brand = get_string_between($brand, '">', '</');
            $query = "INSERT INTO `prodotto`(`codiceBarre`, `nomeProdotto`, `brand`, `produttore`, `indirizzoProduttore`, `prezzo`) VALUES (".$codiceBarreRicevuto.",'".$nomeProdottoRicevuto."','".$brand."','".$produttore."','".$indirizzoProduttore."','0,00')";
            
            $prodotto = mysql_query($query);
            return array ($codiceBarreRicevuto,$nomeProdottoRicevuto, $brand,$produttore,$indirizzoProduttore, "€ 0.00");
        }
    }
}

function digiteyes_com($codiceBarre){
    $esiste = already_exists($codiceBarre);
    if ($esiste==true){
        $query = "SELECT * FROM prodotto WHERE codiceBarre=".$codiceBarre;
        $prodotto = mysql_query($query);
        $prodotto=mysql_fetch_array($prodotto);
        return array ($prodotto['codiceBarre'],$prodotto['nomeProdotto'],$prodotto['brand'], $prodotto['produttore'],$prodotto['indirizzoProduttore'],$prodotto['prezzo']);
    }
    else{
        $uri = 'http://www.digit-eyes.com/upcCode/'.$codiceBarre.'.html?l=it';
        $get = @file_get_contents($uri);
        if($get === FALSE) { return "ERROR"; }
        else{
           
            $intermedio = get_string_between($get, '<tit', 'tle>');
            $codiceBarreRicevuto = get_string_between($get, 'le>', 'UPC');
            echo $codiceBarreRicevuto;
            $nomeProdottoRicevuto = get_string_between($get, 'UPC', '</ti');
            
            $query = "INSERT INTO `prodotto`(`codiceBarre`, `nomeProdotto`, `prezzo`) VALUES (".$codiceBarreRicevuto.",'".$nomeProdottoRicevuto."','0,00')";
            
            $prodotto = mysql_query($query);
            return array ($codiceBarreRicevuto,$nomeProdottoRicevuto, "€ 0.00");
        }
    }
}

function already_exists($codiceBarre){
    $query = "SELECT * FROM prodotto WHERE codiceBarre=".$codiceBarre;
    $prodotto = mysql_query($query);
    $count = mysql_num_rows($prodotto);
    if ($count >= 1){
        return true;
    }
    else{
        return false;
    }
}

 
?>