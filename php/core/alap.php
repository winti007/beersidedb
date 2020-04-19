<?php


 /**
 * CKozostar - közös tárterület kezelése a php futása során, adatok ideiglenes tárolását végzi egy futás során 
 * $Data változóba tárolja az adatokat, static hogy ugyanarra mutasson mindig
 */

class CKozostar  
{
    protected static $Data=array();
    
 /**
 * __construct - létrehozás, nem kell semmit csinálni, de lehessen hivatkozni rá ha vmikor mégis 
 */    
    public function __construct()
    {
    }   
        

 /**
 * __destruct - objektum megsemmisítés, nem kell semmit csinálni, de lehessen hivatkozni rá ha vmikor mégis  
 */    
    public function __destruct()
   	{
   	} 
                
    
 /**
 * Kozbe - közös területre tesz le értéket
 * @param $NEV - session neve 
 * @param $ERTEK - session értéke *  
 */
    public static function Kozbe($NEV,$ERTEK)
    {
        CKozostar::$Data[$NEV]=$ERTEK;        
    }



 /**
 * Kozad - közös területből kérdez le adatot
 * @param $Nev - neve 
 * @param $Default - ha nem létezik, ezt a default értéket adja vissza 
 * @return string 
 */
    public static function Kozad($Nev,$Default="")
    {

        if (isset(CKozostar::$Data[$Nev]))$Vissza=CKozostar::$Data[$Nev];
                else $Vissza=$Default;
        return $Vissza;
    }

 /**
 * Kozurit - közös területből minden ürít 
 */
    public static function Kozurit()       
    {
            foreach (CKozostar::$Data as $Kulcs =>$Ertek)
            {
                unset(CKozostar::$Data[$Kulcs]);
            }
            CKozostar::$Data=null;
            CKozostar::$Data=array();
            
    }
    
 /**
 * Kozuritegy - közös területből egy indexet ürít
 * @param $Nev - közös terület neve 
 */    
    function Kozuritegy($Nev)
    {
        if (isset(CKozostar::$Data[$Nev]))
        {
            if (is_array(CKozostar::$Data[$Nev]))
            {
                foreach (CKozostar::$Data[$Nev] as $Kulcs =>$Ertek)
                {
                    unset(CKozostar::$Data[$Kulcs]);
                }
                unset(CKozostar::$Data[$Nev]);
            }else
            {
                unset(CKozostar::$Data[$Nev]);
            }
            
        }
    }


 /**
 * Kozbetomb - közös területre tömbbe tesz le egy adatot 
 * @param $Index1 - Index1 
 * @param $Ertek - Érték 
 * @param $Index2 - Index2: ha üres hozzáfűz új elemet pl scripteket gyűjteni 
 * @param $Index3 - nem kötelező, ha ki van töltve akkor 3. indexbe teszi $Ertek et pl adatbázis adatok tárolása [$Index1-KOZ_OBJ][$Index2-OBJAZON][$Index3-MEZONEV]  
 */
    public static function Kozbetomb($Index1,$Ertek,$Index2="",$Index3="")
    {
        $Eddig=CKozostar::Kozad($Index1,array());
        if ($Index2!="")
        {
            if ($Index3!="")$Eddig[$Index2][$Index3]=$Ertek;
                       else $Eddig[$Index2]=$Ertek;
        }
        else $Eddig[]=$Ertek;
        CKozostar::Kozbe($Index1,$Eddig);
    }
    
    
 /**
 * Kozadtomb - közös területből kérdez le adatot, az adat tömb formájába van, pl KOZ_OBJ,KOZ_SQL,KOZ_SCRIPT -eket ad vissza
 * @param $INDEX1 - első index 
 * @param $INDEX2 - második index 
 * @return array: Van 0 vagy 1, Ertek -> Ertek 
 */
    public static function Kozadtomb($INDEX1,$INDEX2)
    {
        $Vissza["Van"]=0;
        $Vissza["Ertek"]="";
        $Van=CKozostar::Kozad($INDEX1,array());

        if (isset($Van[$INDEX2]))
        {
            $Vissza["Van"]=1;
            $Vissza["Ertek"]=$Van[$INDEX2];
        }           

        return $Vissza;
    }
             
}




 /**
 * CAlap - mindenhonnan elérhető alap függvények, nem egy objektumhoz kötődnek 
 * static: nem lehet bennük this, ugyanarra mutatnak
 * Sql tag: static hogy kevesebb helyet foglaljon a memóriába
 */

class CAlap extends CKozostar  
{    
        public static $Sql;  
        

