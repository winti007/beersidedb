<?php


ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);


/*
*  Barion PHP library usage example
*  
*  Getting detailed information about a payment
*  
*  � 2015 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

$myPosKey = "6d8d1602-9925-4d40-8722-d7e471d5c5e3";
$myPosKey = "6d8d1602-9925-4d40-8722-d7e471d5c5e3"; 

$paymentId = "62ce7fed40db46bd9f7b9c9127889794"; // <-- Replace this with the ID of the payment!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Prod);

// send the request
$paymentDetails = $BC->GetPaymentState($paymentId);

var_dump($paymentDetails);
// TODO: process the information contained in $paymentDetails