<?php

ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);

/*
*  Barion PHP library usage example
*  
*  Starting an immediate payment with two payee transactions
*  
*  � 2015 Barion Payment Inc.
*/

require_once '../library/BarionClient.php';

$myPosKey = "6d8d1602-9925-4d40-8722-d7e471d5c5e3"; // <-- Replace this with your POSKey!
$myEmailAddress = "tamas.cserniczky@beerside.hu"; // <-- Replace this with your e-mail address in Barion!
$myEmailAddress = "BeerSide"; // <-- Replace this with your e-mail address in Barion!

// Barion Client that connects to the TEST environment
$BC = new BarionClient($myPosKey, 2, BarionEnvironment::Test);


// create the item model
$item = new ItemModel();
$item->Name = "ExpensiveTestItem"; // no more than 250 characters
$item->Description = "An expensive test item for payment"; // no more than 500 characters
$item->Quantity = 1;
$item->Unit = "piece"; // no more than 50 characters
$item->UnitPrice = 50000;
$item->ItemTotal = 50000;
$item->SKU = "ITEM-03"; // no more than 100 characters

// create the payee transactions
$ptrans1 = new PayeeTransactionModel();
$ptrans1->POSTransactionId = "PTRANS-01"; // no more than 100 characters
$ptrans1->Payee = "user1@example.com"; // no more than 256 characters
$ptrans1->Total = 1000;
$ptrans1->Comment = "Royalties"; // no more than 640 characters

$ptrans2 = new PayeeTransactionModel();
$ptrans2->POSTransactionId = "PTRANS-02"; // no more than 100 characters
$ptrans2->Payee = "user2@example.com"; // no more than 256 characters
$ptrans2->Total = 3000;
$ptrans2->Comment = "Royalties"; // no more than 640 characters

// create the transaction
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-03";
$trans->Payee = $myEmailAddress; // no more than 256 characters
$trans->Total = 50000;
$trans->Comment = "Test Transaction"; // no more than 640 characters
$trans->AddItem($item); // add the item to the transaction
$trans->AddPayeeTransaction($ptrans1); // add the payee transactions to the transaction
$trans->AddPayeeTransaction($ptrans2);

// create the request model
$psr = new PreparePaymentRequestModel();
$psr->GuestCheckout = true; // we allow guest checkout
$psr->PaymentType = PaymentType::Immediate; // we want an immediate payment
$psr->FundingSources = array(FundingSourceType::All); // both Barion wallet and bank card accepted
$psr->PaymentRequestId = "TESTPAY-03"; // no more than 100 characters
$psr->PayerHint = "user@example.com"; // no more than 256 characters
$psr->Locale = UILocale::EN; // the UI language will be English 
$psr->OrderNumber = "ORDER-0001"; // no more than 100 characters
$psr->ShippingAddress = "12345 NJ, Example ave. 6.";
$psr->AddTransaction($trans); // add the transaction to the payment

// send the request
$myPayment = $BC->PreparePayment($psr);
var_dump($myPayment->RequestSuccessful);
if ($myPayment->RequestSuccessful === true) {
  // redirect the user to the Barion Smart Gateway
  header("Location: " . BARION_WEB_URL_TEST . "?id=" . $myPayment->PaymentId);
}