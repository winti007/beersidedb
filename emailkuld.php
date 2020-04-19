<?php

ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);
define ("HIRLEVFROM_EMAIL","info@beerside.hu");


if ((isset($_POST["Kuld"]))&&(($_POST["Kuld"]=="1")))
{
    Mailkuld($_POST["Targy"],$_POST["Level"],$_POST["Email"]);
    echo "<script>alert('Email elküldve: ".$_POST["Email"]." ');</script>";
}

echo Bekero();

function Bekero()
{

    return "<table width=600 cellpadding=3 cellspacing=3 align=center>
    
    <form method='post' action='?' >
    <input type='hidden' name='Kuld' id='Kuld' value='1' >
    <tr>
      <td>Címzett címe:</td>
      <td><input type='text' name='Email' id='Email' / > </td>
    </tr>  
    <tr>
      <td>Email tárgya:</td>
      <td><input type='text' name='Targy' id='Targy' value='Teszt' /> </td>
    </tr>  
    <tr>
      <td>Email szövege:</td>
      <td><input type='text' name='Level' id='Level' value='Teszt levél' /> </td>
    </tr>  
    <tr>
      <td>Feladó:</td>
      <td><input type='text' name='Felado' id='Felado' value='".HIRLEVFROM_EMAIL."' / > </td>
    </tr>  
    <tr>
      <td></td>
      <td><input type='submit' value='Email küldése'  / > </td>
    </tr>      
    </form>";
    
} 


        function Mailkuld($TARGY,$SZOVEG,$TO_EMAIL,$TO_NAME=null)
        {
                
                require_once "php/Phpmailer.php";
                $mailer = new Mail_Phpmailer(true);

                if ($mailer->ValidateAddress($TO_EMAIL))
                {
                    if ($TO_NAME===null)$TO_NAME="";
                    
                    $mailer->CharSet = 'utf-8';
                    $mailer->Encoding = 'base64';
                
                    $mailer->SetFrom("info@beerside.hu");
                    $mailer->AddAddress($TO_EMAIL,$TO_NAME);
                    $mailer->Subject =$TARGY;
                    $mailer->Body = $SZOVEG;
                    $mailer->AltBody = strip_tags($SZOVEG);
                    $mailer->Send();
                   
                }            
        }    



?>