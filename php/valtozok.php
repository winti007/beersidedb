<?php

/**
*ADATBAZISNEV - adatbázis neve, ha üres akkor létrehozza mysql usert/jelszót adatbázist a program
*/

define ("KUPON_SZOV","BLACKBEERDAY19");
define ("KUPON_SZAZ",15);
define ("Arch_hirazon",19305);
define ("Hirdok_azon",1850);

define ("Face_appid","913947108775443");
define ("Face_secret","b48710aea460e153123b73b30ea540c3");
define ("Face_version","v2.2");
define ("Face_incl","faceb/autoload.php");
define ("Face_backurl","https://www.beerside.hu/callback.php");

define ("SZALL_KLTS",1450);
define ("REND_ALLAPOT","@@@Kosárba§§§+0!@@@megrendelt§§§+1!@@@befogadott§§§+2!@@@elküldött§§§+3!@@@átvett§§§+4!@@@törölt§§§+5");
define ("REND_ALLAPOT_K","- -+0!@@@megrendelt§§§+1!@@@befogadott§§§+2!@@@elküldött§§§+3!@@@átvett§§§+4+@@@törölt§§§+5");

define ("SZALL_MOD","+0!@@@MPL házhozszállítás 24h§§§+1!@@@Posta pont kiszállítás§§§+2!@@@Csomag autómata kiszállítás§§§+3!@@@Postán maradó kiszállítás§§§+4!@@@Személyes átvétel a Beer To Go-ban. Cím: 1092, Budapest, Ráday u 7.§§§+5!@@@Személyes átvétel Hops Beer Bar, 1077, Budapest, Wesselényi u 13.§§§+6!@@@GLS házhozszállítás§§§+7!@@@GLS csomagpont§§§+8");

define ("SZALL_MOD2","+0!@@@Postán maradó kiszállítás§§§+4!@@@Személyes átvétel a Beer To Go-ban. Cím: 1092, Budapest, Ráday u 7.§§§+5!@@@Személyes átvétel Hops Beer Bar, 1077, Budapest, Wesselényi u 13.§§§+6!@@@GLS házhozszállítás§§§+7!@@@GLS csomagpont§§§+8");



//define ("SZALL_MOD","+0!postai kiszállítás+1!bolti átvétel+4");
define ("FIZ_MOD","+0!@@@Közvetlen banki utalás§§§+1!@@@Utánvétel§§§+2!@@@Barion§§§+3");

define ("TEMPEST_TAG","nincs+0!2017 arany+1!2016 arany+2!2017 ezüst+3!2016 ezüst+4");

define ("Termek_szem_atvet",45079);

define ("ADATBAZISNEV","beerside");
define ("ADATBAZIS_USER","beerside");
define ("ADATBAZIS_PASS","KLkkrIkZ");

define ("ADATBAZIS_HOST","localhost");
define ("Amerika_azon",16223);



define ("QUAPCHA_FONT","fonts/font.gdf");
define ("FELTOLTES", "upload/");


define ("Focsop_azon","1");
define ("Felhcsop_azon","2");
define ("Senki_azon","4");
define ("Nyelv_azon","6");

define ("Hirlevel_azon","24");

define ("Labsz_azon","10");
define ("Butork_azon","11");
define ("Kapcs_azon","12");


define ("Nyito_azon1","13");
define ("Nyito_azon2","14");
define ("Nyito_azon3","15");
define ("Nyito_azon4","16");

define ("Fozde_azon","581");

define ("Webshop_azon","10");
define ("Kosar_azon","238");

define ("COOKIEKODKULCS", "di2iU11jkiwwscxmssdd"); 

function Utali_azon($Nyelv)
{
    switch ($Nyelv)
    {
        case "HU":
        $Vissza=17864;
        break;
        case "EN":
        $Vissza=17865;
        break;
    }
    return $Vissza;    
}

function Partn_azon($Nyelv)
{
    switch ($Nyelv)
    {
        case "HU":
//        $Vissza=11;
        $Vissza=16112;
        break;
        case "EN":
//        $Vissza=33;
        $Vissza=16253;
        break;
    }
    return $Vissza;
}


