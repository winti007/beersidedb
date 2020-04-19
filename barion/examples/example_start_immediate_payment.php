<?php

/*
*  Barion PHP library usage example
*  
*  Starting an immediate payment with one product
*  
*  � 2015 Barion Payment Inc.
*/

ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);


require_once '../library/BarionClient.php';

$myPosKey = "6d8d1602-9925-4d40-8722-d7e471d5c5e3"; // <-- Replace this with your POSKey!
//$myPosKey = "b4cb27c9-66a5-46f2-842b-1bf237325618"; // <-- Replace this with your POSKey!

$myEmailAddress = "tamas.cserniczky@beerside.hu"; // <-- Replace this with your e-mail address in Barion!

$userEmailAddress=$myEmailAddress;


// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Prod);

// create the item model
$item = new ItemModel();
$item->Name = "TestItem"; // no more than 250 characters
$item->Description = "A test item for payment"; // no more than 500 characters
$item->Quantity = 1;
$item->Unit = "piece"; // no more than 50 characters
$item->UnitPrice = 1000;
$item->ItemTotal = 1000;
$item->SKU = "ITEM-01"; // no more than 100 characters

// create the transaction
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = $myEmailAddress; // no more than 256 characters
$trans->Total = 1000;
$trans->Comment = "Test Transaction"; // no more than 640 characters
$trans->AddItem($item); // add the item to the transaction

// create the request model
$psr = new PreparePaymentRequestModel();

$psr->GuestCheckout = true; // we allow guest checkout
$psr->CallbackUrl="http://dev-gt.ysolutions.hu/";
$psr->RedirectUrl="http://dev-gt.ysolutions.hu/";


$psr->PaymentType = PaymentType::Immediate; // we want an immediate payment
$psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
$psr->PaymentRequestId = "TESTPAY-01"; // no more than 100 characters
$psr->PayerHint = $userEmailAddress; // no more than 256 characters
$psr->Locale = UILocale::HU; // the UI language will be English 
$psr->Currency = Currency::HUF;
$psr->OrderNumber = "ORDER-0001"; // no more than 100 characters
$psr->ShippingAddress = "12345 NJ, Example ave. 6.";
$psr->AddTransaction($trans); // add the transaction to the payment


    

// send the request
$myPayment = $BC->PreparePayment($psr);
VAR_DUMP($myPayment->PaymentId);
    var_dump($myPayment);

    exit;

if ($myPayment->RequestSuccessful === true) {
    
  // redirect the user to the Barion Smart Gateway
//  header("Location: " . BARION_WEB_URL_TEST . "?id=" . $myPayment->PaymentId);

  header("Location: " . BARION_WEB_URL_PROD . "?id=" . $myPayment->PaymentId);
  
}else
{
    echo "hiba";
}