 /**
 * __construct - objektum létrehozáskor Sql tagot betölteni  
 */    
        public function __construct()
        {
            if (!(is_object(CAlap::$Sql)))
            {
                CAlap::$Sql=new CMysql();
            }            
            parent::__construct();
        }
        
        

 /**
 * __destruct - objektum megsemmisítés, nem kell semmit csinálni, de lehessen hivatkozni rá ha vmikor mégis  
 */    
    public function __destruct()
   	{
   	} 
       
            
        
        
 /**
 * Filev  - formból jövő fájlokat vesz át
 * @param $Valtozo - formba lévő bekérő neve
 * @param $Adattipus: tmp_name -> elérést ad vissza, name ->fájlnevet ad vissza 
 * @return string 
 */         
        public static function Filev($Valtozo,$Adattipus)
        {
           
            if (isset($_FILES[$Valtozo][$Adattipus]))$Vissza=$_FILES[$Valtozo][$Adattipus];
                    else $Vissza="";

            return $Vissza;
        }



 /**
 * Postgetv - minden bejövő paramétert url/formból ezen keresztül veszünk át
 * @param $Valtozo - url/formtag neve
 * @param $Chboxe - ha formból jön és checkboxból -> 0 vagy 1 (ha value=on) ad vissza 
 * @return string 
 */
        public static function Postgetv($Valtozo,$Chboxe=0)
        {
            if ($Chboxe)
            {
                if (isset($_POST[$Valtozo])&&($_POST[$Valtozo]=="on"))$Vissza=1;
                    else $Vissza=0;
            }else
            {
                if (isset($_POST[$Valtozo]))
                {
                        $Vissza=$_POST[$Valtozo];
                        if (!(is_array($Vissza)))
                        {
                                $Vissza=stripslashes($Vissza);
                                $Vissza=addslashes($Vissza);
                        }
                }
                else
                if (isset($_GET[$Valtozo]))
                {
                    $Vissza=$_GET[$Valtozo];
                    if (!(is_array($Vissza)))
                    {
                            $Vissza=stripslashes($Vissza);
                            $Vissza=addslashes($Vissza);
                    }
                }
                else
                $Vissza=null;
            }
                return $Vissza;
        }


        


 /**
 * Sessbe - minden session beállítása ezen keresztül történik, először közös területre, futás végén kiirni sessionbe
 * @param $NEV - session neve 
 * @param $ERTEK - session értéke, ha "null" akkor törlődik  
 */
        public static function Sessbe($NEV,$ERTEK)
        {
            CKozostar::Kozbetomb(KOZ_SESS,$ERTEK,$NEV);
        }


        
  /**
 * Sessionad - sessionök lekérdezése ezen keresztül történik, ha közös táron van, akkor azt adja vissza, ha nincs session tömbből
 * @param $Nev - session neve 
 * @param $Default - ha nem létezik a session, ezt a default értéket adja vissza 
 * @return string 
 */
        public static function Sessad($Nev,$Default="")
        {
            $Data=CKozostar::Kozadtomb(KOZ_SESS,$Nev);
            
            $Van=$Data["Van"];
            if ("$Van"=="1")
            {
                $Vissza=$Data["Ertek"];
                if (is_string($Vissza))
                {
                    if ("$Vissza"=="null")$Vissza=$Default;
                }
                if (is_array($Vissza))
                {
                    $Visszat=array();
                    foreach ($Vissza as $kulcs => $egyv)    
                    {
                        if (is_string($egyv))
                        {
                            if ("$egyv"!="null")$Visszat[$kulcs]=$egyv;
                        }else $Visszat[$kulcs]=$egyv;
                    }
                    $Vissza=$Visszat;
                }
                
            }else
            {
                if (isset($_SESSION[$Nev]))$Vissza=$_SESSION[$Nev];
                                      else $Vissza=$Default;
                if (is_array($Vissza))
                {
                    $Visszat=array();
                    foreach ($Vissza as $kulcs => $egyv) 
                    {
                        if (is_string($egyv))
                        {
                            if ("$egyv"!="null")$Visszat[$kulcs]=$egyv;
                        }else $Visszat[$kulcs]=$egyv;
                    }
                    $Vissza=$Visszat;
                }            
            
            }
            return $Vissza;
        }

 /**
 * Kiiras_sess - futás végén sessionok kiírása
 */
        public function Kiiras_sess()
        {
            $Data=$this->Kozad(KOZ_SESS,array());
            foreach ($Data as $kulcs => $ertek)
            {
                $Beirt=false;
                if (is_string($ertek))
                {
                    if ("$ertek"=="null")
                    {
                        $this->Sessurit($kulcs);
                        $Beirt=true;
                    }
                }
                if (!($Beirt))$_SESSION[$kulcs]=$ertek;
            }
        }        
      
 

