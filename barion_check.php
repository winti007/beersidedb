<?php

/*
ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);
*/

include "php/eloszor.php";
$Fonal=new CVaz_bovit();
$GLOBALS["Nemkelltrans"]=1;

$Focsp=$Fonal->ObjektumLetrehoz(Focsop_azon,0);

$_SESSION["Korvalaszt"]=1;
            if(!(is_object($Focsp->Sessad("Aktfelh"))))
            {
                $Focsp->Futtat(Felhcsop_azon)->CsakKijelentkezes();
                $Focsp->Futtat(Felhcsop_azon)->Bejelentkezbeallit();
            }


$Sikeres=false;
$Oldalir="";
$paymentId=$Fonal->Postgetv("paymentId");

Fiz_logba("Barion válasz",0,0,"paymentId=".$paymentId,0);

if($paymentId=="")
{
    $Uzen="Érvénytelen hívás";
}else
{
    $Azon=$Fonal::$Sql->Lekerst("select * from BANKI_TRANSAZON where PaymentId='$paymentId' ");
    if ($Azon)
    {
        if (count($Azon)>1)
        {
            $Uzen="Hiba, több tranzakció?";
        }else
        {
            $Obj=$Fonal->ObjektumLetrehoz($Azon[0]["REND_VZ_AZON_I"],0);
        
        
            require_once 'barion/library/BarionClient.php';

            $myPosKey = BARION_POSKEY;
            $Uzen="Sikertelen fizetés";


            // Barion Client that connects to the TEST environment
            $BC = new BarionClient($myPosKey, 2, BarionEnvironment::Prod);

            // send the request
            $paymentDetails = $BC->GetPaymentState($paymentId);
            $Status=$paymentDetails->Status;
            $Data=$Obj->Kosarinfo();
            $OSSZEG=$Data["ERTEK"];
            
            Fiz_logba("Barion válasz data",0,0,serialize($paymentDetails),0);
            
            switch ($Status)
            {
                case "Canceled":
                    $Uzen="Felhasználó által visszautasított fizetés!";
                break;
                case "Expired":
                    $Uzen="Lejárt fizetés!";
                break;
                case "Failed":
                    $Uzen="Sikertelen fizetés!";
                break;
                case "Succeeded":
                    if ($paymentDetails->Total==$OSSZEG)
                    {
                        $Uzen="Sikeres fizetés!";
                        $Sikeres=true;
                        $Oldalir=$Obj->Sikeres_fizetes($paymentId,$Azon[0]["AZON_I"]);
                    }else
                    {
                        $Uzen="Összeg nem egyezik!";
                    }
                break;
                
            }

            
        }
    }else $Uzen="Nincs tranzakció!";
}

Fiz_logba("Barion eredmény",0,0,$Uzen,0);

if ($Oldalir!="")
{
    $Oldalir=$Fonal->Futtat(Nyelv_azon)->Cserel($Oldalir);
    echo $Oldalir;    
}else
{

    $Vissza["Tartalom"]=$Uzen;
    $Vissza["Cim"]="Fizetés eredménye";
    
    
     
    $Oldalir=$Focsp->Sablonbe("Oldal",$Vissza);
    
    $Oldalir=$Fonal->Futtat(Nyelv_azon)->Cserel($Oldalir);
    echo $Oldalir;
                

}


?>