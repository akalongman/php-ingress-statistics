<?php
/**
 * @package 			Ingress Statistics Builder
 * @version 			1.0.0
 * @author 			Avtandil Kikabidze
 * @link 				http://long.ge
 * @copyright 		Copyright (c) 2008-2015, Avtandil Kikabidze aka LONGMAN (akalongman@gmail.com)
 * @license 			GNU General Public License version 2 or later;
 */

class Ingress
{
	// Statistics year for titles
	protected $year;

	// Cell code
	protected $cell;

	// Cell name
	protected $cellname;

	// Data from CSV
	protected $data;

	// Google Analytics ID
	protected $analytics_id;


	/**
	 * Contructor function
	 *
	 * @param string $year
	 * @param string $cell
	 * @param string $cellname
	 * @return string
	 */
	public function __construct($config)
	{
		if (isset($config['year'])) {
			$this->year = $config['year'];
		}
		if (isset($config['cell'])) {
			$this->cell = $config['cell'];
		}
		if (isset($config['cellname'])) {
			$this->cellname = $config['cellname'];
		}
		if (isset($config['analytics'])) {
			$this->analytics_id = $config['analytics'];
		}

	}

	/**
	 * Load CVS data file
	 *
	 * @param string $file
	 * @return string
	 */
	public function loadCSV($file)
	{
		if (empty($file)) {
			throw new InvalidArgumentException('File name is empty');
		}

		if (!file_exists($file)) {
			throw new InvalidArgumentException('CSV file not found');
		}

		$this->data = file($file);
		if (empty($this->data)) {
			throw new LogicException('CSV file is empty');
		}

		$this->data = array_reverse($this->data);
	}