 /**
 * Sessbetomb - session beállítása tömb formájába 
 * @param $Index1 - Index1 
 * @param $Ertek - Érték 
 * @param $Index2 - Index2 
  
 */
    public static function Sessbetomb($Index1,$Ertek,$Index2="")
    {
        $Eddig=self::Sessad($Index1,array());
        if ($Index2!="")
        {
            $Eddig[$Index2]=$Ertek;
        }
        else $Eddig[]=$Ertek;
        self::Sessbe($Index1,$Eddig);
    }
    
 /**
 * Sessadtomb - sessionok lekérdezése tömb formájába
 * @param $INDEX1 - első index 
 * @param $INDEX2 - második index 
 * @return array: Van 0 vagy 1, Ertek -> Ertek 
 */
    public static function Sessadtomb($INDEX1,$INDEX2)
    {
        $Vissza["Van"]=0;
        $Vissza["Ertek"]="";
        $Van=self::Sessad($INDEX1,array());
        if (isset($Van[$INDEX2]))
        {
            if ($Van[$INDEX2]=="null")
            {
                $Vissza["Van"]=0;
                $Vissza["Ertek"]="";
            }else
            {
                $Vissza["Van"]=1;
                $Vissza["Ertek"]=$Van[$INDEX2];
            }                
        }           
        return $Vissza;
    }        

/**
 * Sessurit - sessionök ürítése ezen keresztül történik
 * @param $Nev - session neve 
 */
        private function Sessurit($Nev)       
        {
            if (isset($_SESSION[$Nev]))
            {
                if (is_array($_SESSION[$Nev]))
                {
                    foreach ($_SESSION[$Nev] as $Kulcs =>$Ertek)
                    {
                        unset($_SESSION[$Nev][$Kulcs]);
                    }
                    if (isset($_SESSION[$Nev]))unset($_SESSION[$Nev]);
                    
                }else unset($_SESSION[$Nev]);    
            }
            
        }        
    
    /**
 * ScriptTarol - javascriptes parancsot teszi közös tárba, futás végén lesz kiiratva 
 * @param $Utasitas - utasítás 
 */
        public static function ScriptTarol($Utasitas)
        {
            CKozostar::Kozbetomb(KOZ_SCRIPT,$Utasitas);    
        }

 /**
 * ScriptUzenetAd - javascriptes alert parancsot teszi közös tárba, futás végén lesz kiiratva. Azért kell különvenni scriptektől mert volt olyan ha ez a html kód elején van nem töltődött be rendesen a lap, html végén kell lennie.  
 * @param $Utasitas - utasítás 
 */
        public static function ScriptUzenetAd($Uzenet)
        {
                $Utasitas="\nalert(\"$Uzenet\");\n";
                CKozostar::Kozbetomb(KOZ_SCRIPT_UZ,$Utasitas);   
        }

 /**
 * Headbe - html head részébe lehet beadni neki tetszőleges html tartalmat  
 * @param $Utasitas - utasítás 
 */
        public static function Headbe($Utasitas)
        {
            CKozostar::Kozbetomb(KOZ_HEAD,$Utasitas);    
        }



 /**
 * Kodir - captcha-ba ellenőrző kódot rajzolja ki
 * @param $Kod - kiirandó kód
 * @return image blob  
 */
        public function Kodir_pb_fut()
        {
                $Kod=$this->AllapotKi("KODIR");
                $ardimg = @imagecreate(100, 20) or die("Cannot Initialize new GD image stream");
                $background_color = imagecolorallocate($ardimg,150,150,150);
                $Hossz=strlen($Kod);
                $font = imageloadfont(QUAPCHA_FONT);
                for ($c=0;$c<$Hossz;$c++)
                {
                        $char=substr($Kod,$c,1);
                        $szinkod=sscanf("#ffffff", '#%2x%2x%2x');
                        $text_color = imagecolorallocate($ardimg,$szinkod[0],$szinkod[1],$szinkod[2]);
                        imagechar($ardimg,$font,2+($c*21),0,$char,$text_color);
                }


                imagejpeg($ardimg);
                imagedestroy($ardimg);
                exit;
        }



 /**
 * Nyelvadpub  - visszaadja az aktuális nyelv kódját, ezt a CNyelvForditCsoport osztály csinálja, betölti azt az objektumot és meghívja ezt a metódusát 
 * @return string 
 */         
        public function Nyelvadpub()
        {            
            
            $Vaz=new CVaz_bovit();
            $Vissza=$Vaz->Futtat(Nyelv_azon)->Nyelvadpubl();
            return $Vissza;
        }

