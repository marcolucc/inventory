<?php
require('simple_html_dom.php');
$html = file_get_html('https://www.blia.it/utili/prezzi/?ean=5449000000439');
$title=$html->find("div#centroid",0)->innertext;
echo $title;
