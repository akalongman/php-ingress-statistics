# php-ingress-statistics
==================

Class shows graphycal year statistics for Ingress from CSV file

## Usage:

First you must create CSV file from Ingress data (You can first create Excel and after export in CSV). Look at file example.csv

```php
	// include lib file
	require('ingress.inc.php');

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
```

Pull requests are welcome.

Troubleshooting
---------------
If you like living on the edge, please report any bugs you find on the [CodeFormatter issues](https://github.com/akalongman/sublimetext-codeformatter/issues) page.