 /**
 * EgyezoObjektum  - két objektum ugyanaz e  
 * @return 0 vagy 1 
 */  
        public static function EgyezoObjektum($AObjektum, $BObjektum)
        {
            $Eredmeny=$AObjektum->Azonosit()==$BObjektum->Azonosit();
            return $Eredmeny;
        }

     
 /**
 * Objvege - megsemmisít egy objektumot, __destruct automatikusan meghívódik utána
 * @param $Obj - objektum
 */     public static function Objvege(&$Obj)
        {
                unset($Obj);
        }
        
 /**
 * unhtmlentities - html entitásokat rendes karakterré alakítja
 * @param $string - string
 * @return string
 */         
        public static function unhtmlentities($str)
        {
            $ret = html_entity_decode($str, ENT_COMPAT, STRING_CODE);
            $p2 = -1;
            for(;;) {
            $p = strpos($ret, '&#', $p2+1);
            if ($p === FALSE)
                break;
            $p2 = strpos($ret, ';', $p);
            if ($p2 === FALSE)
                break;
           
            if (substr($ret, $p+2, 1) == 'x')
                $char = hexdec(substr($ret, $p+3, $p2-$p-3));
            else
                $char = intval(substr($ret, $p+2, $p2-$p-2));
           
            $newchar = iconv(
            'UCS-4', STRING_CODE,
            chr(($char>>24)&0xFF).chr(($char>>16)&0xFF).chr(($char>>8)&0xFF).chr($char&0xFF)
            );
            $ret = substr_replace($ret, $newchar, $p, 1+$p2-$p);
            $p2 = $p + strlen($newchar);
            }
            return $ret;
                
        }
       
 /**
 * Tombre - konstansot/stringet tömbbé alakít határoló karakterek alapján. 
 * pl Január+1!Február+2 -> 
 * Tomb[0][0]="Január"
 * Tomb[0][1]="1"
 * Tomb[1][0]="Február"
 * Tomb[1][1]="2" ..   
 * @param $KONSTANS - átalakítandó string 
 * @param $Kulsovalaszt - tömb elemeit határoló karakter, alapból ! 
 * @param $Belsovalaszst - határoló karakter, alapból + 
 * @return array
 */             
        public static function Tombre($KONSTANS,$Kulsovalaszt=null,$Belsovalaszst=null)
        {
                if ($Kulsovalaszt===null)$Kulsovalaszt="!";
                if ($Belsovalaszst===null)$Belsovalaszst="+";
                
                $Vissza=array();
                $Ert=explode($Kulsovalaszt,$KONSTANS);
                foreach ($Ert as $Egy)
                {
                        $Reszek=explode($Belsovalaszst,$Egy);
                        $Vissza[]=$Reszek;
                }
                return $Vissza;

        }     
        

 /**
 * Tombert   
 */             
        public static function Tombert($KONSTANS,$ERTEK,$Kulsovalaszt=null,$Belsovalaszst=null)
        {
                $Vissza="";
                $Tomb=self::Tombre($KONSTANS,$Kulsovalaszt=null,$Belsovalaszst=null);
                foreach ($Tomb as $egy)
                {
                    if ($egy["1"]=="$ERTEK")$Vissza=$egy["0"];
                }
                return $Vissza;

        }   
        
 /**
 * ParambolAd - adatbázis paraméter táblából visszaad egy értéket
 * @param $NEV - string paraméter neve
 * @return string
 */  
     public static function ParambolAd($NEV)
     {
        
            $Vane=CKozostar::Kozadtomb(KOZ_PARAM,$NEV);
            if ($Vane["Van"]=="1")
            {
                $Vissza=$Vane["Ertek"];
            }else
            {
                $Volt=self::$Sql->Lekerst("select * from PARAMETER where NEV_S='$NEV'");
                if ($Volt)$Vissza=$Volt[0]["ERTEK_S"];
                        else $Vissza="";
            }
            return $Vissza;
     }

 /**
 * Parambatesz - adatbázis paraméter táblába beletesz egy értéket,közös tárba, futás végén kiirás adatbázisba
 * @param $NEV - string paraméter neve
 * @param $ERTEK -  paraméter értéke
 * @param $Elozfelt - feltétel, sorszám generáláshoz generátor hiányába
 * @return 0 vagy 1 
 */
     public static function Parambatesz($NEV,$ERTEK,$Elozfelt="")
     {
        if ($Elozfelt!="")
        {
                self::$Sql->Modosit("update PARAMETER set ERTEK_S='$ERTEK' where NEV_S='$NEV' $Elozfelt");
                $Modszam=self::$Sql->Affectrows();
                if ($Modszam<1)$Vissza=0;
                    else $Vissza=1;
        }else
        {
            $Vissza=1;
            $Volt=$Volt=self::$Sql->Lekerst("select * from PARAMETER where NEV_S='$NEV' ");
            if ($Volt)
            {
                self::$Sql->Modosit("update PARAMETER set ERTEK_S='$ERTEK' where NEV_S='$NEV'");
            }
            else self::$Sql->Modosit("insert into PARAMETER (NEV_S,ERTEK_S) values ('$NEV','$ERTEK')");
        }
        
        if ($Vissza)
        {
             CKozostar::Kozbetomb(KOZ_PARAM,$ERTEK,$NEV);    
        }
        return $Vissza;

     }       
    
