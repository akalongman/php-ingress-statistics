<?php

// include lib file
include('ingress.inc.php');

try {

	// Possible parameters
	// year - Statistics year for titles
	// cell - Cell code
	// cellname - Human readable cell name
	// analytics - Google analytics id (if you want track page views)

	// chart_options - Chart Options:
	//			 animation.duration - Duration in milliseconds (default is 1500)
	//			 animation.easing - Easing (default is 'swing'. More easings you can see at http://api.jqueryui.com/easings)
	$chart_options = array('animation.duration'=>1500, 'animation.easing'=>'easeOutBounce');

	// initialize class with custom parameters
	$ingress = new Ingress(array('year'=>2014, 'cell'=>'NR02-BRAVO-02', 'cellname'=>'Tbilisi - Georgia', 'chart_options'=>$chart_options));

	// load csv file
	$ingress->loadCSV('example.csv');

	// render generated HTML
	$ingress->render();
}
catch(Exception $e) {
	die($e->getMessage());
}