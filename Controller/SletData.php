<?php
/**
 * Created by PhpStorm.
 * User: Benjamin-pc
 * Date: 04-12-2017
 * Time: 12:03
 */
$id = $_GET["id"];

$uri = "http://restmarsvineservicebamm.azurewebsites.net/service1.svc/Measurements";
$uri = $uri . "/" . $id;
echo $uri;
$ch = curl_init($uri);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$jsondata = curl_exec($ch);
$theDeletedTeacher = json_decode($jsondata, true);

$host = $_SERVER['HTTP_HOST'];
header("Location: http://{$host}/MarsvineWebPage/Controller/");
return;
