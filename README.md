# php-ingress-statistics
==================

Class shows graphycal year statistics for Ingress from CSV file
See demo: http://ingress.ge/stats/2014

## Usage:

First you must create CSV file from Ingress data (You can create Excel and after export in CSV). Look at file example.csv

CSV format is very simple: each cycle in each line:
CYCLE_NAME,ENL_MU(k),RES_MU(k),ENL_TOP_PLAYER,RES_TOP_PLAYER

Small example:
2014.03,59.4,16.2,LONGMAN [L11],bizarre [L10]


```php
	// include lib file
	require('ingress.inc.php');

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
```

TODO
-----
Add other languages

Pull requests are welcome.

Troubleshooting
---------------
If you like living on the edge, please report any bugs you find on the [php-ingress-statistics issues](https://github.com/akalongman/php-ingress-statistics/issues) page.
