<?php 
/**
 * Affiche la liste des stations de vélib
 */

$apiUrl = 'https://api.jcdecaux.com/vls/v1';
$jcDecaultKey = 'e7e6f35a68c26186b39d6c3b3136968d174a0e4d';
$contract = 'Paris';


function tri_par_name($a, $b)
{
	if ($a['name'] == $b['name']) {
		return 0;
	}
	return ($a['name'] < $b['name']) ? -1 : 1;
}

function afficheStation($numberStation, $nomStation, $nombreVelosDispo, $nombrePlacesDispo){
	$bikeStateClass = 'green';
	if($nombreVelosDispo < 5){
		$bikeStateClass = 'orange';
	}
	if($nombreVelosDispo == 0){
		$bikeStateClass = 'red';
	}	
	$parkingStateClass = 'green';
	if($nombrePlacesDispo < 5){
		$parkingStateClass = 'orange';
	}
	if($nombrePlacesDispo == 0){
		$parkingStateClass = 'red';
	}	
	$parkingSvg = sprintf('<svg class="icon %s" version="1.0" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		 width="99.999px" height="100px" viewBox="0 0 99.999 100" enable-background="new 0 0 99.999 100" xml:space="preserve">
	<path d="M0,50.002C0,77.571,22.43,100,50,100c27.569,0,49.999-22.43,49.999-49.999C99.999,22.43,77.569,0,50,0
		C22.43,0,0,22.43,0,50.002L0,50.002z M8.8,50.002C8.8,27.283,27.282,8.8,50,8.8c22.718,0,41.199,18.483,41.199,41.202
		c0,22.716-18.48,41.199-41.199,41.199C27.282,91.201,8.8,72.718,8.8,50.002L8.8,50.002z"/>
	<path d="M65.181,36.761c-1.319-2.011-2.955-3.317-4.909-3.917c-1.272-0.399-4.003-0.601-8.189-0.601H41.029v40.022h6.836V57.162
		h4.503c3.126,0,5.513-0.191,7.161-0.574c1.214-0.309,2.406-0.94,3.581-1.896c1.177-0.953,2.144-2.269,2.905-3.943
		c0.763-1.674,1.144-3.74,1.144-6.196C67.16,41.37,66.5,38.771,65.181,36.761z M59.317,47.817c-0.522,0.901-1.245,1.562-2.169,1.984
		c-0.923,0.423-2.75,0.634-5.484,0.634h-3.798V38.969h3.353c2.501,0,4.164,0.093,4.994,0.276c1.124,0.239,2.055,0.838,2.788,1.792
		c0.734,0.956,1.103,2.168,1.103,3.639C60.103,45.871,59.842,46.917,59.317,47.817z"/>
	</svg>', $parkingStateClass);

	$bikeSvg = sprintf('<svg class="icon %s" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="100px" height="100px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
	<g id="&#1057;&#1083;&#1086;&#1081;_1" display="none">
		<g display="inline"></g>
	</g>
	<g id="&#1057;&#1083;&#1086;&#1081;_2">
		<path d="M50,0C22.386,0,0,22.386,0,50s22.386,50,50,50s50-22.386,50-50S77.614,0,50,0z M50,96C24.595,96,4,75.404,4,50   C4,24.595,24.595,4,50,4c25.404,0,46,20.595,46,46C96,75.404,75.404,96,50,96z"/>
		<path d="M74,45c-2.067,0-4.014,0.495-5.749,1.354l-3.918-6.056L65.767,37H66c1.104,0,2-0.896,2-2s-0.896-2-2-2H56   c-1.104,0-2,0.896-2,2s0.896,2,2,2h6.494l-0.652,1.5H39.649c0.393-2.764-1.781-4.273-2.888-5.038   c-0.282-0.194-0.572-0.395-0.701-0.523c-0.703-0.703-0.712-1.773-0.423-2.471C35.82,30.027,36.205,29.5,37,29.5h7   c0.829,0,1.5-0.671,1.5-1.5s-0.671-1.5-1.5-1.5h-7c-1.857,0-3.403,1.055-4.134,2.821c-0.818,1.974-0.386,4.28,1.074,5.74   c0.321,0.321,0.708,0.588,1.118,0.871c1.473,1.017,1.864,1.488,1.535,2.537l-3.941,8.377C30.704,45.681,28.434,45,26,45   c-7.18,0-13,5.82-13,13s5.82,13,13,13s13-5.82,13-13c0-3.641-1.5-6.927-3.912-9.287l0.884-1.878l17.011,12.378   c0.198,0.145,0.42,0.215,0.646,0.251c0.024,0.005,0.05,0.007,0.075,0.011c0.054,0.006,0.106,0.025,0.16,0.025   c0.014,0,0.026-0.004,0.04-0.005c0.016,0.001,0.03,0.005,0.046,0.005H55h6.093C61.838,65.972,67.328,71,74,71c7.18,0,13-5.82,13-13   S81.18,45,74,45z M37,58c0,6.075-4.925,11-11,11s-11-4.925-11-11s4.925-11,11-11c2.13,0,4.112,0.615,5.796,1.664L30.9,50.569   c-1.666,3.567-2.013,4.31-5.571,6.089c-0.741,0.371-1.041,1.271-0.671,2.013c0.263,0.525,0.792,0.829,1.343,0.829   c0.226,0,0.455-0.051,0.67-0.158c4.493-2.246,5.161-3.678,6.947-7.503l0.556-1.182C35.925,52.605,37,55.174,37,58z M66.825,49.673   l4.417,6.827h-8.128C63.485,53.781,64.845,51.382,66.825,49.673z M54.098,56.313L37.272,44.071l1.209-2.571h22.056L54.098,56.313z    M57.288,56.5l5.608-12.898l2.829,4.372c-2.522,2.084-4.238,5.104-4.633,8.526H57.288z M74,69c-5.565,0-10.152-4.137-10.886-9.5H74   c0.828,0,1.5-0.672,1.5-1.5c0-0.296-0.089-0.57-0.236-0.803l-0.004-0.012l-5.915-9.141C70.76,47.381,72.334,47,74,47   c6.075,0,11,4.925,11,11S80.075,69,74,69z"/>
	</g>
	</svg>', $bikeStateClass);
	?><a href="<?php echo sprintf('?number=%s', str_pad($numberStation, 5, 0, STR_PAD_LEFT));?>"><?php echo substr($nomStation, 8, strlen($nomStation)) . '</a> '; echo $nombreVelosDispo . $bikeSvg . ' ' . $nombrePlacesDispo . $parkingSvg; ?><br/><?php
}

?><!DOCTYPE html>
<html lang="fr">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Vélibou</title>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="viewport" content="width=device-width, user-scalable=yes">
		<link rel="apple-touch-icon" href="favicon.png">
		<link rel="shortcut icon" href="favicon.ico">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<link href="css/style.css" type="text/css" rel="stylesheet"/>
	</head>
	<body>
		<div>
			<a href=".">Tous</a><br/>
			<?php
				if(!isset($_GET['number']) && !isset($_GET['json'])) {
					$listStationsJson = json_decode(file_get_contents(sprintf('%s/%s?apiKey=%s&contract=%s', $apiUrl, 'stations', $jcDecaultKey, $contract)), true);
					usort($listStationsJson, "tri_par_name");
					foreach($listStationsJson as $stationsJson){
						afficheStation($stationsJson['number'], $stationsJson['name'], $stationsJson['available_bikes'], $stationsJson['available_bike_stands'] );
					}	
				}
				// eg : ?number=44102
				if(isset($_GET['number'])){
					if(!is_array($_GET['number'])){
						$listStationsJson = json_decode(file_get_contents(sprintf('%s/stations/%s?apiKey=%s&contract=%s', $apiUrl, $_GET['number'], $jcDecaultKey, $contract)), true);
						afficheStation($listStationsJson['number'], $listStationsJson['name'], $listStationsJson['available_bikes'], $listStationsJson['available_bike_stands'] );
					}else{
						foreach($_GET['number'] as $number){
							$listStationsJson = json_decode(file_get_contents(sprintf('%s/stations/%s?apiKey=%s&contract=%s', $apiUrl, $number, $jcDecaultKey, $contract)), true);
							afficheStation($listStationsJson['number'], $listStationsJson['name'], $listStationsJson['available_bikes'], $listStationsJson['available_bike_stands'] );
						}
					}
				}
				// eg : ?json=[{"title":"Charenton","number":"44102"}]
				// eg : ?json=[{"title":"Charenton","number":"44102"}]
				// eg : ?json=[{"title":"Charenton","numbers":["00111","00"]}]
				if(isset($_GET['json'])){
					$jsonStations = json_decode($_GET['json'], true);
					foreach($jsonStations as $stations){
						?><h2><?php echo $stations['title'];?></h2><?php
						foreach($stations['numbers'] as $stationNumber){
							$listStationsJson = @json_decode(file_get_contents(sprintf('%s/stations/%s?apiKey=%s&contract=%s', $apiUrl, $stationNumber, $jcDecaultKey, $contract)), true);
							if(is_array($listStationsJson)){
								afficheStation($listStationsJson['number'], $listStationsJson['name'], $listStationsJson['available_bikes'], $listStationsJson['available_bike_stands'] );
							}
						}
					}
				}				
			?>
		</div>
	</body>
</html>