 /**
 * EmailCimLetzik - van e ilyen email cimmel felhasználó a rendszerbe
 * @param $EMAIL - string paraméter neve
 * @param $Azon -  paraméter értéke
 * @return 0 vagy 1 
 */    
    public static function EmailCimLetzik($EMAIL,$Azon="")
    {
        if ($Azon!="")$Felt=" and AZON_I<>'$Azon'";
                else $Felt="";

        $Login=self::$Sql->Lekerst("Select AZON_I from FELHASZNALO where (EMAIL_S='$EMAIL') $Felt");
        if ($Login)$Vissza=1;
             else $Vissza=0;
        return $Vissza;
    }

    public static function LoginLetzik($EMAIL,$Azon="")
    {
        if ($Azon!="")$Felt=" and AZON_I<>'$Azon'";
                else $Felt="";

        $Login=self::$Sql->Lekerst("Select AZON_I from FELHASZNALO where (LOGIN_S='$EMAIL') $Felt");
        if ($Login)$Vissza=1;
             else $Vissza=0;
        return $Vissza;
    }
    
    
             
    
/**
 * Fofutoe - Egy függvény főfutó e. Ezek a függvények megjelennek az oldalon url-be is. Ezek futtatásához jogosultságot is kell ellenőrizni.      
 * @param $fgvnev: függvény neve. Ha _fut a vége, akkor 1 lesz az eredmény 
 * @return 0 vagy 1 
 */     
    public static function Fofutoe($fgvnev)
    {
        $Vissza=false;
        $Vege=mb_substr($fgvnev,mb_strlen($fgvnev)-4,4,STRING_CODE);
        if ($Vege=="_fut")$Vissza=true;
        return $Vissza;       
    }
    


/**
 * Mailkuld - Email küldése html formába      
 * @param $TARGY: levél tárgya 
 * @param $SZOVEG: levél szövege  
 * @param $TO_EMAIL: címzett címe 
 * @param $TO_NAME: címzett neve 
 * @param $ALL1: állomány elérése - ha kell 
 * @param $FROM_EMAIL: feladó címe, ha nincs megadva FROM_EMAIL konstansból 
 * @param $FROM_NAME: feladó neve, ha nincs megadva FROM_NEV konstansból 
 */     
        function Mailkuld($TARGY,$SZOVEG,$TO_EMAIL,$TO_NAME=null,$ALL1=null,$FROM_EMAIL=null,$FROM_NAME=null)
        {
                
                require_once "php/Phpmailer.php";
                $mailer = new Mail_Phpmailer(true);

                if ($mailer->ValidateAddress($TO_EMAIL))
                {
                    $TARGY=$this->Futtat(Nyelv_azon)->Cserel($TARGY);
                    $SZOVEG=$this->Futtat(Nyelv_azon)->Cserel($SZOVEG);
                    if ($TO_NAME===null)$TO_NAME="";
                    if ($ALL1===null)$ALL1="";
                    $mailer->CharSet = 'utf-8';
                    $mailer->Encoding = 'base64';
                
                    if ($FROM_EMAIL===null)$FROM_EMAIL=FROM_EMAIL;
                    if ($FROM_NAME===null)$FROM_NAME=FROM_NEV;
                    $mailer->SetFrom($FROM_EMAIL,$FROM_NAME);
                    $mailer->AddAddress($TO_EMAIL,$TO_NAME);
                    $mailer->Subject =$TARGY;
                    if ($ALL1!="")
                    {
                        $mailer->AddAttachment($ALL1);                    
                    }
                    $mailer->Body = $SZOVEG;
                    $mailer->AltBody = strip_tags($SZOVEG);
                    $mailer->Send();
                }            
        }    
                
/**
 * Objclone - Egy objektumot lemásol      
 * @param $Mire objektum - mit klonnozunk 
 * @return objektum 
 */       
    public static function Objclone($Mire)
    {
        if (PHP_VERSION < 5)
        {
            $Vissza=$Mire;
        }else
        {
            $Vissza=clone ($Mire);
        }
        return $Vissza;
    }
    
            
/**
 * Mostfutobj - most futó objektum, nem sessionbe
*/   
    public function Mostfutobj()
    {
        $Obj=$this->KozAd("Mostfutobj",false);
        return $Obj;
    }
    
    
/**
 * Mostfutobjbe - most futó objektum, nem sessionbe
*/   
    public function Mostfutobjbe($Obj)
    {
        $this->Kozbe("Mostfutobj",$this->Objclone($Obj));
    }
     
     
                 
