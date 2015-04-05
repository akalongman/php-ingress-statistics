# php-ingress-statistics
==================

Class shows graphycal year statistics for Ingress from CSV file
See demo: http://ingress.ge/stats/2014


## Sponsors

No sponsors yet.. :(
If you like the software, don't forget to donate to further development of it!

[![PayPal donate button](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=MVCBM7M2RCEX6&lc=US&item_name=Support%20our%20open%2dsource%20initiatives&item_number=donation&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted "Donate once-off to this project using Paypal")



## Usage:

First you must create CSV file from Ingress data (You can create Excel and after export in CSV). Look at file example.csv

CSV format is very simple: each cycle in each line:
CYCLE_NAME,ENL_MU(k),RES_MU(k),ENL_TOP_PLAYER,RES_TOP_PLAYER

Small example:
2014.03,59.4,16.2,LONGMAN [L14],bizarre [L10]

If no data about any player you can left empty:
2014.03,59.4,16.2,,bizarre [L10]

If MUs is under 1k you must specify amount with decimals under 1:
2014.03,0.1,16.2,LONGMAN [L14],bizarre [L10]

```php
	// include lib file
	require('ingress.inc.php');

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
		$ingress = new Ingress(array('year'=>2014, 'cell'=>'NR02-BRAVO-02', 'cellname'=>'Tbilisi - Georgia'));

		// load csv file
		$ingress->loadCSV('example.csv');

		// render generated HTML
		$ingress->render();
	}
	catch(Exception $e) {
		die($e->getMessage());
	}
```

## TODO

Add other languages

Pull requests are welcome.

## Troubleshooting

If you like living on the edge, please report any bugs you find on the [php-ingress-statistics issues](https://github.com/akalongman/php-ingress-statistics/issues) page.
