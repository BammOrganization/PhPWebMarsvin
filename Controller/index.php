<?php
/**
 * Created by PhpStorm.
 * User: Benjamin-pc
 * Date: 27-11-2017
 * Time: 12:49
 */
$uri = "http://restmarsvineservicebamm.azurewebsites.net/service1.svc/measurements/";
$jsondata = file_get_contents($uri);

$convertToAssociativeArray = true;
$data = json_decode($jsondata, $convertToAssociativeArray);


$TimestampListe = array();
foreach ($data as $mindate) {
    $Id = $mindate['Id'];
    $Time = $mindate['DateTime'];
    $Dato = parseJsonDate($Time);
    $dB = $mindate['dB'];
    $ImageLink = $mindate['ImageLink'];
    $minliste = array('Id' => $Id, 'DateTime' => $Dato, 'dB' => $dB, 'ImageLink' => $ImageLink);

    array_push($TimestampListe, $minliste);
}

function parseJsonDate($date)
{
    // Match the time stamp (microtime) and the timezone offset (may be + or -) and also negative Timestamps
    preg_match('/\/Date\((-?\d+)([+-]\d{4})\)/', $date, $matches);


    $seconds = $matches[1] / 1000;                // microseconds to seconds

    $dateTime = date('d-m-Y H:i:s', $seconds); // date and time

    return $dateTime;
}





require_once '../vendor/autoload.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('../Views');
$twig = new Twig_Environment($loader, array('auto_reload'=> true));

$template = $twig->loadTemplate('ShowTable.html.twig');

$parametersToTwig = array("Pidata"=> $TimestampListe);

echo $template ->render($parametersToTwig);