        function Erveny_nevre($Allomany_nev)
        {
                $Allomany_nev = strtolower($Allomany_nev);
                $Lehet="yxcvbnmasdfghjklqwertzuiopYXCVBNMASDFGHJKLQWERTZUIOP0123456789_.";
                $Hossz=strlen($Allomany_nev);
                $NEVJO=strtolower($Allomany_nev);
                $Jo=true;
                for ($c=0;$c<$Hossz;$c++)
                {
                        $Kar=mb_substr($Allomany_nev,$c,1,STRING_CODE);
                        $Temp=strpos($Lehet,$Kar);
                        if ($Temp===false)
                        {
                                $NEVJO=str_replace($Kar,"_",$NEVJO);
                        }

                }
                $Allomany_nev = $NEVJO;
                return $Allomany_nev;
        }

        
        function ArFormaz($AR,$Ft=true)
        {
                $ARSZ=round($AR);

                if ($ARSZ<1)return "";

                $tort=fmod($AR,1);

                $Tized=0;
                $AR=number_format($AR,$Tized,'.',' ');
                if ($Ft)$AR.=" Ft";
                return $AR;

        }





        function Egyedikulcs(&$TITLE,&$SZO,&$DESC)
        {
                $TITLE="";
                $SZO="";
                $DESC="";

                if (isset($this->Tabla->ADATOK["KERESO_TITLE_HU"]))
                {
                        $TITLE=$this->TablaAdatKi("KERESO_TITLE_HU");
                        $SZO=$this->TablaAdatKi("KERESO_KEY_HU");
                        $DESC=$this->TablaAdatKi("KERESO_DESC_HU");
                }
        }


        function Kulcsszavak(&$TITLE,&$KULCSSZO,&$DESCR,$NYELV="")
        {
                $TITLE="";
                $KULCSSZO="";
                $DESCR="";
                if ($NYELV=="")$NYELV=$_SESSION["Nyelv"];
                $Szo=Vegrehajt("select * from PARAMETER where NEV='KULCS_TITLE$NYELV'");
                if ($Szo)
                {
                        $TITLE=$Szo[0]["ERTEK"];
                }
                $Szo=Vegrehajt("select * from PARAMETER where NEV='KULCS_SZO$NYELV'");
                if ($Szo)
                {
                        $KULCSSZO=$Szo[0]["ERTEK"];
                }
                $Desc=Vegrehajt("select * from PARAMETER where NEV='KULCS_DESC$NYELV'");
                if ($Desc)
                {
                        $DESCR=$Desc[0]["ERTEK"];
                }
        }

        function Kulcsszo_tarol_be($TITLE,$KULCSSZO,$DESCR,$NYELV)
        {
                $Szo=Vegrehajt("select * from PARAMETER where NEV='KULCS_TITLE$NYELV'");
                if ($Szo)
                {
                        $SQL="update PARAMETER set ERTEK='$TITLE' where NEV='KULCS_TITLE$NYELV'";
                }else $SQL="insert into PARAMETER (NEV,ERTEK) values ('KULCS_TITLE$NYELV','$TITLE')";
                VegrehajtModosit($SQL);

                $Szo=Vegrehajt("select * from PARAMETER where NEV='KULCS_SZO$NYELV'");
                if ($Szo)
                {
                        $SQL="update PARAMETER set ERTEK='$KULCSSZO' where NEV='KULCS_SZO$NYELV'";
                }else $SQL="insert into PARAMETER (NEV,ERTEK) values ('KULCS_SZO$NYELV','$KULCSSZO')";
                VegrehajtModosit($SQL);


                $Szo=Vegrehajt("select * from PARAMETER where NEV='KULCS_DESC$NYELV'");
                if ($Szo)
                {
                        VegrehajtModosit("update PARAMETER set ERTEK='$DESCR' where NEV='KULCS_DESC$NYELV'");
                }else VegrehajtModosit("insert into PARAMETER (NEV,ERTEK) values ('KULCS_DESC$NYELV','$DESCR')");

        }




