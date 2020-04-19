<?php

/*
ini_set ( "display_errors", E_ALL);
ini_set ( "error_reporting", E_ALL);
*/
/*
RewriteCond %{SERVER_PORT} 80 
RewriteCond %{REQUEST_URI} !^(.+)\.htm$
RewriteRule ^(.*)$ https://www.beerside.hu/$1 [L,R=301]
 */
include "php/eloszor.php";
    if($_SERVER["HTTPS"] != "on")
    {
        $Mehet=true;
        if (!(mb_strpos($_SERVER["REQUEST_URI"],"Xml_genera")===false))$Mehet=false;    
        if (!(mb_strpos($_SERVER["REQUEST_URI"],"privacy_policy")===false))$Mehet=false;    
        
        if ($Mehet)
        {
            $Param="";
            foreach ($_GET as $kulcs =>$item)
            {
                if ($Param=="")$Param.="?";
                    else $Param.="&";
                $Param.=$kulcs."=".$item;                
            }
        
            header("location: ".OLDALCIM.$Param);
            exit;
        }
    }
    
if (mb_strpos($_SERVER["REQUEST_URI"],"main.php")===false)
{
    
}else
{
    header('Location: ' . OLDALCIM, true, 303);
    exit;
    
}



$Fonal=new CVaz_bovit();



$Oldalir=$Fonal->Futtat(Focsop_azon)->Init();
echo $Oldalir;



?>