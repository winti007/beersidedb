<?php

/*
ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);
*/
/**
*php futás legelején fut le, beemeli a szükséges php fájlokat, beállítja a headert gzipes kiirást,kódolást, elindítja sessionoket, random függvényt, memóriát állítja be
*adatbázis kapcsolatot nem állítja be, azt az sql.php végzi   
*/
ini_set ( "memory_limit", "32M");


header('Content-Type: text/html; charset=utf8');
ini_set('default_charset', 'utf8');


function compress_output($output)
{
    return gzencode($output);
}


header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");

if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])&&(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')))
{
    ob_start("compress_output");
    header("Content-Encoding: gzip");
}

include("php/core/alap.php");
include "php/core/vaz.php";
include "php/core/sql.php";
include "php/vaz_bovit.php";

include "php/core/elsofutas.php";





include "php/allomany.php";

include "php/kapcsform.php";
include "php/felhasznalo.php";
include "php/szoveg.php";
include "php/dokumentum.php";
include "php/statcsoport.php";
include "php/multimedia.php";
include "php/nyelv.php";
include "php/focsoport.php";
include "php/hirlevel.php";
include "php/termek.php";
include "php/rendel.php";






session_start();
$Elotag="";

include "php/valtozok".$Elotag.".php";
include "php/sablon".$Elotag."/alap_sablon.php";
include "php/sablon".$Elotag."/form.php";
include "php/sablon".$Elotag."/vaz_sablon.php";
include "php/sablon".$Elotag."/kapcsform_sablon.php";
include "php/sablon".$Elotag."/szoveg_sablon.php";
include "php/sablon".$Elotag."/felhasznalo_sablon.php";
include "php/sablon".$Elotag."/multimedia_sablon.php";
include "php/sablon".$Elotag."/termek_sablon.php";
include "php/sablon".$Elotag."/rendel_sablon.php";



function make_seed() 
{
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}
srand(make_seed());
        
        function Kuponmod()
        {
            $Vissza=false;
            if (isset($_GET["Kuponmod"]))$_SESSION["Kuponmod"]=$_GET["Kuponmod"];
            if (isset($_SESSION["Kuponmod"]))$Vissza=$_SESSION["Kuponmod"];
            
            if ($Vissza)
            {
                
            }else
            {
                $Most=date("Y-m-d H:i:s");
                if (($Most>="2019-11-22 00:01:00")&&($Most<="2019-11-24 00:01:00"))$Vissza=true;
//                Idõtartam: nov. 22. péntek 00:01 - nov. 24. vasárnap 23:59
            }
            return $Vissza;
        }

        function Httpse()
        {
            if (empty($_SERVER['HTTPS']))return false;
            return true;
            
        }


?>