          function Paros($Szam)
        {
                if ($Szam==0)
                {
                        $Vissza=0;
                }
                $Seged=$Szam/2;
                $Vissza=!(is_double($Seged));
                return $Vissza;
                
        }





}




 /**
 * CFuttato - Objektumokat futtatja, minden futtatás ezen az osztályon keresztül történik. Egyszerre több objektumot is tudjon futtatni.
 * Fofutoe (ld: CAlap::Fofutoe) futtatáskor: Ha volt már utoljára futott objektum, akkor a futtatott objektum létrehozója az utoljára futott objektum lesz. Futás végén ő lesz az utoljára futott objektum 
 * Futtatást így lehet hívni: $this->Futtat(1)->UrlapKi_Fut($Param1,$Param2);   
 * Ha több objektumot futtat, eredmény is tömbbe megy vissza. Paramétereket referenciaként nem lehet átadni, ha olyan kellene, arra ott a közös tár
 * Ezt az osztályt VAZ használja futtatás során.
 * tagok: $Obj: amire/amikre a futtatást kell csinálni
      
 */

class CFuttato extends CAlap  
{
    private $Obj;
    private $Fofutobj=array();
    private $Futtatobj;

 /**
 * __construct - betölti/megjegyzi a futtatandó objektumokat, vagy egy objektum, vagy több objektum tömbbe
 * @param $Obj - vagy objektum vagy tömb  
 * @param $Futtatobjp - futtato objektum. Ha nem null, akkor nem fog most futtatni, hanem előző fofuto után, kivétel hanem fofuto -ról van szó   
 */    
    public function __construct($Objinp,$Futtatobjp=null)
    {
        parent::__construct();
        $this->Obj=$Objinp;    
        $this->Futtatobj=$Futtatobjp;    
    } 
    
 
/**
 * Futottobj - utoljára futott 'Fofutoe' objektumot adja vissza
*/   
    public function Futottobj()
    {
        $Obj=$this->Sessad("Futottobj",false);
        return $Obj;
    }
    
    
/**
 * Futottobjbe - beállítja futott objektumot sessionbe
*/   
    private function Futottobjbe($Obj)
    {
        $this->Sessbe("Futottobj",$this->Objclone($Obj));
    }
        
 
/**
 * __call - akármit hívunk ez a függvény fog lefutni 
 * @return ha a bejövő paraméter egy objektum akkor visszatérés az objektum visszatérése, 
 *         ha a bejövő tömb akkor visszamegy tömb: 
 *                                      ["Ossz"]= összes objektum száma amire végre kell hajtani a feladatot, pager miatt nem biztos hogy ez az objektumok száma
 *                                      ["Eredm"]= tömb, végrehajtott objektumonként függvény visszatérési értéke      
 *                                      ["Pager"]= ha limit-el hívtuk és szükséges visszaad pager-t is html formába      
 * ha _fut feladat fut le, akkor meg kell nézni van e jogosultsága futni
 * Figyelem sorrend két futas: első fut -> elindul második-> második vége-> első vége
 *          sorrend  két fofuto futás: első fut-> első vége, 2. fut -> 2. vége
 */    
    function __call($name, $arguments)
    {       
        $Fofuto=$this->Fofutoe($name);

        if (!(($this->Futtatobj===null)))
        {

            if ($Fofuto)
            {
                $Ment["Method"]=$name;
                $Ment["Arg"]=$arguments;
                $Ment["Objazon"]=$this->Obj;
                $Ment["Futtato"]=$this->Futtatobj;
                $this->Kozbe("Kovfuttat",$Ment);
                return "";
            }
            else
            {
//fofuto után gyerek fut    
                
                $this->Obj=$this->Futtatobj->Futtat_objad($this->Obj);

            }    
        }      
        
        if (is_array($this->Obj))
        {   
            $Eredmeny=array();
            $Eredmeny["Ossz"]=$this->Obj["Ossz"];
            $Eredmeny["Pager"]=$this->Obj["Pager"];
            $Eredmeny["Eredm"]=array();
            
            foreach ($this->Obj["Obj"] as $egy)
            {
                $Futhat=true;                
                if ($Fofuto)
                {
                    if ($egy->Hozzafer($name))$Futhat=true;
                                          else $Futhat=false;
                }
                if ($Futhat)
                {
                    $Eredmeny["Eredm"][]=$this->_Futtat($egy,$name,$arguments);
                }
                
                
            }
        }else
        if (is_object($this->Obj))
        {
                $Futhat=true;
                if ($Fofuto)
                {
                    if ($this->Obj->Hozzafer($name))$Futhat=true;
                            else $Futhat=false;
                }
                if ($Futhat)
                {
                    
                    $Eredmeny=$this->_Futtat($this->Obj,$name,$arguments); 
                }else $Eredmeny=false;
                
        }else $Eredmeny="";
        
        if ($Fofuto)
        {
            $Kovvan=$this->Kozad("Kovfuttat",false);
            if (is_array($Kovvan))
            {
                $Futtato=$Kovvan["Futtato"];                    
                $this->Kozuritegy("Kovfuttat");
                $Obj=$Futtato->Futtat_betesz($Kovvan["Objazon"]);
                $Eredmeny=$this->_Futtatcsinal($Obj,$Kovvan["Method"],$Kovvan["Arg"]);  
            }    
        }
        return $Eredmeny;    
    }
 

 
    
/**
 * _Futtat  
 * Fofutoe (ld: CAlap::Fofutoe) futtatáskor: Ha volt már utoljára futott objektum, akkor a futtatott objektum létrehozója az utoljára futott objektum lesz. 
 * Futás végén ő lesz az utoljára futott objektum. Beállítja utoljára futott feladatnak a feladatot. 
 * Egy oldal futáskor két Fofuto feladat lehet max, elsőt main indítja, a másik ennek a futás végén visszalépés lehet. Pl tároláskor: tárolás -> listázás, vagy új elem létrehozás-> új elem űrlap. 
 * Ilyenkor első futás lefut, utána jön a másik. 
 * A 2. Fofuton létrehozót nem állítunk be. Ha 2 Fofuto van, akkor az első fofuto-n nem állítunk be alapfeladatot.
 * @param $Obj objektum 
 * @param $Method objektum metódusa 
 * @param $Params paraméterek tömbben. Max 7 paramétert lehet átadni, de nem referenciaként. Továbbadva nem tömbként lesz, hanem ahogy hívtuk eredetileg. pl Lista(param1,param2..) 
 * @return futtatás eredmény 
 */     
        
