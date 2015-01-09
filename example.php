<?php

// include lib file
include('ingress.inc.php');

try {
	// initialize class with custom parameters
	$ingress = new Ingress(array('year'=>2014, 'cell'=>'NR02-BRAVO-02', 'cellname'=>'Tbilisi - Georgia'));

	// load csv file
	$ingress->loadCSV('2014.csv');

	// render generated HTML
	$ingress->render();
}
catch(Exception $e) {
	die($e->getMessage());
}