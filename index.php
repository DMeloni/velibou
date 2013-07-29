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
		<meta name="apple-mobile-web-app-capable" content="yes">
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<body>
		<div>
			<a href=".">Tous</a><br/>
			<?php
				if(!isset($_GET['number'])) {
					$listStationsJson = json_decode(file_get_contents(sprintf('%s/%s?apiKey=%s&contract=%s', $apiUrl, 'stations', $jcDecaultKey, $contract)), true);
					usort($listStationsJson, "tri_par_name");
					foreach($listStationsJson as $stationsJson){
						?><a href="<?php echo sprintf('?number=%s', str_pad($stationsJson['number'], 5, 0, STR_PAD_LEFT));?>"><?php echo $stationsJson['name'] . ' : ' .  $stationsJson['available_bike_stands'] . ' places libres' . '/' .  $stationsJson['available_bikes'] . ' vélos libres'; ?></a><br/><?php
					}	
				}else{
					if(!is_array($_GET['number'])){
						$listStationsJson = json_decode(file_get_contents(sprintf('%s/stations/%s?apiKey=%s&contract=%s', $apiUrl, $_GET['number'], $jcDecaultKey, $contract)), true);
						?><a href="<?php echo sprintf('?number=%s', str_pad($listStationsJson['number'], 5, 0, STR_PAD_LEFT));?>"><?php echo $listStationsJson['name'] . ' : ' .  $listStationsJson['available_bike_stands'] . ' places libres' . '/' .  $listStationsJson['available_bikes'] . ' vélos libres'; ?></a><br/><?php
					}else{
						foreach($_GET['number'] as $number){
							$listStationsJson = json_decode(file_get_contents(sprintf('%s/stations/%s?apiKey=%s&contract=%s', $apiUrl, $number, $jcDecaultKey, $contract)), true);
							?><a href="<?php echo sprintf('?number=%s', str_pad($listStationsJson['number'], 5, 0, STR_PAD_LEFT));?>"><?php echo $listStationsJson['name'] . ' : ' .  $listStationsJson['available_bike_stands'] . ' places libres' . '/' .  $listStationsJson['available_bikes'] . ' vélos libres'; ?></a><br/><?php
						}
					}
				}
			?>
		</div>
	</body>
</html>


