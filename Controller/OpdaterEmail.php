<?php
/**
 * Created by PhpStorm.
 * User: Benjamin-pc
 * Date: 29-11-2017
 * Time: 08:18
 */
//Lave en metode som kan hente og opdaterer den nye email med twig




$uri = "http://restmarsvineservicebamm.azurewebsites.net/service1.svc/Contactinfo/6";
$jsondata = file_get_contents($uri);

$convertToAssociativeArray = true;
$user = json_decode($jsondata, $convertToAssociativeArray);



require_once '../vendor/autoload.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('../Views');
$twig = new Twig_Environment($loader, array('auto_reload'=> true));

$parametersToTwig = array("Userinfo"=> $user);

$template = $twig->loadTemplate('OpdaterEmail.html.twig');


echo $template ->render($parametersToTwig);