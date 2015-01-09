<?php

// include lib file
include('ingress.inc.php');

try {
	// initialize class with custom parameters

	// Possible parameters
	// year - Statistics year for titles
	// cell - Cell code
	// cellname - Human readable cell name
	// analytics - Google analytics id (if you want track page views)
	$ingress = new Ingress(array('year'=>2014, 'cell'=>'NR02-BRAVO-02', 'cellname'=>'Tbilisi - Georgia'));

	// load csv file
	$ingress->loadCSV('2014.csv');

	// render generated HTML
	$ingress->render();
}
catch(Exception $e) {
	die($e->getMessage());
}