	/**
	 * Render generated HTML
	 *
	 */
	public function render()
	{
		$stats = array();
		$enl_players = array();
		$res_players = array();

		$js_series = array();
		$js_categories = array();
		$js_enl_mus = array();
		$js_res_mus = array();

		$enl_wins = 0;
		$res_wins = 0;

		$enl_mus = array();
		$res_mus = array();
		$players = array();
		$top_players = array();
		$cycles = 0;
		foreach($this->data as $i=>$item) {
			$str = explode(',', $item);
			$cicle_name = trim($str[0]);
			$enl_mu = trim($str[1]);
			$res_mu = trim($str[2]);
			$enl_player = trim($str[3]);
			$res_player = trim($str[4]);

			$enl_players[] = $enl_player;
			$res_players[] = $res_player;

			$players[$enl_player] = 'ENL';
			$players[$res_player] = 'RES';

			$top_players[] = $enl_player;
			$top_players[] = $res_player;

			$js_categories[] = $cicle_name;
			$js_enl_mus[] = "{y:".$enl_mu.", player:'".strtok($enl_player, ' ')."', faction: 'ENL', playerlvl: '".$enl_player."'}";
			$js_res_mus[] = "{y:".$res_mu.", player:'".strtok($res_player, ' ')."', faction: 'RES', playerlvl: '".$res_player."'}";

			if ($enl_mu > $res_mu) {
				$enl_wins++;
			}
			else if ($enl_mu < $res_mu) {
				$res_wins++;
			}

			$enl_mus[] = $enl_mu;
			$res_mus[] = $res_mu;
			$cycles++;
		}


		$js_categories = "'".implode("', '", $js_categories)."'";
		$js_enl_mus = implode(", ", $js_enl_mus);
		$js_res_mus = implode(", ", $js_res_mus);



		$enl_players = array_count_values($enl_players);
		arsort($enl_players);

		$res_players = array_count_values($res_players);
		arsort($res_players);

		$top_players = array_count_values($top_players);
		arsort($top_players);


		$enl_mus_avg = round(array_sum($enl_mus) / count($enl_mus), 2);
		$res_mus_avg = round(array_sum($res_mus) / count($res_mus), 2);

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");
		header("Cache-Control: post-check=0, pre-check=0", false);
		?><!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8" />
			<title>Results for cell <?php echo $this->cell ?> "<?php echo $this->cellname ?>" of <?php echo $this->year ?> year.</title>
			<link rel="icon" href="http://ingress.ge/favicon.ico"/>
			<link rel="shortcut icon" href="http://ingress.ge/favicon.ico" />
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<meta name="description" content="Statistics for cell <?php echo $this->cell?> of <?php echo $this->year ?> year" />
			<meta name="copyright" content="&copy; LONGMAN" />
			<meta name="author" content="LONGMAN" />

			<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

			<!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

			<!-- Optional theme -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

			<!-- Latest compiled and minified JavaScript -->
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>


			<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
			<script type="text/javascript">

				$(function () {
					$('#chart').highcharts({
						chart: {
							type: 'bar',
							style: {
								fontFamily: 'Arial'
							},
							backgroundColor: '#000000',
							showAxes: true,
							spacingRight: 40,
							height: 5000
						},
						colors: ['#2bed1b', '#0abfff'],
						title: {
							text: '<?php echo $this->cell?> [<?php echo $this->year?> year, <?php echo $cycles ?> cycles]',
							style: {
								color: '#ebbc4a',
								fontSize: '22px',
								fontWeight: 'bold'
							},
							margin: 20
						},
						subtitle: {
							text: 'Count of MUs (k) in each cycle',
							style: {
								color: '#ebbc4a',
								fontSize: '16px',
							}
						},
						xAxis: {
							gridLineColor: '#ebbc4a',
							categories: [<?php echo $js_categories ?>],
							title: {
								align: 'high',
								offset: 0,
								rotation: 0,
								x: -16,
		                				y: 4,
								text: 'Cycles',
								style: {
									color: '#ebbc4a',
									fontSize: '16px',
								}
							},
							labels: {
								style: {
									color:'#ebbc4a',
									fontSize: '16px',
									fontWeight:'bold'
								}
							}
						},
						yAxis: {
							title: {
								align: 'middle',
								offset: 40,
								rotation: 0,
								text: 'MU, k',
								style: {
									color: '#ebbc4a',
									fontSize: '16px',
								}
							},


							gridLineColor: '#ebbc4a',
							stackLabels: {
								enabled: true,
								align: 'left',
								color: '#ebbc4a'
							},
							labels: {
								style: {
									color:'#ebbc4a',
									fontSize: '16px',
									fontWeight:'bold'
								},
								formatter: function() {
									return this.value + 'k';
								}
							}
						},
						credits: {
							enabled: false
						},

						legend: {
							layout: 'horizontal',
							floating: false,
							align: 'center',
							verticalAlign: 'top',
							y: 70,
							itemStyle: {
								color: '#ebbc4a'
							},
							itemHoverStyle: {
								color: '#cccccc'
							}
						},
						plotOptions: {
							bar: {
								dataLabels: {
									enabled: true
								},
								borderWidth: 0
							},
							series: {
								dataLabels: {
									enabled: true,
									//align: 'right',
									//inside: false,
									crop: false,
									overflow: 'none',
									//x: 80,
									//verticalAlign: 'bottom',
									style: {
										fontSize: '18px',
										color: '#ebbc4a',
										fontWeight: 'bold',
										textShadow: '0 0 3px black',
									},
									formatter: function () {
										var color = this.point.faction == 'ENL' ? '#2bed1b' : '#0abfff';
										return '<span style="color:'+color+'">' + this.point.player + '</span>';
									}
								},
								pointPadding: 0.1
							}
						},
						tooltip: {
							backgroundColor: '#000000',
							borderColor: '#ebbc4a',
							borderWidth: 2,
							crosshairs: [null, true],
							style: {
								color: '#ebbc4a',
								fontSize: '14px',
								padding: '8px'
							},
							formatter: function () {
								var color = this.point.faction == 'ENL' ? '#2bed1b' : '#0abfff';

								return '<b>Cycle:</b> ' + this.x + '<br /><b>' + this.series.name + ':</b> ' + this.y + 'k MU<br /><b>Top Player:</b> <span style="color:'+color+'"> ' + this.point.playerlvl + '</span>';
							}
						},


						series: [
							{
								name: 'Enlightened',
								data: [<?php echo $js_enl_mus?>]
							}, {
								name: 'Resistance',
								data: [<?php echo $js_res_mus?>]
							}
						]
					});
				});
			</script>
		</head>

		<body style="background-color:#000000;color:#ebbc4a">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-8" style="padding-left: 0;margin-left: 0;">
						<div class="chart" id="chart"></div>
					</div>

					<div class="col-xs-4" style="padding-left: 10px;padding-right: 10px;">
						<div class="overlay" style="padding-top: 60px;">

							<div class="totalCount" style="text-align:center;">
								<h4>
									The number of winning cycles and the average MU
								</h4>

								<div class="row">


									<div class="col-xs-5" style="text-align:center;">


										<span style="font-size: 50px;color: #2bed1b;">
											<?php echo $enl_wins ?>
										</span>
										<br />
										<span style="color: #2bed1b;font-size: 15px;">
											<?php echo $enl_mus_avg?>k
										</span>


									</div>

									<div class="col-xs-2" style="text-align:center;">
										<span style="font-size: 68px;text-align:center;">
										:
										</span>
									</div>

									<div class="col-xs-5" style="text-align:center;">

										<span style="font-size: 50px;color: #0abfff;">
											<?php echo $res_wins ?>
										</span>
										<br />
										<span style="color: #0abfff;font-size: 15px;">
											<?php echo $res_mus_avg?>k
										</span>
									</div>

								</div>

							</div>


							<div class="topPlayers" style="margin-top:40px;text-align:center;">
								<h4>
									TOP Players of <?php echo $this->year ?>
								</h4>

								<div class="row">

									<ol style="text-align:left">
									<?php
									$top_players = array_slice($top_players, 0, 8);
									foreach($top_players as $pl=>$cnt) {
										$style = '';

										if (isset($players[$pl])) {
											if ($players[$pl] == 'ENL') {
												$style = 'color: #2bed1b;';
											} else if ($players[$pl] == 'RES') {
												$style = 'color: #0abfff;';
											}
										}
										?>
										<li>
											<span style="<?php echo $style?>"><?php echo $pl ?></span> <span style="color:#888888;">(<?php echo $cnt ?>)</span>
										</li>
										<?php
									}
									?>
									</ol>

								</div>

							</div>


							<div class="topPlayersEnl" style="margin-top:40px;text-align:center;">
								<h4>
									TOP Enlightened Players of <?php echo $this->year ?>
								</h4>

								<div class="row">

									<ol style="text-align:left">
									<?php
									$enl_players = array_slice($enl_players, 0, 8);
									foreach($enl_players as $pl=>$cnt) {
										$style = 'color: #2bed1b;';
										?>
										<li>
											<span style="<?php echo $style?>"><?php echo $pl ?></span> <span style="color:#888888;">(<?php echo $cnt ?>)</span>
										</li>
										<?php
									}
									?>
									</ol>

								</div>

							</div>


							<div class="topPlayersEnl" style="margin-top:40px;text-align:center;">
								<h4>
									TOP Resistance Players of <?php echo $this->year ?>
								</h4>

								<div class="row">

									<ol style="text-align:left">
									<?php
									$res_players = array_slice($res_players, 0, 8);
									foreach($res_players as $pl=>$cnt) {
										$style = 'color: #0abfff;';
										?>
										<li>
											<span style="<?php echo $style?>"><?php echo $pl ?></span> <span style="color:#888888;">(<?php echo $cnt ?>)</span>
										</li>
										<?php
									}
									?>
									</ol>

								</div>

							</div>


							<div class="topPlayersDesc" style="margin-top:40px;text-align:center;">
								<div class="row" style="padding:20px">
									<span style="font-size: 11px;text-align:justify;">
									Top players is determined by the sum of the first places of collected MU among all agents in cell <?php echo $this->cell ?> (Top Players) among the players Enlightened (Top Players Enlightened) and among players Resistance (Top Players Resistance).
									</span>
								</div>

							</div>

						</div>
					</div>

				</div>

				<footer>
					<div class="pull-right" style="text-align:right;">
						<span style="font-size:18px">
							Created by <b><a href="https://plus.google.com/u/0/+AvtandilKikabidze" target="_blank">LONGMAN</a></b> [ENL]
						</span>
						<br />
						<span style="font-size:14px">
							Original idea <a href="http://gplus.to/sendelufa" target="_blank">Sendel</a> [ENL]
						</span>
					</div>
				</footer>

			</div>
			<?php
			if (!empty($this->analytics_id)) {
				?>
				<script>
					(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

					ga('create', '<?php echo $this->analytics_id ?>', 'auto');
					ga('send', 'pageview');
				</script>
				<?php
			}
			?>

		</body>
		</html>
		<?php
	}


}