function Nyito_azon($Nyelv)
{
    switch ($Nyelv)
    {
        case "HU":
        $Vissza=4033;
        break;
        case "EN":
        $Vissza=4034;
        break;
    }
    return $Vissza;
}


function Rend_feltazon($Nyelv)
{
    switch ($Nyelv)
    {
        case "HU":
        $Vissza=21;
        break;
        case "EN":
        $Vissza=48;
        break;
    }
    return $Vissza;
}



function Felso_menu($Nyelv)
{
    switch ($Nyelv)
    {
        case "HU":
        $Vissza=9;
        break;
        case "EN":
        $Vissza=31;
        break;
    }
    return $Vissza;
}

function Also_menu($Nyelv)
{
    switch ($Nyelv)
    {
        case "HU":
        $Vissza=12;
        break;
        case "EN":
        $Vissza=34;
        break;
    }
    return $Vissza;
}


define ("Sqlhiba_latszik",1);

define ("Nemkell_tablaletrehoz",1);

define ("SQL_DEBUG",0);

define ("AFA","27");
define ("SZALL_INGYEN_TOL","15000");




/**
 * CK_EDITOR - ckeditor elérése
*/
define ("CK_EDITOR","/ckeditor/ckeditor.js");



/**
 * KOZ_OBJ - közös tárterületen objektumok adatai hol vannak - milyen indexen
*/

define ("KOZ_OBJ","OBJDAT");


/**
 * MYSQL_ENGIN - mysql tárolási módszer, create table-ökhöz kell
*/

define ("MYSQL_ENGINE","innodb");


 /**
 * KOZ_SESS - közös tárterületen sessionok adatai hol vannak - milyen indexen
 */
define ("KOZ_SESS","SESSDAT");
 /**
 * KOZ_SCRIPT - közös tárterületen scriptek hol vannak - milyen indexen
 */
define ("KOZ_SCRIPT","SCRIPTDAT");

 /**
 * KOZ_SCRIPT_UZ - közös tárterületen script alertek hol vannak - milyen indexen
 */
define ("KOZ_SCRIPT_UZ","SCRIPTUZDAT");
 /**
 * KOZ_HEAD - közös tárterületen head részbe kerülő html-ek hol vannak - milyen indexen
 */
define ("KOZ_HEAD","HTML_HEAD");

 /**
 * KOZ_PARAM - közös tárterületen paraméter tábla adatai hol vannak - milyen indexen
 */
define ("KOZ_PARAM","DAT_PARAM");




/**
* TOROLT_INDEX - törölt bitet ADATOK melyik indexébe tároljuk. Ez a törölt bit nincs adatbázisba, csak az objektumban.
*/
define ("TOROLT_INDEX","__TOROLTE");

/**
* STRING_CODE - string műveletekhez karakterkódolás
*/
define ("STRING_CODE","UTF-8");
/**
* STRING_CODE - string műveletekhez karakterkódolás
*/


//define("FROM_EMAIL","tunde.gyuricza@beerside.hu");
define("FROM_EMAIL","info@beerside.hu");
  

define("FROM_NEV","");

define ("MAIL_TARGY"," [BEERSIDE] ");
define ("OLDALCIM", "https://www.beerside.hu/");
define ("OLDALCIM2", "https://www.beerside.hu");

/**
 * DEF_PHP - default php
*/
define ("DEF_PHP","https://www.beerside.hu");
//define ("DEF_PHP","/main.php");




define ("BARION_BACK_URL","https://www.beerside.hu/barion_check.php");
define ("BARION_POSKEY","6d8d1602-9925-4d40-8722-d7e471d5c5e3");        
define ("BARION_EMAIL","tamas.cserniczky@beerside.hu");        


define ("OLDALEMAIL", "info@beerside.hu");


define ("MAXALLOMANYMERET", "100024");
define ("NYELVEK", "HU!EN");



if (class_exists('Imagick'))
{
define ("COMPOSITE_OVERLAY",imagick::COMPOSITE_OVERLAY);
define ("FILTER_CATROM",imagick::FILTER_CATROM);
}

/**
 * ESEMVALASZTO - url-be objektumot és feladatot elválvasztó karakter
*/
define ("ESEMVALASZTO","-");



define ("BEILLESZTKULSO", "|");
define ("BEILLESZTBELSO", "!");

?>