    private function _Futtat($Obj,$Method,$Params)
    {
        
        
        $Fofuto=$this->Fofutoe($Method);
        
        if ($Fofuto)
        {
            if ($Obj->Uresobjektum())die ("Hiba");
            $this->Mostfutobjbe($Obj);
            $Utolobj=$this->Futottobj();
            $Futobjszam=$this->Kozad("Futobjszam",0);                

            if ("$Futobjszam"=="0")
            {
                if (is_object($Utolobj))
                {
                    if (!($this->EgyezoObjektum($Obj,$Utolobj)))
                    {
                        $Vane=$Obj->Letrehozovane();
                        if (!($Vane))$Obj->LetrehozoBe($Utolobj);
                    }
                }
            }
            $Futobjszam++;
            $this->Kozbe("Futobjszam",$Futobjszam);
            
            
        }        
        $Vissza=$this->_Futtatcsinal($Obj,$Method,$Params);
        if ($Fofuto)
        {
              
            $this->Futottobjbe($Obj);
            if ("$Method"!="Alap_fut")
            {
                $Kovvan=$this->Kozad("Kovfuttat",false);
                if (is_array($Kovvan))
                {
                    
                }else
                {
                    $Obj->Futottfeladatbe($Method);
                }
            }
        }


        return $Vissza;
        
    }
    
/**
* _Futtatcsinal - Futtatja az átadott objektum metódusát
* Futtatásra lehetne használni call_user_func_array php függvényt, de az a függvény meg fog szűnni, úgyhogy inkább nem használjuk.
*/
    
    private function _Futtatcsinal($Obj,$Method,$Params) 
    {
        $nargs = sizeof($Params);
        if ("$nargs"=="0") 
        {            
            $Vissza=$Obj->$Method();
        }
        elseif ("$nargs"=="1") 
        { 
            $Vissza=$Obj->$Method($Params[0]);
        }
        elseif ("$nargs"=="2") 
        { 
            $Vissza=$Obj->$Method($Params[0], $Params[1]);
        }
        elseif ("$nargs"=="3") 
        { 
            $Vissza=$Obj->$Method($Params[0],$Params[1],$Params[2]);
        }
        elseif ("$nargs"=="4") 
        { 
            $Vissza=$Obj->$Method($Params[0],$Params[1],$Params[2],$Params[3]);
        }
        elseif ("$nargs"=="5") 
        { 
            $Vissza=$Obj->$Method($Params[0],$Params[1],$Params[2],$Params[3],$Params[4]);
        }
        elseif ("$nargs"=="6") 
        { 
            $Vissza=$Obj->$Method($Params[0],$Params[1],$Params[2],$Params[3],$Params[4],$Params[5]);
        }
        else
        { 
            $Vissza=$Obj->$Method($Params[0],$Params[1],$Params[2],$Params[3],$Params[4],$Params[5],$Params[6]);
        }

        return $Vissza;
        
    }        
    
    

                
}




?>